<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\Payment;
use App\Services\MidtransService;
use Illuminate\Support\Facades\Log;

class AutoCheckPayments extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'payments:auto-check 
                           {--interval=300 : Check interval in seconds (default: 5 minutes)}
                           {--max-age=3600 : Maximum age of pending payments to check in seconds (default: 1 hour)}';

    /**
     * The console command description.
     */
    protected $description = 'Automatically check and update payment statuses from Midtrans';

    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        parent::__construct();
        $this->midtransService = $midtransService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $interval = (int) $this->option('interval');
        $maxAge = (int) $this->option('max-age');
        
        $this->info("Starting auto payment checker...");
        $this->info("Check interval: {$interval} seconds");
        $this->info("Max age for pending payments: {$maxAge} seconds");
        $this->info("Press Ctrl+C to stop");
        
        while (true) {
            try {
                $this->checkPendingPayments($maxAge);
                $this->line("Next check in {$interval} seconds...");
                sleep($interval);
            } catch (\Exception $e) {
                $this->error("Error during auto-check: " . $e->getMessage());
                Log::error('Auto payment check failed', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                sleep(60); // Wait 1 minute before retrying
            }
        }
    }

    private function checkPendingPayments($maxAge)
    {
        $cutoffTime = now()->subSeconds($maxAge);
        
        $pendingPayments = Payment::where('status', 'pending')
            ->where('created_at', '>=', $cutoffTime)
            ->whereNotNull('transaction_id')
            ->with('order')
            ->get();

        if ($pendingPayments->isEmpty()) {
            $this->line(now()->format('H:i:s') . " - No pending payments to check");
            return;
        }

        $this->line(now()->format('H:i:s') . " - Checking {$pendingPayments->count()} pending payments...");
        $updatedCount = 0;

        foreach ($pendingPayments as $payment) {
            try {
                // Get status from Midtrans
                $transactionStatus = \Midtrans\Transaction::status($payment->transaction_id);
                $midtransStatus = is_object($transactionStatus) ? $transactionStatus->transaction_status : null;
                
                if (!$midtransStatus) {
                    continue;
                }

                $oldStatus = $payment->status;
                $orderOldStatus = $payment->order->status;

                // Check if status needs updating
                if (($midtransStatus === 'settlement' || $midtransStatus === 'capture') && 
                    $payment->status === 'pending') {
                    
                    // Update payment status
                    $payment->update([
                        'status' => 'success',
                        'paid_at' => now(),
                        'payment_reference' => is_object($transactionStatus) ? ($transactionStatus->transaction_id ?? null) : null
                    ]);
                    
                    // Update order status
                    $payment->order->update(['status' => 'processing']);
                    
                    $updatedCount++;
                    
                    $this->info("✅ Updated: {$payment->order->order_number} | Payment: {$oldStatus} → success | Order: {$orderOldStatus} → processing");
                    
                    Log::info('Auto-updated payment status', [
                        'order_number' => $payment->order->order_number,
                        'payment_id' => $payment->id,
                        'old_payment_status' => $oldStatus,
                        'new_payment_status' => 'success',
                        'old_order_status' => $orderOldStatus,
                        'new_order_status' => 'processing',
                        'midtrans_status' => $midtransStatus
                    ]);
                    
                } elseif (in_array($midtransStatus, ['deny', 'expire', 'cancel', 'failure']) && 
                         $payment->status === 'pending') {
                    
                    // Mark as failed
                    $payment->update([
                        'status' => 'failed',
                        'payment_reference' => is_object($transactionStatus) ? ($transactionStatus->transaction_id ?? null) : null
                    ]);
                    
                    $payment->order->update(['status' => 'cancelled']);
                    
                    $updatedCount++;
                    
                    $this->warn("❌ Failed: {$payment->order->order_number} | Payment: {$oldStatus} → failed | Order: {$orderOldStatus} → cancelled");
                    
                    Log::info('Auto-updated failed payment', [
                        'order_number' => $payment->order->order_number,
                        'payment_id' => $payment->id,
                        'old_payment_status' => $oldStatus,
                        'new_payment_status' => 'failed',
                        'midtrans_status' => $midtransStatus
                    ]);
                }
                
            } catch (\Exception $e) {
                $this->error("Error checking payment {$payment->id}: " . $e->getMessage());
                Log::error('Error in auto payment check', [
                    'payment_id' => $payment->id,
                    'order_number' => $payment->order->order_number ?? 'unknown',
                    'error' => $e->getMessage()
                ]);
            }
        }

        if ($updatedCount > 0) {
            $this->info("✅ Updated {$updatedCount} payments successfully");
        } else {
            $this->line(now()->format('H:i:s') . " - All payments are up to date");
        }
    }
}
