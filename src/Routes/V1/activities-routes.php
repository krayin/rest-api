<?php

use Illuminate\Support\Facades\Route;
use Webkul\RestApi\Http\Controllers\V1\Activity\ActivityController;

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::controller(ActivityController::class)->prefix('activities')->group(function () {
        Route::get('', 'index');

        Route::get('{id}', 'show')->where('id', '[0-9]+');

        Route::post('', 'store');

        Route::put('{id}', 'update');

        Route::get('file-download/{id?}', 'download');

        Route::delete('{id}', 'destroy');

        Route::post('mass-update', 'massUpdate');

        Route::match(['delete', 'post'], 'mass-destroy', 'massDestroy');
    });
});
