<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ManagementController;
use App\Http\Controllers\ManagementProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/cart', [CartController::class, 'list'])->name('cart.list');
});

Route::prefix('management')->middleware(['auth', 'verified'])->group(function () {
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
    
    Route::get('/profile', [ManagementProfileController::class, 'edit'])->name('management.profile.edit');
    Route::patch('/profile', [ManagementProfileController::class, 'update'])->name('management.profile.update');
    Route::delete('/profile', [ManagementProfileController::class, 'destroy'])->name('management.profile.destroy');
});

require __DIR__.'/auth.php';
