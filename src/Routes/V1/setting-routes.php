<?php

use Illuminate\Support\Facades\Route;
use Webkul\RestApi\Http\Controllers\V1\Setting\AttributeController;
use Webkul\RestApi\Http\Controllers\V1\Setting\EmailTemplateController;
use Webkul\RestApi\Http\Controllers\V1\Setting\GroupController;
use Webkul\RestApi\Http\Controllers\V1\Setting\PipelineController;
use Webkul\RestApi\Http\Controllers\V1\Setting\RoleController;
use Webkul\RestApi\Http\Controllers\V1\Setting\SourceController;
use Webkul\RestApi\Http\Controllers\V1\Setting\TagController;
use Webkul\RestApi\Http\Controllers\V1\Setting\TypeController;
use Webkul\RestApi\Http\Controllers\V1\Setting\UserController;
use Webkul\RestApi\Http\Controllers\V1\Setting\WorkflowController;

Route::group([
    'prefix'     => 'settings',
    'middleware' => 'auth:sanctum',
], function () {
    /**
     * Group routes.
     */
    Route::get('groups', [GroupController::class, 'index']);

    Route::get('groups/{id}', [GroupController::class, 'show']);

    Route::post('groups', [GroupController::class, 'store']);

    Route::put('groups/{id}', [GroupController::class, 'update']);

    Route::delete('groups/{id}', [GroupController::class, 'destroy']);

    /**
     * Role routes.
     */
    Route::get('roles', [RoleController::class, 'index']);

    Route::get('roles/{id}', [RoleController::class, 'show']);

    Route::post('roles', [RoleController::class, 'store']);

    Route::put('roles/{id}', [RoleController::class, 'update']);

    Route::delete('roles/{id}', [RoleController::class, 'destroy']);

    /**
     * User routes.
     */
    Route::get('users', [UserController::class, 'index']);

    Route::get('users/{id}', [UserController::class, 'show']);

    Route::post('users', [UserController::class, 'store']);

    Route::put('users/{id}', [UserController::class, 'update']);

    Route::delete('users/{id}', [UserController::class, 'destroy']);

    Route::post('users/mass-update', [UserController::class, 'massUpdate']);

    Route::post('users/mass-destroy', [UserController::class, 'massDestroy']);

    /**
     * Lead pipeline routes.
     */
    Route::get('pipelines', [PipelineController::class, 'index']);

    Route::get('pipelines/{id}', [PipelineController::class, 'show']);

    Route::post('pipelines', [PipelineController::class, 'store']);

    Route::put('pipelines/{id}', [PipelineController::class, 'update']);

    Route::delete('pipelines/{id}', [PipelineController::class, 'destroy']);

    /**
     * Lead source routes.
     */
    Route::get('sources', [SourceController::class, 'index']);

    Route::get('sources/{id}', [SourceController::class, 'show']);

    Route::post('sources', [SourceController::class, 'store']);

    Route::put('sources/{id}', [SourceController::class, 'update']);

    Route::delete('sources/{id}', [SourceController::class, 'destroy']);

    /**
     * Lead type routes.
     */
    Route::get('types', [TypeController::class, 'index']);

    Route::get('types/{id}', [TypeController::class, 'show']);

    Route::post('types', [TypeController::class, 'store']);

    Route::put('types/{id}', [TypeController::class, 'update']);

    Route::delete('types/{id}', [TypeController::class, 'destroy']);

    /**
     * Attribute routes.
     */
    Route::get('attributes', [AttributeController::class, 'index']);

    Route::get('attributes/{id}', [AttributeController::class, 'show']);

    Route::get('attributes/lookup/{lookup?}', [AttributeController::class, 'lookup']);

    Route::post('attributes', [AttributeController::class, 'store']);

    Route::put('attributes/{id}', [AttributeController::class, 'update']);

    Route::delete('attributes/{id}', [AttributeController::class, 'destroy']);

    Route::post('attributes/mass-destroy', [AttributeController::class, 'massDestroy']);

    /**
     * Email template routes.
     */
    Route::get('email-templates', [EmailTemplateController::class, 'index']);

    Route::get('email-templates/{id}', [EmailTemplateController::class, 'show']);

    Route::post('email-templates', [EmailTemplateController::class, 'store']);

    Route::put('email-templates/{id}', [EmailTemplateController::class, 'update']);

    Route::delete('email-templates/{id}', [EmailTemplateController::class, 'destroy']);

    /**
     * Workflow routes.
     */
    Route::get('workflows', [WorkflowController::class, 'index']);

    Route::get('workflows/{id}', [WorkflowController::class, 'show']);

    Route::post('workflows', [WorkflowController::class, 'store']);

    Route::put('workflows/{id}', [WorkflowController::class, 'update']);

    Route::delete('workflows/{id}', [WorkflowController::class, 'destroy']);

    /**
     * Tag routes.
     */
    Route::get('tags', [TagController::class, 'index']);

    Route::get('tags/{id}', [TagController::class, 'show']);

    Route::post('tags', [TagController::class, 'store']);

    Route::put('tags/{id}', [TagController::class, 'update']);

    Route::delete('tags/{id}', [TagController::class, 'destroy']);

    Route::post('tags/mass-destroy', [TagController::class, 'massDestroy']);
});
