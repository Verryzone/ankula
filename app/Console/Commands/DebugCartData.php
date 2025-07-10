<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;

class DebugCartData extends Command
{
    protected $signature = 'debug:cart-data {user_id?}';
    protected $description = 'Debug cart data for troubleshooting cart issues';

    public function handle()
    {
        $userId = $this->argument('user_id');
        
        if ($userId) {
            $this->debugSpecificUser($userId);
        } else {
            $this->debugAllData();
        }
    }

    private function debugSpecificUser($userId)
    {
        $user = User::find($userId);
        
        if (!$user) {
            $this->error("User with ID {$userId} not found");
            return;
        }

        $this->info("=== Debug data for User ID: {$userId} ===");
        $this->info("User: {$user->name} ({$user->email}) - Role: {$user->role}");

        $cart = Cart::where('user_id', $userId)->first();
        
        if (!$cart) {
            $this->warn("No cart found for this user");
            $this->info("Creating cart for user...");
            $cart = Cart::create(['user_id' => $userId]);
            $this->info("Cart created with ID: {$cart->id}");
        } else {
            $this->info("Cart found with ID: {$cart->id}");
        }

        $cartItems = $cart->cartItems()->with('product')->get();
        
        if ($cartItems->isEmpty()) {
            $this->warn("No cart items found");
        } else {
            $this->info("Cart items:");
            foreach ($cartItems as $item) {
                $productName = $item->product ? $item->product->name : 'N/A';
                $this->line("  - Item ID: {$item->id}, Product: {$productName}, Quantity: {$item->quantity}");
            }
        }
    }

    private function debugAllData()
    {
        $this->info("=== Cart System Debug Summary ===");
        
        $userCount = User::count();
        $cartCount = Cart::count();
        $cartItemCount = CartItem::count();
        $productCount = Product::count();
        
        $this->info("Total Users: {$userCount}");
        $this->info("Total Carts: {$cartCount}");
        $this->info("Total Cart Items: {$cartItemCount}");
        $this->info("Total Products: {$productCount}");
        
        // Check for users without carts
        $usersWithoutCarts = User::whereDoesntHave('cart')->where('role', 'customer')->get();
        
        if ($usersWithoutCarts->count() > 0) {
            $this->warn("Users without carts:");
            foreach ($usersWithoutCarts as $user) {
                $this->line("  - User ID: {$user->id}, Name: {$user->name}");
            }
        }
        
        // Check for cart items without products
        $itemsWithoutProducts = CartItem::whereDoesntHave('product')->get();
        
        if ($itemsWithoutProducts->count() > 0) {
            $this->warn("Cart items without valid products:");
            foreach ($itemsWithoutProducts as $item) {
                $this->line("  - Cart Item ID: {$item->id}, Product ID: {$item->product_id}");
            }
        }
        
        $this->info("Debug completed.");
    }
}
