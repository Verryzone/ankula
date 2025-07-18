<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class FixOrderStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:fix-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix order status based on payment status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fixing order status based on payment status...');

        // Find orders with pending status but have successful payments
        $ordersToFix = Order::where('status', 'pending')
            ->whereHas('payment', function($query) {
                $query->where('status', 'success');
            })
            ->with('payment')
            ->get();

        if ($ordersToFix->isEmpty()) {
            $this->info('No orders found that need status fixing.');
            return 0;
        }

        $this->info("Found {$ordersToFix->count()} orders that need status fixing:");

        foreach ($ordersToFix as $order) {
            $this->line("Order: {$order->order_number}");
            $this->line("  Current order status: {$order->status}");
            $this->line("  Payment status: {$order->payment->status}");
            $this->line("  Payment paid at: {$order->payment->paid_at}");

            // Update order status to processing
            $order->update(['status' => 'processing']);
            
            $this->info("  ✅ Updated order status to: processing");
            
            Log::info('Order status fixed', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'old_status' => 'pending',
                'new_status' => 'processing',
                'payment_status' => $order->payment->status,
                'paid_at' => $order->payment->paid_at
            ]);
            
            $this->line("");
        }

        $this->info("✅ Fixed {$ordersToFix->count()} orders successfully!");
        
        return 0;
    }
}
