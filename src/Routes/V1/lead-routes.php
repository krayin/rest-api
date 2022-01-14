<?php

use Illuminate\Support\Facades\Route;
use Webkul\RestApi\Http\Controllers\V1\Lead\LeadController;
use Webkul\RestApi\Http\Controllers\V1\Lead\QuoteController;
use Webkul\RestApi\Http\Controllers\V1\Lead\TagController;

Route::group(['middleware' => 'auth:sanctum'], function () {
    /**
     * Leads.
     */
    Route::get('leads', [LeadController::class, 'index']);

    Route::get('leads/{id}', [LeadController::class, 'show']);

    Route::post('leads', [LeadController::class, 'store']);

    Route::put('leads/{id}', [LeadController::class, 'update']);

    Route::delete('leads/{id}', [LeadController::class, 'destroy']);

    Route::post('leads/mass-update', [LeadController::class, 'massUpdate']);

    Route::post('leads/mass-destroy', [LeadController::class, 'massDestroy']);

    /**
     * Tags.
     */
    Route::post('leads/{lead_id}/tags', [TagController::class, 'store']);

    Route::delete('leads/{lead_id}/tags', [TagController::class, 'delete']);

    /**
     * Quotes.
     */
    Route::post('leads/{lead_id}/quotes', [QuoteController::class, 'store']);

    Route::delete('leads/{lead_id}/quotes', [QuoteController::class, 'delete']);
});
