<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\Payment;
use App\Services\MidtransService;
use Illuminate\Support\Facades\Log;

class CheckPaymentStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:check-status {order_number?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and update payment status from Midtrans';

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
            // Check specific order
            $this->checkSingleOrder($orderNumber);
        } else {
            // Check all pending payments
            $this->checkAllPendingPayments();
        }

        return 0;
    }

    private function checkSingleOrder($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->first();
        
        if (!$order) {
            $this->error("Order {$orderNumber} tidak ditemukan");
            return;
        }

        $payment = Payment::where('order_id', $order->id)->first();
        
        if (!$payment) {
            $this->error("Payment untuk order {$orderNumber} tidak ditemukan");
            return;
        }

        $this->info("Checking payment status untuk order: {$orderNumber}");
        $this->info("Current status: {$payment->status}");
        $this->info("Transaction ID: {$payment->transaction_id}");

        // Check status from Midtrans
        $result = $this->midtransService->checkTransactionStatus($payment->transaction_id);

        if ($result['success']) {
            $status = $result['data'];
            $transactionStatus = is_object($status) ? $status->transaction_status : $status['transaction_status'];
            
            $this->info("Midtrans status: {$transactionStatus}");

            // Update payment status
            if (in_array($transactionStatus, ['capture', 'settlement'])) {
                $payment->markAsSuccess($payment->transaction_id, $status);
                $this->info("✅ Payment marked as success");
            } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                $payment->markAsFailed($status);
                $this->info("❌ Payment marked as failed");
            } else {
                $this->info("⏳ Payment status unchanged: {$transactionStatus}");
            }
        } else {
            $this->error("Failed to check status: " . $result['message']);
        }
    }

    private function checkAllPendingPayments()
    {
        $this->info('Checking all pending payments...');

        $pendingPayments = Payment::where('status', 'pending')
            ->whereHas('order')
            ->with('order')
            ->get();

        if ($pendingPayments->isEmpty()) {
            $this->info('No pending payments found.');
            return;
        }

        $this->info("Found {$pendingPayments->count()} pending payments");

        foreach ($pendingPayments as $payment) {
            $this->info("Checking order: {$payment->order->order_number}");
            
            $result = $this->midtransService->checkTransactionStatus($payment->transaction_id);

            if ($result['success']) {
                $status = $result['data'];
                $transactionStatus = is_object($status) ? $status->transaction_status : $status['transaction_status'];
                
                $this->line("  Midtrans status: {$transactionStatus}");

                if (in_array($transactionStatus, ['capture', 'settlement'])) {
                    $payment->markAsSuccess($payment->transaction_id, $status);
                    $this->info("  ✅ Updated to success");
                } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                    $payment->markAsFailed($status);
                    $this->info("  ❌ Updated to failed");
                }
            } else {
                $this->error("  Failed to check: " . $result['message']);
            }
        }
    }
}
