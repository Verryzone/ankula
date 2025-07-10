<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Display user's orders
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if (!$user || $user->role !== 'customer') {
            return redirect()->route('login');
        }

        // Get filter parameters
        $status = $request->get('status', 'all');
        $search = $request->get('search');
        $perPage = 10;

        // Build query
        $query = Order::where('user_id', $user->id)
                     ->with(['orderItems.product', 'payment', 'shippingAddress'])
                     ->orderBy('created_at', 'desc');

        // Apply filters
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('orderItems.product', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->paginate($perPage);

        // Get order statistics
        $stats = [
            'total' => Order::where('user_id', $user->id)->count(),
            'pending' => Order::where('user_id', $user->id)->where('status', 'pending')->count(),
            'processing' => Order::where('user_id', $user->id)->where('status', 'processing')->count(),
            'completed' => Order::where('user_id', $user->id)->where('status', 'completed')->count(),
            'cancelled' => Order::where('user_id', $user->id)->where('status', 'cancelled')->count(),
        ];

        return view('pages.orders.index', compact('orders', 'stats', 'status', 'search'));
    }

    /**
     * Show order details
     */
    public function show($orderNumber)
    {
        $user = Auth::user();
        
        if (!$user || $user->role !== 'customer') {
            return redirect()->route('login');
        }

        $order = Order::where('order_number', $orderNumber)
                     ->where('user_id', $user->id)
                     ->with([
                         'orderItems.product.category',
                         'payment',
                         'shippingAddress'
                     ])
                     ->first();

        if (!$order) {
            return redirect()->route('orders.index')->with('error', 'Pesanan tidak ditemukan');
        }

        return view('pages.orders.detail', compact('order'));
    }

    /**
     * Cancel order (only if pending)
     */
    public function cancel($orderNumber)
    {
        $user = Auth::user();
        
        if (!$user || $user->role !== 'customer') {
            return redirect()->route('login');
        }

        $order = Order::where('order_number', $orderNumber)
                     ->where('user_id', $user->id)
                     ->first();

        if (!$order) {
            return redirect()->route('orders.index')->with('error', 'Pesanan tidak ditemukan');
        }

        if ($order->status !== 'pending') {
            return redirect()->route('orders.show', $orderNumber)
                           ->with('error', 'Pesanan tidak dapat dibatalkan');
        }

        try {
            // Update order status
            $order->update(['status' => 'cancelled']);

            // Update payment status if exists
            if ($order->payment) {
                $order->payment->update(['status' => 'failed']);
            }

            // Restore product stock
            foreach ($order->orderItems as $item) {
                $item->product->increment('stock', $item->quantity);
            }

            Log::info('Order cancelled by user', [
                'user_id' => $user->id,
                'order_number' => $orderNumber
            ]);

            return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dibatalkan');

        } catch (\Exception $e) {
            Log::error('Failed to cancel order', [
                'user_id' => $user->id,
                'order_number' => $orderNumber,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('orders.show', $orderNumber)
                           ->with('error', 'Gagal membatalkan pesanan');
        }
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
    public function store(StoreOrderRequest $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
