<?php

namespace Webkul\RestApi\Tests\Fixtures;

use Illuminate\Foundation\Exceptions\Handler as BaseHandler;

/**
 * A stand-in for a host-app exception handler (e.g. Krayin's own admin error
 * handler) that a competing provider might bind. Only used for identity
 * checks in tests, so the constructor is intentionally trivial.
 */
class ThirdPartyHandler extends BaseHandler
{
    public function __construct()
    {
        //
    }
}
