<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Addresses;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

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

        // Validate stock availability
        foreach ($selectedItems as $item) {
            if ($item->quantity > $item->product->stock) {
                return redirect()->route('cart.list')
                    ->with('error', "Stock {$item->product->name} tidak mencukupi. Tersedia: {$item->product->stock}");
            }
        }

        // Calculate totals
        $subtotal = 0;
        $totalDiscount = 0;
        $totalItems = 0;

        foreach ($selectedItems as $item) {
            $itemSubtotal = $item->quantity * $item->product->price;
            $subtotal += $itemSubtotal;
            $totalItems += $item->quantity;
        }

        $shippingCost = 15000; // Fixed shipping cost
        $total = $subtotal - $totalDiscount + $shippingCost;

        // Get user addresses
        $addresses = $user->addresses()->get();
        $defaultAddress = $user->defaultAddress;

        return view('pages.cart.checkout', compact(
            'selectedItems',
            'subtotal',
            'totalDiscount',
            'shippingCost',
            'total',
            'totalItems',
            'addresses',
            'defaultAddress'
        ));
    }

    /**
     * Process the checkout and create order
     */
    public function process(Request $request)
    {
        $user = Auth::user();
        
        if (!$user || $user->role !== 'customer') {
            return redirect()->route('login');
        }

        $request->validate([
            'items' => 'required|array|min:1',
            'items.*' => 'exists:cart_items,id',
            'shipping_address_id' => 'required|exists:addresses,id',
            'payment_method' => 'required|string'
        ]);

        DB::beginTransaction();

        try {
            // Get user's cart
            $cart = Cart::where('user_id', $user->id)->first();
            
            if (!$cart) {
                return redirect()->route('cart.list')->with('error', 'Keranjang tidak ditemukan');
            }

            // Get selected cart items
            $selectedItems = $cart->cartItems()
                ->whereIn('id', $request->items)
                ->with(['product'])
                ->get();

            if ($selectedItems->isEmpty()) {
                return redirect()->route('cart.list')->with('error', 'Item yang dipilih tidak ditemukan');
            }

            // Validate stock availability again
            foreach ($selectedItems as $item) {
                if ($item->quantity > $item->product->stock) {
                    DB::rollBack();
                    return redirect()->route('checkout', ['items' => $request->items])
                        ->with('error', "Stock {$item->product->name} tidak mencukupi. Tersedia: {$item->product->stock}");
                }
            }

            // Get shipping address
            $shippingAddress = Addresses::where('id', $request->shipping_address_id)
                ->where('user_id', $user->id)
                ->first();

            if (!$shippingAddress) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Alamat pengiriman tidak valid');
            }

            // Calculate totals
            $subtotal = 0;
            foreach ($selectedItems as $item) {
                $subtotal += $item->quantity * $item->product->price;
            }

            $shippingCost = 15000;
            $totalAmount = $subtotal + $shippingCost;

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'shipping_address_id' => $shippingAddress->id,
                'order_number' => Order::generateOrderNumber(),
                'total_amount' => $subtotal,
                'shipping_cost' => $shippingCost,
                'status' => 'pending',
                'shipping_address_snapshot' => [
                    'recipient_name' => $shippingAddress->recipient_name,
                    'phone_number' => $shippingAddress->phone_number,
                    'address_line' => $shippingAddress->address_line,
                    'city' => $shippingAddress->city,
                    'province' => $shippingAddress->province,
                    'postal_code' => $shippingAddress->postal_code,
                ]
            ]);

            // Create order items and update stock
            foreach ($selectedItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);

                // Update product stock
                $item->product->decrement('stock', $item->quantity);
            }

            // Create payment with Midtrans
            $paymentResult = $this->midtransService->createTransaction($order);

            if (!$paymentResult['success']) {
                DB::rollBack();
                return redirect()->back()->with('error', $paymentResult['message']);
            }

            // Remove items from cart
            $selectedItems->each->delete();

            DB::commit();

            // Redirect to payment page
            return view('pages.payment.process', [
                'order' => $order,
                'snapToken' => $paymentResult['snap_token'],
                'payment' => $paymentResult['payment']
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout process failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Handle Midtrans notification
     */
    public function handleNotification(Request $request)
    {
        $notification = $request->all();
        
        Log::info('Midtrans notification received', $notification);

        $result = $this->midtransService->handleNotification($notification);

        if ($result['success']) {
            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'error', 'message' => $result['message']], 400);
        }
    }

    /**
     * Payment success page
     */
    public function paymentSuccess(Request $request)
    {
        $orderId = $request->get('order_id');
        $transactionStatus = $request->get('transaction_status');

        $order = Order::where('order_number', $orderId)->first();

        if (!$order) {
            return redirect()->route('dashboard')->with('error', 'Pesanan tidak ditemukan');
        }

        return view('pages.payment.success', compact('order', 'transactionStatus'));
    }

    /**
     * Payment failed page
     */
    public function paymentFailed(Request $request)
    {
        $orderId = $request->get('order_id');
        
        $order = Order::where('order_number', $orderId)->first();

        return view('pages.payment.failed', compact('order'));
    }
}
