<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DashboardContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'image_path',
        'price',
        'price_text',
        'button_text',
        'button_link',
        'background_color',
        'text_color',
        'size',
        'type',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function getPriceDisplayAttribute()
    {
        if (!$this->price) {
            return null;
        }

        $formattedPrice = 'Rp ' . number_format((float)$this->price, 0, ',', '.');
        
        if ($this->price_text) {
            return $this->price_text . ' ' . $formattedPrice;
        }

        return $formattedPrice;
    }
}
