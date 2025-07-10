<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    public function __construct()
    {
        // Set konfigurasi Midtrans
        Config::$serverKey = config('midtrans.serverKey');
        Config::$isProduction = config('midtrans.isProduction');
        Config::$isSanitized = config('midtrans.isSanitized');
        Config::$is3ds = config('midtrans.is3ds');
    }

    /**
     * Create payment transaction
     */
    public function createTransaction(Order $order)
    {
        try {
            // Prepare transaction details
            $transactionDetails = [
                'order_id' => $order->order_number,
                'gross_amount' => (int) ($order->total_amount + $order->shipping_cost),
            ];

            // Prepare item details
            $itemDetails = [];
            foreach ($order->orderItems as $item) {
                $itemDetails[] = [
                    'id' => $item->product_id,
                    'price' => (int) $item->price,
                    'quantity' => $item->quantity,
                    'name' => $item->product->name,
                ];
            }

            // Add shipping cost as item if exists
            if ($order->shipping_cost > 0) {
                $itemDetails[] = [
                    'id' => 'shipping',
                    'price' => (int) $order->shipping_cost,
                    'quantity' => 1,
                    'name' => 'Ongkos Kirim',
                ];
            }

            // Customer details
            $customerDetails = [
                'first_name' => $order->user->name,
                'email' => $order->user->email,
                'phone' => $order->shippingAddress ? $order->shippingAddress->phone_number : '',
            ];

            // Shipping address
            if ($order->shippingAddress) {
                $customerDetails['shipping_address'] = [
                    'first_name' => $order->shippingAddress->recipient_name,
                    'phone' => $order->shippingAddress->phone_number,
                    'address' => $order->shippingAddress->address_line,
                    'city' => $order->shippingAddress->city,
                    'postal_code' => $order->shippingAddress->postal_code,
                    'country_code' => 'IDN'
                ];
            }

            // Transaction data
            $transactionData = [
                'transaction_details' => $transactionDetails,
                'item_details' => $itemDetails,
                'customer_details' => $customerDetails,
                'enabled_payments' => [
                    'credit_card', 'bca_va', 'bni_va', 'bri_va', 
                    'echannel', 'other_va', 'gopay', 'shopeepay', 'qris'
                ],
                'vtweb' => [
                    'enabled_payments' => [
                        'credit_card', 'bca_va', 'bni_va', 'bri_va', 
                        'echannel', 'other_va', 'gopay', 'shopeepay', 'qris'
                    ]
                ]
            ];

            // Create transaction
            $snapToken = Snap::getSnapToken($transactionData);

            // Create payment record
            $payment = Payment::create([
                'order_id' => $order->id,
                'payment_method' => 'midtrans',
                'amount' => $order->total_amount + $order->shipping_cost,
                'status' => 'pending',
                'transaction_id' => $order->order_number,
            ]);

            return [
                'success' => true,
                'snap_token' => $snapToken,
                'payment' => $payment
            ];

        } catch (\Exception $e) {
            Log::error('Midtrans transaction creation failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
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
                    // Handle challenge status
                    $payment->update(['status' => 'pending']);
                } else if ($fraudStatus == 'accept') {
                    // Payment success
                    $payment->markAsSuccess($orderId, $notification);
                }
            } else if ($transactionStatus == 'settlement') {
                // Payment success
                $payment->markAsSuccess($orderId, $notification);
            } else if ($transactionStatus == 'pending') {
                // Payment pending
                $payment->update(['status' => 'pending']);
            } else if (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                // Payment failed
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
