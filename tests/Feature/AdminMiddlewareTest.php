<?php

namespace Webkul\RestApi\Tests\Feature;

use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Sanctum;
use Webkul\RestApi\Tests\Fixtures\User;
use Webkul\RestApi\Tests\TestCase;

/**
 * Exercises the `sanctum.admin` middleware end-to-end through a throwaway route,
 * covering the token-based authentication branch (the API path) and the
 * unauthenticated rejection. The middleware grants access only to tokens that
 * carry the `role:admin` ability.
 */
class AdminMiddlewareTest extends TestCase
{
    protected function defineEnvironment($app): void
    {
        parent::defineEnvironment($app);

        // Make `localhost` a stateful (first-party) domain so a request carrying
        // a matching Referer exercises the middleware's session branch.
        $app['config']->set('sanctum.stateful', ['localhost']);
    }

    protected function defineRoutes($router): void
    {
        $router->middleware(['api', 'sanctum.admin'])->get('/test-admin', function () {
            return response()->json(['ok' => true]);
        });
    }

    public function test_token_with_admin_ability_is_allowed(): void
    {
        Sanctum::actingAs(new User(['id' => 1]), ['role:admin']);

        $this->getJson('/test-admin')
            ->assertOk()
            ->assertJson(['ok' => true]);
    }

    public function test_token_without_admin_ability_is_rejected(): void
    {
        Sanctum::actingAs(new User(['id' => 1]), ['role:agent']);

        $this->getJson('/test-admin')->assertStatus(401);
    }

    public function test_unauthenticated_request_is_rejected(): void
    {
        $this->getJson('/test-admin')->assertStatus(401);
    }

    public function test_first_party_session_request_without_admin_user_is_rejected(): void
    {
        // A first-party (Referer matches a stateful domain) request with no
        // authenticated admin session must be rejected via the session branch.
        $this->getJson('/test-admin', ['Referer' => 'http://localhost'])
            ->assertStatus(401);
    }

    public function test_first_party_session_request_with_admin_user_is_allowed(): void
    {
        $this->actingAs(new User(['id' => 1]), 'admin');

        $this->getJson('/test-admin', ['Referer' => 'http://localhost'])
            ->assertOk()
            ->assertJson(['ok' => true]);
    }
}
