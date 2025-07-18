<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Services\MidtransService;

class CheckAllPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:check-all {--fix : Automatically fix mismatched statuses}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check all payment statuses from Midtrans and optionally fix mismatches';

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
        $this->info('Checking all payment statuses...');
        
        $orders = Order::with('payment')
            ->whereHas('payment', function($query) {
                $query->whereIn('status', ['pending']);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        if ($orders->isEmpty()) {
            $this->info('No pending payments found.');
            return;
        }

        $this->info("Found {$orders->count()} orders with pending payments:");
        
        $headers = ['Order Number', 'Order Status', 'Payment Status', 'Midtrans Status', 'Action'];
        $tableData = [];
        $fixedCount = 0;

        foreach ($orders as $order) {
            if (!$order->payment || !$order->payment->transaction_id) {
                $tableData[] = [
                    $order->order_number,
                    $order->status,
                    $order->payment ? $order->payment->status : 'No Payment',
                    'No Transaction ID',
                    'Skipped'
                ];
                continue;
            }

            try {
                // Get status from Midtrans
                $transactionStatus = \Midtrans\Transaction::status($order->payment->transaction_id);
                $midtransStatus = is_object($transactionStatus) ? $transactionStatus->transaction_status : null;
                
                $action = 'No Change';
                
                // Check if status needs fixing
                if (($midtransStatus === 'settlement' || $midtransStatus === 'capture') && 
                    $order->payment->status === 'pending') {
                    
                    if ($this->option('fix')) {
                        // Update payment status
                        $order->payment->update([
                            'status' => 'success',
                            'paid_at' => now(),
                            'payment_reference' => is_object($transactionStatus) ? ($transactionStatus->transaction_id ?? null) : null
                        ]);
                        
                        // Update order status
                        $order->update(['status' => 'processing']);
                        
                        $action = 'Fixed ✅';
                        $fixedCount++;
                    } else {
                        $action = 'Needs Fix ❌';
                    }
                }
                
                $tableData[] = [
                    $order->order_number,
                    $order->status,
                    $order->payment->status,
                    $midtransStatus,
                    $action
                ];
                
            } catch (\Exception $e) {
                $tableData[] = [
                    $order->order_number,
                    $order->status,
                    $order->payment->status,
                    'Error: ' . $e->getMessage(),
                    'Failed'
                ];
            }
        }

        $this->table($headers, $tableData);
        
        if ($this->option('fix')) {
            $this->info("Fixed {$fixedCount} payment statuses.");
        } else {
            $needsFixCount = collect($tableData)->where(4, 'Needs Fix ❌')->count();
            if ($needsFixCount > 0) {
                $this->warn("{$needsFixCount} payments need fixing. Run with --fix to automatically fix them:");
                $this->line("php artisan payments:check-all --fix");
            }
        }
    }
}
