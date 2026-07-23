<?php

namespace Webkul\RestApi\Tests\Fixtures;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\Contracts\HasApiTokens as HasApiTokensContract;
use Laravel\Sanctum\HasApiTokens;

/**
 * Minimal Sanctum-capable user used to exercise auth-guarded package code
 * (middleware, route middleware) without pulling in the Krayin `Webkul\User`
 * model or its migrations. `Sanctum::actingAs()` uses a transient token, so
 * these instances never need to be persisted.
 */
class User extends Authenticatable implements HasApiTokensContract
{
    use HasApiTokens;

    protected $guarded = [];

    /**
     * Krayin users carry a `view_permission`; some package code reads it, so we
     * expose it as a plain attribute with a sensible default.
     */
    protected $attributes = [
        'view_permission' => 'global',
    ];
}
