<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page with selected items
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->role !== 'customer') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak');
        }

        // Get selected cart item IDs from query parameter
        $selectedItemIds = $request->get('items', []);
        
        if (empty($selectedItemIds)) {
            return redirect()->route('cart.list')->with('error', 'Pilih produk yang ingin dibeli');
        }

        // Get user's cart
        $cart = Cart::where('user_id', $user->id)->first();
        
        if (!$cart) {
            return redirect()->route('cart.list')->with('error', 'Keranjang tidak ditemukan');
        }

        // Get selected cart items
        $selectedItems = $cart->cartItems()
            ->whereIn('id', $selectedItemIds)
            ->with(['product.category'])
            ->get();

        if ($selectedItems->isEmpty()) {
            return redirect()->route('cart.list')->with('error', 'Item yang dipilih tidak ditemukan');
        }

        // Calculate totals
        $subtotal = 0;
        $totalDiscount = 0;
        $totalItems = 0;

        foreach ($selectedItems as $item) {
            $itemSubtotal = $item->quantity * $item->product->price;
            $subtotal += $itemSubtotal;
            $totalItems += $item->quantity;
            
            // You can add discount calculation here if you have original_price field
            // $itemDiscount = $item->quantity * ($item->product->original_price - $item->product->price);
            // $totalDiscount += $itemDiscount;
        }

        $shippingCost = 0; // Free shipping for now
        $total = $subtotal - $totalDiscount + $shippingCost;

        return view('pages.cart.checkout', compact(
            'selectedItems',
            'subtotal',
            'totalDiscount',
            'shippingCost',
            'total',
            'totalItems'
        ));
    }

    /**
     * Process the checkout (placeholder for payment processing)
     */
    public function process(Request $request)
    {
        $user = Auth::user();
        
        if (!$user || $user->role !== 'customer') {
            return redirect()->route('login');
        }

        $request->validate([
            'items' => 'required|array|min:1',
            'items.*' => 'exists:cart_items,id'
        ]);

        try {
            // Get user's cart
            $cart = Cart::where('user_id', $user->id)->first();
            
            if (!$cart) {
                return redirect()->route('cart.list')->with('error', 'Keranjang tidak ditemukan');
            }

            // Verify selected items belong to user
            $selectedItems = $cart->cartItems()
                ->whereIn('id', $request->items)
                ->with('product')
                ->get();

            if ($selectedItems->isEmpty()) {
                return redirect()->route('cart.list')->with('error', 'Item yang dipilih tidak valid');
            }

            // Check stock availability
            foreach ($selectedItems as $item) {
                if ($item->quantity > $item->product->stock) {
                    return redirect()->route('cart.list')
                        ->with('error', "Stock {$item->product->name} tidak mencukupi. Tersedia: {$item->product->stock}");
                }
            }

            // TODO: Implement actual payment processing here
            // For now, we'll simulate successful payment
            
            // Remove purchased items from cart
            foreach ($selectedItems as $item) {
                // Update product stock
                $item->product->decrement('stock', $item->quantity);
                
                // Remove item from cart
                $item->delete();
            }

            return redirect()->route('dashboard')
                ->with('success', 'Pembayaran berhasil! Pesanan Anda sedang diproses.');

        } catch (\Exception $e) {
            Log::error('Checkout process error', [
                'error' => $e->getMessage(),
                'user_id' => $user->id
            ]);
            
            return redirect()->route('checkout')
                ->with('error', 'Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.');
        }
    }
}
