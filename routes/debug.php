<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\User;
use App\Models\Product;
use App\Http\Controllers\DebugController;

// Debug routes - Only for development
Route::middleware(['auth'])->group(function () {
    Route::get('/debug/cart-data', [DebugController::class, 'cartDebug']);
    Route::post('/debug/force-create-cart', [DebugController::class, 'forceCreateCart']);
    Route::post('/debug/add-test-item', [DebugController::class, 'addTestItem']);
    
    // Test checkout with sample data
    Route::get('/debug/test-checkout', function () {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Not authenticated']);
        }
        
        $cart = Cart::with(['cartItems.product'])->where('user_id', $user->id)->first();
        
        if (!$cart || $cart->cartItems->isEmpty()) {
            return response()->json(['error' => 'No cart items found']);
        }
        
        $itemIds = $cart->cartItems->pluck('id')->toArray();
        $checkoutUrl = route('checkout') . '?' . http_build_query(['items' => $itemIds]);
        
        return response()->json([
            'message' => 'Test checkout data generated',
            'cart_items' => $cart->cartItems,
            'item_ids' => $itemIds,
            'checkout_url' => $checkoutUrl
        ]);
    });
});

Route::get('/debug/midtrans-config', function () {
    return response()->json([
        'server_key' => config('midtrans.serverKey') ? 'Set (hidden)' : 'Not set',
        'client_key' => config('midtrans.clientKey') ? 'Set (hidden)' : 'Not set',
        'is_production' => config('midtrans.isProduction'),
        'is_sanitized' => config('midtrans.isSanitized'),
        'is_3ds' => config('midtrans.is3ds'),
    ]);
});
