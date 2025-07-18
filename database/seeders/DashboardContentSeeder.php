<?php

namespace Database\Seeders;

use App\Models\DashboardContent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DashboardContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DashboardContent::create([
            'title' => 'Clear Choice Price',
            'subtitle' => 'Earn 20% Back in Rewards',
            'description' => null,
            'price' => null,
            'price_text' => null,
            'button_text' => 'Shop Now',
            'button_link' => null,
            'background_color' => '#ffffff',
            'text_color' => '#1f2937',
            'size' => 'large',
            'type' => 'promo',
            'is_active' => true,
            'sort_order' => 0
        ]);

        DashboardContent::create([
            'title' => 'Charge Your Devices',
            'subtitle' => null,
            'description' => null,
            'price' => 29.99,
            'price_text' => 'Starting from',
            'button_text' => 'Shop Now',
            'button_link' => null,
            'background_color' => '#ffffff',
            'text_color' => '#1f2937',
            'size' => 'small',
            'type' => 'category',
            'is_active' => true,
            'sort_order' => 1
        ]);

        DashboardContent::create([
            'title' => 'Summer Sale',
            'subtitle' => 'Up to 50% Off',
            'description' => 'Limited time offer on selected items',
            'price' => null,
            'price_text' => null,
            'button_text' => 'Shop Sale',
            'button_link' => null,
            'background_color' => '#fef3c7',
            'text_color' => '#92400e',
            'size' => 'medium',
            'type' => 'featured',
            'is_active' => false,
            'sort_order' => 2
        ]);
    }
}
