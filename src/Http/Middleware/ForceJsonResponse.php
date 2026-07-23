<?php

namespace Webkul\RestApi\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForceJsonResponse
{
    /**
     * Force every request handled by the REST API to negotiate a JSON response.
     *
     * Without this, a client that omits the `Accept: application/json` header
     * makes `$request->expectsJson()` return false, so validation errors and
     * exceptions render as HTML error pages instead of JSON. Overriding the
     * Accept header up front guarantees a consistent JSON contract.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $request->headers->set('Accept', 'application/json');

        return $next($request);
    }
}
