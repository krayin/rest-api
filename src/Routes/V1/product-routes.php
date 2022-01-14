<?php

use Illuminate\Support\Facades\Route;
use Webkul\RestApi\Http\Controllers\V1\Product\ProductController;

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('products', [ProductController::class, 'index']);

    Route::get('products/{id}', [ProductController::class, 'show']);

    Route::get('products/search', [ProductController::class, 'search']);

    Route::post('products', [ProductController::class, 'store']);

    Route::put('products/{id}', [ProductController::class, 'update']);

    Route::delete('products/{id}', [ProductController::class, 'destroy']);

    Route::post('products/mass-destroy', [ProductController::class, 'massDestroy']);
});
