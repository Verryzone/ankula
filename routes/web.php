<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\ManagementController;
use App\Http\Controllers\ManagementOrderController;
use App\Http\Controllers\ManagementProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Tambahan controller auth
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;

Route::get('/', [dashboardController::class, 'fetch'])->name('dashboard');

Route::get('/dashboard', [dashboardController::class, 'fetch'])->middleware(['auth', 'verified'])->name('user.dashboard');

Route::middleware(['auth', 'role:customer'])->group(function () {
    // Halaman daftar keranjang
    Route::get('/cart', [CartController::class, 'list'])->name('cart.list');
    
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    
    // Payment routes
    Route::get('/payment/success', [CheckoutController::class, 'paymentSuccess'])->name('payment.success');
    Route::get('/payment/failed', [CheckoutController::class, 'paymentFailed'])->name('payment.failed');
    Route::get('/payment/retry/{id}', [CheckoutController::class, 'retryPayment'])->name('payment.retry');
    Route::post('/payment/check-status/{order}', [CheckoutController::class, 'manualCheckStatus'])->name('payment.check-status');

    // Order routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Midtrans notification handler (no auth needed)
Route::post('/payment/notification', [CheckoutController::class, 'handleNotification'])->name('payment.notification');

Route::prefix('management')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', [ManagementController::class, 'index'])->name('management.index');

    // Product routes
    Route::get('/product', [ProductController::class, 'list'])->name('management.product.list');
    Route::post('/product', [ProductController::class, 'add'])->name('management.product.add');
    Route::patch('/product', [ProductController::class, 'update'])->name('management.product.update');
    Route::delete('/product', [ProductController::class, 'destroy'])->name('management.product.destroy');

    // Category routes
    Route::get('/category', [CategoryController::class, 'list'])->name('management.category.list');
    Route::post('/category', [CategoryController::class, 'add'])->name('management.category.add');
    Route::delete('/category', [CategoryController::class, 'destroy'])->name('management.category.destroy');

    // Highlight routes
    Route::get('/highlight', [\App\Http\Controllers\ManagementHighlightController::class, 'index'])->name('management.highlight.index');
    Route::get('/highlight/create', [\App\Http\Controllers\ManagementHighlightController::class, 'create'])->name('management.highlight.create');
    Route::post('/highlight', [\App\Http\Controllers\ManagementHighlightController::class, 'store'])->name('management.highlight.store');
    Route::get('/highlight/{highlight}/edit', [\App\Http\Controllers\ManagementHighlightController::class, 'edit'])->name('management.highlight.edit');
    Route::put('/highlight/{highlight}', [\App\Http\Controllers\ManagementHighlightController::class, 'update'])->name('management.highlight.update');
    Route::delete('/highlight/{highlight}', [\App\Http\Controllers\ManagementHighlightController::class, 'destroy'])->name('management.highlight.destroy');
    Route::post('/highlight/{highlight}/toggle-active', [\App\Http\Controllers\ManagementHighlightController::class, 'toggleActive'])->name('management.highlight.toggle-active');
    Route::post('/highlight/update-order', [\App\Http\Controllers\ManagementHighlightController::class, 'updateOrder'])->name('management.highlight.update-order');

    // Content routes
    Route::get('/content/create', [\App\Http\Controllers\ManagementHighlightController::class, 'contentCreate'])->name('management.content.create');
    Route::post('/content', [\App\Http\Controllers\ManagementHighlightController::class, 'contentStore'])->name('management.content.store');
    Route::get('/content/{content}/edit', [\App\Http\Controllers\ManagementHighlightController::class, 'contentEdit'])->name('management.content.edit');
    Route::put('/content/{content}', [\App\Http\Controllers\ManagementHighlightController::class, 'contentUpdate'])->name('management.content.update');
    Route::delete('/content/{content}', [\App\Http\Controllers\ManagementHighlightController::class, 'contentDestroy'])->name('management.content.destroy');
    Route::post('/content/{content}/toggle-active', [\App\Http\Controllers\ManagementHighlightController::class, 'contentToggleActive'])->name('management.content.toggle-active');
    Route::post('/content/update-order', [\App\Http\Controllers\ManagementHighlightController::class, 'contentUpdateOrder'])->name('management.content.update-order');

    // Order management routes
    Route::get('/orders', [ManagementOrderController::class, 'index'])->name('management.orders.index');
    Route::get('/orders/{id}', [ManagementOrderController::class, 'show'])->name('management.orders.show');
    Route::patch('/orders/{id}/status', [ManagementOrderController::class, 'updateStatus'])->name('management.orders.update-status');
    Route::post('/orders/bulk-update', [ManagementOrderController::class, 'bulkUpdateStatus'])->name('management.orders.bulk-update');

    // Admin profile
    Route::get('/profile', [ManagementProfileController::class, 'edit'])->name('management.profile.edit');
    Route::patch('/profile', [ManagementProfileController::class, 'update'])->name('management.profile.update');
    Route::delete('/profile', [ManagementProfileController::class, 'destroy'])->name('management.profile.destroy');
});

// Route login & register eksplisit
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

Route::get('/test-midtrans-config', function() {
    return [
        'server_key' => config('midtrans.serverKey'),
        'client_key' => config('midtrans.clientKey'),
        'is_production' => config('midtrans.isProduction'),
        'env_server_key' => env('MIDTRANS_SERVER_KEY'),
        'env_client_key' => env('MIDTRANS_CLIENT_KEY'),
    ];
})->name('test.midtrans.config');

require __DIR__.'/auth.php';

// Debug routes - Only for development
if (app()->environment('local')) {
    require __DIR__.'/debug.php';
    
    // Debugging payment status
    Route::get('/debug/payment/{orderId}', [CheckoutController::class, 'checkPaymentStatus'])->name('debug.payment.status');
    
    // Test webhook form
    Route::get('/test-webhook', function() {
        return view('test-webhook');
    });
    
    // Manual webhook test
    Route::get('/test-webhook-manual/{orderId}/{status}', function($orderId, $status) {
        $notification = [
            'order_id' => $orderId,
            'transaction_status' => $status,
            'fraud_status' => 'accept',
            'payment_type' => 'bank_transfer',
            'transaction_time' => date('Y-m-d H:i:s')
        ];
        
        $checkoutController = new \App\Http\Controllers\CheckoutController(new \App\Services\MidtransService());
        $request = new \Illuminate\Http\Request($notification);
        $result = $checkoutController->handleNotification($request);
        
        return response()->json($result);
    });
}
