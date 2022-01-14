<?php

use Illuminate\Support\Facades\Route;
use Webkul\RestApi\Http\Controllers\V1\Quote\QuoteController;

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('quotes', [QuoteController::class, 'index']);

    Route::get('quotes/{id}', [QuoteController::class, 'show']);

    Route::post('quotes', [QuoteController::class, 'store']);

    Route::put('quotes/{id}', [QuoteController::class, 'update']);

    Route::delete('quotes/{id}', [QuoteController::class, 'destroy']);

    Route::post('quotes/mass-destroy', [QuoteController::class, 'massDestroy']);
});
