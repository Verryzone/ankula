<?php

namespace Database\Seeders;

use App\Models\DashboardHighlight;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DashboardHighlightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DashboardHighlight::create([
            'title' => 'Bring Happiness From Shopping Everyday',
            'description' => 'Find the perfect product for your needs.',
            'price' => 449.99,
            'button_text' => 'Shop Now',
            'button_link' => null,
            'is_active' => true,
            'sort_order' => 0
        ]);

        DashboardHighlight::create([
            'title' => 'New Fashion Collection',
            'description' => 'Discover our latest trendy items for this season.',
            'price' => 299.99,
            'button_text' => 'Explore Now',
            'button_link' => null,
            'is_active' => true,
            'sort_order' => 1
        ]);

        DashboardHighlight::create([
            'title' => 'Special Summer Sale',
            'description' => 'Get up to 50% off on selected items.',
            'price' => null,
            'button_text' => 'View Deals',
            'button_link' => null,
            'is_active' => false,
            'sort_order' => 2
        ]);
    }
}
