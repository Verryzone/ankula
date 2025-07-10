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
        'payment_gateway_response',
        'paid_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_gateway_response' => 'json',
        'paid_at' => 'datetime'
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
            'status' => 'success',
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
}
