<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\ManagementController;
use App\Http\Controllers\ManagementProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Tambahan controller auth
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;

Route::get('/', [dashboardController::class, 'fetch'])->name('dashboard');

Route::get('/dashboard', function () {
    return view('pages.dashboard.app');
})->middleware(['auth', 'verified']);

Route::middleware(['auth', 'role:customer'])->group(function () {
    // Halaman daftar keranjang
    Route::get('/cart', [CartController::class, 'list'])->name('cart.list');
    
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    
    // Payment routes
    Route::get('/payment/success', [CheckoutController::class, 'paymentSuccess'])->name('payment.success');
    Route::get('/payment/failed', [CheckoutController::class, 'paymentFailed'])->name('payment.failed');

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

require __DIR__.'/auth.php';
