<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentFactory> */
    use HasFactory;

    protected $fillable = [
        'order_id',
        'payment_method',
        'amount',
        'status',
        'transaction_id',
        'snap_token',
        'snap_token_expires_at',
        'payment_gateway_response',
        'paid_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_gateway_response' => 'json',
        'paid_at' => 'datetime',
        'snap_token_expires_at' => 'datetime'
    ];

    /**
     * Relationships
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeSuccess($query)
    {
        return $query->where('status', 'success');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'success');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Mark payment as success
     */
    public function markAsSuccess($transactionId = null, $gatewayResponse = null)
    {
        $this->update([
            'status' => 'success',  // Use 'success' as defined in enum
            'transaction_id' => $transactionId,
            'payment_gateway_response' => $gatewayResponse,
            'paid_at' => now()
        ]);

        // Update order status
        $this->order->update(['status' => 'processing']);
    }

    /**
     * Mark payment as failed
     */
    public function markAsFailed($gatewayResponse = null)
    {
        $this->update([
            'status' => 'failed',
            'payment_gateway_response' => $gatewayResponse
        ]);
    }

    /**
     * Check if snap token is still valid
     */
    public function isSnapTokenValid()
    {
        return $this->snap_token && $this->snap_token_expires_at && $this->snap_token_expires_at > now();
    }

    /**
     * Get payment by order
     */
    public static function getByOrder($orderId)
    {
        return static::where('order_id', $orderId)->first();
    }
}
