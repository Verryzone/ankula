<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use Exception;

class MidtransService
{
    public function __construct()
    {
        // Set konfigurasi Midtrans
        Config::$serverKey = config('midtrans.serverKey');
        Config::$isProduction = config('midtrans.isProduction', false);
        Config::$isSanitized = config('midtrans.isSanitized', true);
        Config::$is3ds = config('midtrans.is3ds', true);
    }

    /**
     * Create payment transaction
     */
    public function createTransaction(Order $order)
    {
        try {
            // Validate server key
            if (empty(config('midtrans.serverKey'))) {
                throw new Exception('Midtrans server key tidak dikonfigurasi. Silakan periksa file .env');
            }

            // Log config for debugging
            Log::info('Midtrans Config', [
                'serverKey' => substr(config('midtrans.serverKey'), 0, 10) . '...',
                'isProduction' => config('midtrans.isProduction'),
            ]);

            // Configure Midtrans again to ensure it's set
            Config::$serverKey = config('midtrans.serverKey');
            Config::$isProduction = config('midtrans.isProduction');
            Config::$isSanitized = config('midtrans.isSanitized');
            Config::$is3ds = config('midtrans.is3ds');

            // Prepare transaction data
            $transactionDetails = [
                'order_id' => $order->order_number,
                'gross_amount' => (int) ($order->total_amount + $order->shipping_cost),
            ];

            // Customer details
            $customerDetails = [
                'first_name' => $order->user->name,
                'email' => $order->user->email,
                'phone' => $order->user->phone ?? '08111222333'
            ];

            // Add billing address if shipping address exists
            if ($order->shipping_address_snapshot && is_array($order->shipping_address_snapshot)) {
                $shippingAddr = $order->shipping_address_snapshot;
                $customerDetails['billing_address'] = [
                    'first_name' => $order->user->name,
                    'address' => $shippingAddr['address_line'] ?? 'Default Address',
                    'city' => $shippingAddr['city'] ?? 'Default City',
                    'postal_code' => $shippingAddr['postal_code'] ?? '12345',
                    'country_code' => 'IDN'
                ];
            }

            // Item details
            $itemDetails = [];
            foreach ($order->orderItems as $item) {
                $itemDetails[] = [
                    'id' => $item->product_id,
                    'price' => (int) $item->price,
                    'quantity' => $item->quantity,
                    'name' => $item->product->name ?? 'Product'
                ];
            }

            // Add shipping cost as separate item if exists
            if ($order->shipping_cost > 0) {
                $itemDetails[] = [
                    'id' => 'shipping',
                    'price' => (int) $order->shipping_cost,
                    'quantity' => 1,
                    'name' => 'Ongkos Kirim'
                ];
            }

            $transactionData = [
                'transaction_details' => $transactionDetails,
                'customer_details' => $customerDetails,
                'item_details' => $itemDetails,
            ];

            // Log transaction data for debugging
            Log::info('Creating Midtrans transaction', [
                'order_id' => $order->order_number,
                'gross_amount' => $transactionDetails['gross_amount'],
                'item_details' => $itemDetails,
                'customer_details' => $customerDetails
            ]);

            // Create snap token
            $snapToken = Snap::getSnapToken($transactionData);

            // Create payment record
            $payment = Payment::create([
                'order_id' => $order->id,
                'payment_method' => 'midtrans',
                'amount' => $order->total_amount + $order->shipping_cost,
                'status' => 'pending',
                'transaction_id' => $order->order_number,
                'snap_token' => $snapToken,
                'snap_token_expires_at' => now()->addHours(24) // Snap token expired in 24 hours
            ]);

            Log::info('Midtrans Snap token created successfully', [
                'order_id' => $order->order_number,
                'payment_id' => $payment->id,
                'snap_token' => substr($snapToken, 0, 20) . '...'
            ]);

            return [
                'success' => true,
                'snap_token' => $snapToken,
                'payment' => $payment
            ];

        } catch (Exception $e) {
            Log::error('Midtrans transaction creation failed', [
                'order_id' => $order->order_number ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'message' => 'Gagal membuat transaksi pembayaran: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Handle notification from Midtrans
     */
    public function handleNotification($notification)
    {
        try {
            $orderId = $notification['order_id'];
            $transactionStatus = $notification['transaction_status'];
            $fraudStatus = $notification['fraud_status'] ?? 'accept';

            // Find payment
            $payment = Payment::where('transaction_id', $orderId)->first();

            if (!$payment) {
                Log::warning('Payment not found for order: ' . $orderId);
                return ['success' => false, 'message' => 'Payment not found'];
            }

            Log::info('Midtrans notification received', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus
            ]);

            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    $payment->update(['status' => 'pending']);
                } else if ($fraudStatus == 'accept') {
                    $payment->markAsSuccess($orderId, $notification);
                }
            } else if ($transactionStatus == 'settlement') {
                $payment->markAsSuccess($orderId, $notification);
            } else if ($transactionStatus == 'pending') {
                $payment->update(['status' => 'pending']);
            } else if (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                $payment->markAsFailed($notification);
            }

            return ['success' => true, 'message' => 'Notification processed'];

        } catch (\Exception $e) {
            Log::error('Midtrans notification handling failed', [
                'notification' => $notification,
                'error' => $e->getMessage()
            ]);

            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Check transaction status
     */
    public function checkTransactionStatus($orderId)
    {
        try {
            $status = Transaction::status($orderId);
            return [
                'success' => true,
                'data' => $status
            ];
        } catch (\Exception $e) {
            Log::error('Failed to check transaction status', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Cancel transaction
     */
    public function cancelTransaction($orderId)
    {
        try {
            $result = Transaction::cancel($orderId);
            return [
                'success' => true,
                'data' => $result
            ];
        } catch (\Exception $e) {
            Log::error('Failed to cancel transaction', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}