<?php

namespace Webkul\RestApi\Tests\Feature;

use Webkul\RestApi\Tests\TestCase;

/**
 * Guards the Handler's debug-mode carve-out: web/admin routes still get
 * Laravel's HTML debug page when APP_DEBUG=true (useful for developing the
 * host app), but any route under api/* must always render JSON, even for an
 * exception type the handler doesn't explicitly map, and even in debug mode.
 * An API consumer should never receive an HTML stack trace.
 */
class ApiDebugJsonHandlerTest extends TestCase
{
    protected function defineEnvironment($app): void
    {
        parent::defineEnvironment($app);

        $app['config']->set('app.debug', true);
    }

    protected function defineRoutes($router): void
    {
        $router->middleware('api')->prefix('api')->group(function ($router) {
            $router->get('/v1/boom', fn () => throw new \RuntimeException('unexpected failure'));
        });

        $router->middleware('web')->get('/admin/boom', fn () => throw new \RuntimeException('unexpected failure'));
    }

    public function test_unmapped_exception_on_api_route_renders_json_even_in_debug_mode(): void
    {
        $this->getJson('/api/v1/boom')
            ->assertStatus(500)
            ->assertHeader('content-type', 'application/json')
            ->assertJsonStructure(['message'])
            ->assertJsonMissing(['exception']);
    }

    public function test_unmapped_exception_on_non_api_route_keeps_debug_rendering(): void
    {
        $response = $this->get('/admin/boom');

        // Debug mode is preserved for non-api routes: Laravel's default
        // handler renders its own (non-JSON) debug page instead of our
        // handler's generic JSON error.
        $response->assertStatus(500);
        $this->assertStringNotContainsString(
            'application/json',
            $response->headers->get('content-type') ?? ''
        );
    }
}
