<?php

use Illuminate\Support\Facades\Route;
use Webkul\RestApi\Http\Controllers\V1\Mail\EmailController;

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('mails', [EmailController::class, 'index']);

    Route::get('mails/{id}', [EmailController::class, 'show']);

    Route::post('mails', [EmailController::class, 'store']);

    Route::put('mails/{id?}', [EmailController::class, 'update']);

    Route::delete('mails/{id?}', [EmailController::class, 'destroy']);

    Route::post('mails/mass-update', [EmailController::class, 'massUpdate']);

    Route::post('mails/mass-destroy', [EmailController::class, 'massDestroy']);

    Route::get('mails/attachment-download/{id?}', [EmailController::class, 'download']);
});
