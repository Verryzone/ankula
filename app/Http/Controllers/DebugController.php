<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;

class DebugController extends Controller
{
    public function cartDebug()
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Not authenticated']);
        }
        
        $cart = Cart::with(['cartItems.product'])->where('user_id', $user->id)->first();
        
        $debug_data = [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role
            ],
            'cart' => $cart,
            'cart_items_count' => $cart ? $cart->cartItems->count() : 0,
            'all_users' => User::select('id', 'name', 'email', 'role')->get(),
            'all_carts' => Cart::with('cartItems')->get(),
            'all_cart_items' => CartItem::with('product')->get(),
            'total_products' => Product::count(),
            'session_data' => [
                'session_id' => session()->getId(),
                'csrf_token' => csrf_token()
            ]
        ];
        
        Log::info('Cart debug data requested', $debug_data);
        
        return response()->json($debug_data);
    }

    public function forceCreateCart()
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Not authenticated']);
        }

        // Force create cart if not exists
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);
        
        return response()->json([
            'message' => 'Cart created/found',
            'cart' => $cart,
            'user' => $user
        ]);
    }

    public function addTestItem(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Not authenticated']);
        }

        $productId = $request->get('product_id', 1); // Default to product ID 1
        $quantity = $request->get('quantity', 1);

        // Check if product exists
        $product = Product::find($productId);
        if (!$product) {
            return response()->json(['error' => 'Product not found']);
        }

        // Get or create cart
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        // Check if item already exists
        $cartItem = $cart->cartItems()->where('product_id', $productId)->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            $cartItem = $cart->cartItems()->create([
                'product_id' => $productId,
                'quantity' => $quantity
            ]);
        }

        return response()->json([
            'message' => 'Test item added to cart',
            'cart_item' => $cartItem->load('product'),
            'cart' => $cart->load('cartItems.product')
        ]);
    }
}
