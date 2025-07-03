<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
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
    route::put('/{id}', [ProductController::class, 'update']);
});
