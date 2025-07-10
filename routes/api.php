<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AddressController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('category')->group(function () {
    Route::get('/{id}', [CategoryController::class, 'edit']);
    route::put('/{id}', [CategoryController::class, 'update']);
});

Route::prefix('product')->group(function () {
    Route::get('/{id}', [ProductController::class, 'edit']);
    Route::get('/{id}/detail', [ProductController::class, 'show']);
    route::post('/{id}', [ProductController::class, 'update']);
});

// Cart routes - requires authentication and session
Route::prefix('cart')
    ->middleware(['web', 'auth', 'role:customer'])
    ->group(function () {
        Route::post('/add', [CartController::class, 'addToCart'])->name('api.cart.add');
        Route::get('/', [CartController::class, 'getCart'])->name('api.cart.get');
        Route::put('/update/{id}', [CartController::class, 'updateCartItem'])->name('api.cart.update');
        Route::delete('/remove/{id}', [CartController::class, 'removeCartItem'])->name('api.cart.remove');
    });

// Address routes - requires authentication and session
Route::prefix('addresses')
    ->middleware(['web', 'auth', 'role:customer'])
    ->group(function () {
        Route::post('/', [AddressController::class, 'store'])->name('api.addresses.store');
        Route::get('/', [AddressController::class, 'index'])->name('api.addresses.index');
        Route::put('/{id}', [AddressController::class, 'update'])->name('api.addresses.update');
        Route::delete('/{id}', [AddressController::class, 'destroy'])->name('api.addresses.destroy');
    });
