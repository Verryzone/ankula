<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\Payment;
use App\Services\MidtransService;
use Illuminate\Support\Facades\Log;

class TestWebhookNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'webhook:test {order_number} {status=settlement}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test webhook notification manually';

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
        $status = $this->argument('status');

        // Find order
        $order = Order::where('order_number', $orderNumber)->first();
        
        if (!$order) {
            $this->error("Order {$orderNumber} tidak ditemukan");
            return 1;
        }

        // Find payment
        $payment = Payment::where('order_id', $order->id)->first();
        
        if (!$payment) {
            $this->error("Payment untuk order {$orderNumber} tidak ditemukan");
            return 1;
        }

        $this->info("Testing webhook notification untuk order: {$orderNumber}");
        $this->info("Current payment status: {$payment->status}");
        $this->info("Transaction ID: {$payment->transaction_id}");

        // Simulate webhook notification
        $notification = [
            'order_id' => $payment->transaction_id,
            'transaction_status' => $status,
            'fraud_status' => 'accept',
            'payment_type' => 'credit_card',
            'transaction_time' => now()->toDateTimeString(),
            'gross_amount' => (string)($order->total_amount + $order->shipping_cost),
            'currency' => 'IDR',
            'status_code' => '200',
            'status_message' => 'Success, transaction found'
        ];

        $this->info("Simulating notification dengan data:");
        $this->line(json_encode($notification, JSON_PRETTY_PRINT));

        // Process notification
        $result = $this->midtransService->handleNotification($notification);

        if ($result['success']) {
            $this->info("✅ Webhook processed successfully");
            
            // Refresh payment
            $payment = $payment->fresh();
            $this->info("New payment status: {$payment->status}");
            
            if ($payment->status === 'success') {
                $this->info("🎉 Payment marked as success!");
                $this->info("Paid at: " . ($payment->paid_at ? $payment->paid_at->format('Y-m-d H:i:s') : 'Not set'));
            }
        } else {
            $this->error("❌ Webhook processing failed: " . $result['message']);
        }

        return 0;
    }
}
