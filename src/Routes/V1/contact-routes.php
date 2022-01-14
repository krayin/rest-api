<?php

use Illuminate\Support\Facades\Route;
use Webkul\RestApi\Http\Controllers\V1\Contact\OrganizationController;
use Webkul\RestApi\Http\Controllers\V1\Contact\PersonController;

Route::group([
    'prefix'     => 'contacts',
    'middleware' => 'auth:sanctum',
], function () {
    /**
     * Person routes.
     */
    Route::get('persons', [PersonController::class, 'index']);

    Route::get('persons/{id}', [PersonController::class, 'show']);

    Route::get('persons/search', [PersonController::class, 'search']);

    Route::post('persons', [PersonController::class, 'store']);

    Route::put('persons/{id}', [PersonController::class, 'update']);

    Route::delete('persons/{id}', [PersonController::class, 'destroy']);

    Route::post('persons/mass-destroy', [PersonController::class, 'massDestroy']);

    /**
     * Organization routes.
     */
    Route::get('organizations', [OrganizationController::class, 'index']);

    Route::get('organizations/{id}', [OrganizationController::class, 'show']);

    Route::post('organizations', [OrganizationController::class, 'store']);

    Route::put('organizations/{id}', [OrganizationController::class, 'update']);

    Route::delete('organizations/{id}', [OrganizationController::class, 'destroy']);

    Route::post('organizations/mass-destroy', [OrganizationController::class, 'massDestroy']);
});
