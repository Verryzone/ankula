<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Http\Requests\StoreCartRequest;
use App\Http\Requests\UpdateCartRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function list()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Log for debugging
        Log::info('Cart list accessed by user', ['user_id' => $user->id, 'user_role' => $user->role]);

        try {
            // Get user's cart with cart items and products
            $cart = Cart::with(['cartItems.product.category'])
                        ->where('user_id', $user->id)
                        ->first();

            $cartItems = collect();
            $totalPrice = 0;
            $totalOriginalPrice = 0;
            $totalItems = 0;

            if ($cart && $cart->cartItems->count() > 0) {
                $cartItems = $cart->cartItems;
                
                // Calculate totals
                foreach ($cartItems as $item) {
                    if ($item->product) {
                        $itemTotal = $item->quantity * $item->product->price;
                        $totalPrice += $itemTotal;
                        $totalOriginalPrice += $itemTotal; // You can add original_price field to products table for discounts
                        $totalItems += $item->quantity;
                    }
                }
                
                Log::info('Cart data loaded successfully', [
                    'user_id' => $user->id,
                    'cart_items_count' => $cartItems->count(),
                    'total_items' => $totalItems,
                    'total_price' => $totalPrice
                ]);
            } else {
                Log::info('Empty cart for user', ['user_id' => $user->id]);
            }
        } catch (\Exception $e) {
            Log::error('Error loading cart data', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $cartItems = collect();
            $totalPrice = 0;
            $totalOriginalPrice = 0;
            $totalItems = 0;
        }

        $totalDiscount = $totalOriginalPrice - $totalPrice;

        return view('pages.cart.list', compact(
            'cartItems', 
            'totalPrice', 
            'totalOriginalPrice', 
            'totalDiscount', 
            'totalItems'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCartRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCartRequest $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        //
    }

    /**
     * Add product to cart via API
     */
    public function addToCart(Request $request)
    {
        // Debug: Log user information
        Log::info('Cart add attempt', [
            'user_id' => Auth::id(),
            'user_role' => Auth::user()?->role,
            'is_authenticated' => Auth::check()
        ]);

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak terautentikasi'
                ], 401);
            }

            if ($user->role !== 'customer') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya customer yang dapat menambahkan ke keranjang'
                ], 403);
            }

            $productId = $request->product_id;
            $quantity = $request->quantity;

            // Check if user has an active cart
            $cart = Cart::firstOrCreate(['user_id' => $user->id]);

            // Check if product already exists in cart
            $cartItem = $cart->cartItems()->where('product_id', $productId)->first();

            if ($cartItem) {
                // Update quantity if item already exists
                $cartItem->quantity += $quantity;
                $cartItem->save();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Produk berhasil ditambahkan ke keranjang',
                    'cart_item_id' => $cartItem->id
                ]);
            } else {
                // Create new cart item
                $newCartItem = $cart->cartItems()->create([
                    'product_id' => $productId,
                    'quantity' => $quantity
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Produk berhasil ditambahkan ke keranjang',
                    'cart_item_id' => $newCartItem->id
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Cart add error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan produk ke keranjang: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's cart via API
     */
    public function getCart()
    {
        try {
            $user = Auth::user();
            $cart = Cart::with(['cartItems.product'])->where('user_id', $user->id)->first();

            if (!$cart) {
                return response()->json([
                    'success' => true,
                    'data' => ['items' => [], 'total' => 0]
                ]);
            }

            $total = 0;
            foreach ($cart->cartItems as $item) {
                $total += $item->quantity * $item->product->price;
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'items' => $cart->cartItems,
                    'total' => $total
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat keranjang'
            ], 500);
        }
    }

    /**
     * Update cart item quantity via API
     */
    public function updateCartItem(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak terautentikasi'
                ], 401);
            }

            if ($user->role !== 'customer') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya customer yang dapat mengupdate keranjang'
                ], 403);
            }

            // Find cart item that belongs to the user
            $cart = Cart::where('user_id', $user->id)->first();
            
            if (!$cart) {
                return response()->json([
                    'success' => false,
                    'message' => 'Keranjang tidak ditemukan'
                ], 404);
            }

            $cartItem = $cart->cartItems()->where('id', $id)->first();
            
            if (!$cartItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item tidak ditemukan di keranjang'
                ], 404);
            }

            $quantity = $request->quantity;
            
            // Check stock availability
            if ($quantity > $cartItem->product->stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stock tidak mencukupi. Tersedia: ' . $cartItem->product->stock
                ], 400);
            }

            $cartItem->update(['quantity' => $quantity]);

            return response()->json([
                'success' => true,
                'message' => 'Quantity berhasil diperbarui',
                'data' => $cartItem->load('product')
            ]);

        } catch (\Exception $e) {
            Log::error('Cart update error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui keranjang: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove cart item via API
     */
    public function removeCartItem($id)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak terautentikasi'
                ], 401);
            }

            if ($user->role !== 'customer') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya customer yang dapat menghapus dari keranjang'
                ], 403);
            }

            // Find cart item that belongs to the user
            $cart = Cart::where('user_id', $user->id)->first();
            
            if (!$cart) {
                return response()->json([
                    'success' => false,
                    'message' => 'Keranjang tidak ditemukan'
                ], 404);
            }

            $cartItem = $cart->cartItems()->where('id', $id)->first();
            
            if (!$cartItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item tidak ditemukan di keranjang'
                ], 404);
            }

            $cartItem->delete();

            return response()->json([
                'success' => true,
                'message' => 'Item berhasil dihapus dari keranjang'
            ]);

        } catch (\Exception $e) {
            Log::error('Cart remove error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus item dari keranjang: ' . $e->getMessage()
            ], 500);
        }
    }
}
