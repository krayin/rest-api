<?php

namespace Webkul\RestApi\Tests\Feature;

use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;
use ReflectionClass;
use Webkul\RestApi\Tests\TestCase;

/**
 * Guards against a guest hitting an `auth:sanctum` API route crashing with a
 * raw 500 ("Route [login] not defined") instead of a clean 401 JSON.
 *
 * Laravel's Authenticate middleware calls Authenticate::redirectTo(), which
 * only returns something when a callback is registered via
 * Authenticate::redirectUsing() — and resolves it eagerly while building the
 * AuthenticationException, before our own exception handler ever runs. Krayin's
 * admin panel has no named `login` route reachable from the API guard, so any
 * callback that unconditionally calls route('login') blows up with
 * RouteNotFoundException for API requests. RestApiServiceProvider registers a
 * callback that always returns null for api/* paths (and degrades to null,
 * instead of throwing, when no `login` route exists at all), independent of
 * whatever Request::expectsJson() happens to evaluate to at that point.
 */
class GuestAuthRedirectTest extends TestCase
{
    protected function defineRoutes($router): void
    {
        $router->middleware(['api', 'auth:sanctum'])->get('/test-guarded', function () {
            return response()->json(['ok' => true]);
        });
    }

    private function redirectCallback(): \Closure
    {
        $property = (new ReflectionClass(Authenticate::class))->getProperty('redirectToCallback');
        $property->setAccessible(true);

        $callback = $property->getValue();

        $this->assertNotNull($callback, 'RestApiServiceProvider must register Authenticate::redirectUsing().');

        return $callback;
    }

    public function test_api_requests_never_attempt_a_login_redirect(): void
    {
        $callback = $this->redirectCallback();

        $this->assertNull($callback(Request::create('/api/v1/leads')));
    }

    public function test_non_api_requests_degrade_to_null_when_no_login_route_exists(): void
    {
        $callback = $this->redirectCallback();

        // No `login` route is registered in this package-only test harness,
        // so the callback must degrade to null instead of throwing
        // RouteNotFoundException -- the same failure mode being guarded
        // against for api/* paths.
        $this->assertNull($callback(Request::create('/admin/dashboard')));
    }

    public function test_unauthenticated_request_to_a_sanctum_protected_route_returns_401_json(): void
    {
        $this->getJson('/test-guarded')
            ->assertStatus(401)
            ->assertJsonStructure(['message']);
    }
}
