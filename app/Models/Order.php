<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shipping_address_id',
        'order_number',
        'total_amount',
        'shipping_cost',
        'status',
        'shipping_address_snapshot'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'shipping_address_snapshot' => 'json'
    ];

    /**
     * Generate unique order number
     */
    public static function generateOrderNumber()
    {
        do {
            // Format: INV202507180001-AB1C (with timestamp and random suffix for uniqueness)
            $prefix = 'INV' . date('YmdHi'); // Include hour and minute for better uniqueness
            $randomSuffix = strtoupper(substr(md5(uniqid(rand(), true)), 0, 4));
            $orderNumber = $prefix . '-' . $randomSuffix;
            
            // Check if this order number already exists
            $exists = self::where('order_number', $orderNumber)->exists();
        } while ($exists);
        
        return $orderNumber;
    }

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shippingAddress()
    {
        return $this->belongsTo(Addresses::class, 'shipping_address_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
