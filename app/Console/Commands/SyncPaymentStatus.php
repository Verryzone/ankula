<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\Payment;
use App\Services\MidtransService;

class SyncPaymentStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:sync {order_number?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync payment status with Midtrans for pending payments';

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
        $orderNumber = $this->argument('order_number');
        
        if ($orderNumber) {
            // Sync specific order
            $this->syncSingleOrder($orderNumber);
        } else {
            // Sync all pending payments
            $this->syncAllPendingPayments();
        }
    }

    private function syncSingleOrder($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->first();
        
        if (!$order) {
            $this->error("Order {$orderNumber} not found");
            return;
        }

        $payment = Payment::getByOrder($order->id);
        
        if (!$payment) {
            $this->error("Payment for order {$orderNumber} not found");
            return;
        }

        $this->info("Checking payment status for order: {$orderNumber}");
        
        $result = $this->midtransService->checkTransactionStatus($orderNumber);
        
        if ($result['success']) {
            $status = $result['data'];
            $transactionStatus = is_object($status) ? $status->transaction_status : $status['transaction_status'];
            
            $this->info("Midtrans status: {$transactionStatus}");
            $this->info("Current payment status: {$payment->status}");
            $this->info("Current order status: {$order->status}");
            
            // Update payment based on actual status
            if (in_array($transactionStatus, ['capture', 'settlement'])) {
                $payment->markAsSuccess($orderNumber, $status);
                $this->info("✅ Payment marked as paid");
            } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                $payment->markAsFailed($status);
                $this->info("❌ Payment marked as failed");
            } else {
                $payment->update(['status' => 'pending']);
                $this->info("⏳ Payment status updated to pending");
            }
            
        } else {
            $this->error("Failed to check status: " . $result['message']);
        }
    }

    private function syncAllPendingPayments()
    {
        $pendingPayments = Payment::where('status', 'pending')
            ->with('order')
            ->get();
            
        $this->info("Found " . $pendingPayments->count() . " pending payments");
        
        foreach ($pendingPayments as $payment) {
            $this->syncSingleOrder($payment->order->order_number);
            $this->line("---");
        }
        
        $this->info("Sync completed!");
    }
}
