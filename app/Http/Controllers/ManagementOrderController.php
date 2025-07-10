<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ManagementOrderController extends Controller
{
    /**
     * Display a listing of orders for admin management
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $status = $request->get('status', 'all');
        $search = $request->get('search');
        $perPage = 15;

        // Build query
        $query = Order::with(['user', 'orderItems.product', 'payment', 'shippingAddress'])
                     ->orderBy('created_at', 'desc');

        // Apply filters
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('orderItems.product', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->paginate($perPage);

        // Get order statistics
        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        return view('management.pages.orders.list', compact('orders', 'stats', 'status', 'search'));
    }

    /**
     * Show order details for admin
     */
    public function show($id)
    {
        $order = Order::with([
                'user',
                'orderItems.product.category',
                'payment',
                'shippingAddress'
            ])
            ->findOrFail($id);

        return view('management.pages.orders.detail', compact('order'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);

        $order = Order::findOrFail($id);
        $oldStatus = $order->status;
        $newStatus = $request->status;

        try {
            // Update order status
            $order->update(['status' => $newStatus]);

            // Handle specific status changes
            if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
                // Restore product stock when cancelled
                foreach ($order->orderItems as $item) {
                    $item->product->increment('stock', $item->quantity);
                }

                // Update payment status if exists
                if ($order->payment && $order->payment->status !== 'failed') {
                    $order->payment->update(['status' => 'failed']);
                }
            }

            // If order is marked as completed, ensure payment is success
            if ($newStatus === 'completed' && $order->payment && $order->payment->status === 'pending') {
                $order->payment->update(['status' => 'success', 'paid_at' => now()]);
            }

            Log::info('Order status updated by admin', [
                'order_id' => $id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'admin_user' => Auth::user()->email
            ]);

            return redirect()->route('management.orders.show', $id)
                           ->with('success', "Status pesanan berhasil diubah dari '{$oldStatus}' ke '{$newStatus}'");

        } catch (\Exception $e) {
            Log::error('Failed to update order status', [
                'order_id' => $id,
                'error' => $e->getMessage(),
                'admin_user' => Auth::user()->email
            ]);

            return redirect()->route('management.orders.show', $id)
                           ->with('error', 'Gagal mengubah status pesanan');
        }
    }

    /**
     * Bulk update order status
     */
    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id',
            'status' => 'required|in:processing,completed,cancelled'
        ]);

        try {
            $orders = Order::whereIn('id', $request->order_ids)->get();
            $updatedCount = 0;

            foreach ($orders as $order) {
                $oldStatus = $order->status;
                
                // Only update if status is different
                if ($oldStatus !== $request->status) {
                    $order->update(['status' => $request->status]);
                    
                    // Handle cancellation logic
                    if ($request->status === 'cancelled' && $oldStatus !== 'cancelled') {
                        foreach ($order->orderItems as $item) {
                            $item->product->increment('stock', $item->quantity);
                        }
                        
                        if ($order->payment && $order->payment->status !== 'failed') {
                            $order->payment->update(['status' => 'failed']);
                        }
                    }
                    
                    // Handle completion logic
                    if ($request->status === 'completed' && $order->payment && $order->payment->status === 'pending') {
                        $order->payment->update(['status' => 'success', 'paid_at' => now()]);
                    }
                    
                    $updatedCount++;
                }
            }

            Log::info('Bulk order status update by admin', [
                'order_ids' => $request->order_ids,
                'new_status' => $request->status,
                'updated_count' => $updatedCount,
                'admin_user' => Auth::user()->email
            ]);

            return redirect()->route('management.orders.index')
                           ->with('success', "{$updatedCount} pesanan berhasil diubah statusnya ke '{$request->status}'");

        } catch (\Exception $e) {
            Log::error('Failed to bulk update order status', [
                'error' => $e->getMessage(),
                'admin_user' => Auth::user()->email
            ]);

            return redirect()->route('management.orders.index')
                           ->with('error', 'Gagal mengubah status pesanan secara bulk');
        }
    }
}
