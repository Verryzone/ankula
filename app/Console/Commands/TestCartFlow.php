<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;

class TestCartFlow extends Command
{
    protected $signature = 'test:cart-flow {user_id}';
    protected $description = 'Test complete cart flow for debugging';

    public function handle()
    {
        $userId = $this->argument('user_id');
        
        $user = User::find($userId);
        
        if (!$user) {
            $this->error("User with ID {$userId} not found");
            return;
        }

        $this->info("=== Testing Cart Flow for User: {$user->name} ===");

        // Step 1: Ensure cart exists
        $cart = Cart::firstOrCreate(['user_id' => $userId]);
        $this->info("Cart ID: {$cart->id}");

        // Step 2: Add test items to cart
        $products = Product::take(2)->get();
        
        if ($products->isEmpty()) {
            $this->warn("No products found in database");
            return;
        }

        foreach ($products as $product) {
            $cartItem = CartItem::updateOrCreate(
                ['cart_id' => $cart->id, 'product_id' => $product->id],
                ['quantity' => 1]
            );
            $this->info("Added/Updated cart item: Product {$product->name} (ID: {$cartItem->id})");
        }

        // Step 3: Test cart loading
        $cartWithItems = Cart::with(['cartItems.product.category'])
                            ->where('user_id', $userId)
                            ->first();

        if (!$cartWithItems || $cartWithItems->cartItems->isEmpty()) {
            $this->error("Failed to load cart with items");
            return;
        }

        $this->info("Cart loaded successfully with {$cartWithItems->cartItems->count()} items");

        // Step 4: Simulate checkout data
        $selectedItemIds = $cartWithItems->cartItems->pluck('id')->toArray();
        $this->info("Item IDs for checkout: " . implode(', ', $selectedItemIds));

        // Step 5: Test item selection (simulate checkout controller logic)
        $selectedItems = $cartWithItems->cartItems()
            ->whereIn('id', $selectedItemIds)
            ->with(['product.category'])
            ->get();

        if ($selectedItems->isEmpty()) {
            $this->error("Failed to find selected items - this is the bug!");
            
            // Debug info
            $this->info("Debug info:");
            $this->info("Requested IDs: " . implode(', ', $selectedItemIds));
            $this->info("Available cart item IDs: " . implode(', ', $cartWithItems->cartItems->pluck('id')->toArray()));
        } else {
            $this->info("Successfully found {$selectedItems->count()} selected items");
            foreach ($selectedItems as $item) {
                $this->line("  - Item {$item->id}: {$item->product->name} x {$item->quantity}");
            }
        }

        // Step 6: Test checkout URL generation
        $params = $selectedItemIds;
        $checkoutParams = implode('&', array_map(function($id) {
            return "items[]={$id}";
        }, $params));
        
        $this->info("Checkout URL params: {$checkoutParams}");
        $this->info("=== Test completed ===");
    }
}
