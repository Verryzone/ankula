<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManagementController extends Controller
{
    public function index()
    {
        // Statistics
        $totalOrders = Order::count();
        $totalRevenue = Payment::where('status', 'success')->sum('amount');
        $totalProducts = Product::count();
        $totalUsers = User::where('role', 'customer')->count();
        
        // Revenue this month vs last month
        $currentMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        
        $currentMonthRevenue = Payment::where('status', 'success')
            ->whereHas('order', function($query) use ($currentMonth) {
                $query->where('created_at', '>=', $currentMonth);
            })->sum('amount');
            
        $lastMonthRevenue = Payment::where('status', 'success')
            ->whereHas('order', function($query) use ($lastMonth, $currentMonth) {
                $query->whereBetween('created_at', [$lastMonth, $currentMonth]);
            })->sum('amount');
        
        // Orders growth
        $currentMonthOrders = Order::where('created_at', '>=', $currentMonth)->count();
        $lastMonthOrders = Order::whereBetween('created_at', [$lastMonth, $currentMonth])->count();
        
        // Recent orders
        $recentOrders = Order::with(['user', 'payment'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Top selling products
        $topProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->with('product')
            ->groupBy('product_id')
            ->orderBy('total_sold', 'desc')
            ->take(5)
            ->get();
        
        // Monthly revenue chart data (last 6 months)
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();
            
            $revenue = Payment::where('status', 'success')
                ->whereHas('order', function($query) use ($monthStart, $monthEnd) {
                    $query->whereBetween('created_at', [$monthStart, $monthEnd]);
                })->sum('amount');
                
            $monthlyRevenue[] = [
                'month' => $month->format('M Y'),
                'revenue' => $revenue
            ];
        }
        
        // Order status distribution
        $orderStatuses = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();
        
        return view('management.pages.dashboard.app', compact(
            'totalOrders',
            'totalRevenue', 
            'totalProducts',
            'totalUsers',
            'currentMonthRevenue',
            'lastMonthRevenue',
            'currentMonthOrders',
            'lastMonthOrders',
            'recentOrders',
            'topProducts',
            'monthlyRevenue',
            'orderStatuses'
        ));
    }
}
