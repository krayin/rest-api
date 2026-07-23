<?php

namespace Webkul\RestApi\Tests\Feature;

use Illuminate\Routing\Route;
use Webkul\RestApi\Tests\TestCase;

/**
 * Guards the routing layer: every endpoint the package publishes must register
 * with the expected verb, URI, and middleware. This is the cheapest, broadest
 * regression net for the Laravel 12 migration — it loads all route files without
 * dispatching to (or reflecting on) the Krayin-dependent controllers.
 */
class RouteRegistrationTest extends TestCase
{
    private function route(string $method, string $uri): ?Route
    {
        foreach ($this->app['router']->getRoutes() as $route) {
            if ($route->uri() === $uri && in_array($method, $route->methods(), true)) {
                return $route;
            }
        }

        return null;
    }

    private function assertRoute(string $method, string $uri, array $expectedMiddleware): void
    {
        $route = $this->route($method, $uri);

        $this->assertNotNull($route, "Route [$method $uri] is not registered.");

        // Use middleware() (route + group middleware) rather than gatherMiddleware(),
        // which would reflect the controller and pull in uninstalled Krayin classes.
        foreach ($expectedMiddleware as $middleware) {
            $this->assertContains(
                $middleware,
                $route->middleware(),
                "Route [$method $uri] is missing middleware [$middleware]."
            );
        }
    }

    public function test_public_auth_routes_are_registered_without_sanctum(): void
    {
        $this->assertRoute('POST', 'api/v1/login', ['api']);
        $this->assertRoute('POST', 'api/v1/forgot-password', ['api']);

        // Login must NOT sit behind auth:sanctum, or nobody could ever obtain a token.
        $this->assertNotContains('auth:sanctum', $this->route('POST', 'api/v1/login')->middleware());
    }

    public function test_authenticated_account_routes_are_guarded(): void
    {
        $this->assertRoute('DELETE', 'api/v1/logout', ['api', 'auth:sanctum']);
        $this->assertRoute('GET', 'api/v1/get', ['api', 'auth:sanctum']);
        $this->assertRoute('PUT', 'api/v1/update', ['api', 'auth:sanctum']);
    }

    public function test_lead_crud_routes_are_registered_and_guarded(): void
    {
        $this->assertRoute('GET', 'api/v1/leads', ['api', 'auth:sanctum']);
        $this->assertRoute('POST', 'api/v1/leads', ['api', 'auth:sanctum']);
        $this->assertRoute('GET', 'api/v1/leads/{id}', ['api', 'auth:sanctum']);
        $this->assertRoute('PUT', 'api/v1/leads/{id}', ['api', 'auth:sanctum']);
        $this->assertRoute('DELETE', 'api/v1/leads/{id}', ['api', 'auth:sanctum']);
        $this->assertRoute('POST', 'api/v1/leads/mass-update', ['api', 'auth:sanctum']);
        $this->assertRoute('POST', 'api/v1/leads/mass-destroy', ['api', 'auth:sanctum']);
    }

    public function test_representative_domain_routes_exist(): void
    {
        // One route per top-level resource group, proving every route file loaded.
        $this->assertRoute('POST', 'api/v1/contacts/persons', ['api', 'auth:sanctum']);
        $this->assertRoute('POST', 'api/v1/products', ['api', 'auth:sanctum']);
        $this->assertRoute('POST', 'api/v1/quotes', ['api', 'auth:sanctum']);
        $this->assertRoute('POST', 'api/v1/mails', ['api', 'auth:sanctum']);
        $this->assertRoute('PUT', 'api/v1/activities/{id}', ['api', 'auth:sanctum']);
        $this->assertRoute('POST', 'api/v1/settings/users', ['api', 'auth:sanctum']);
    }

    public function test_every_api_route_is_namespaced_under_api_v1(): void
    {
        $apiRoutes = collect($this->app['router']->getRoutes())
            ->filter(fn (Route $r) => str_starts_with($r->uri(), 'api/'));

        $this->assertNotEmpty($apiRoutes);

        $apiRoutes->each(function (Route $r) {
            $this->assertStringStartsWith(
                'api/v1/',
                $r->uri(),
                "Route [{$r->uri()}] escaped the api/v1 prefix."
            );
        });
    }

    public function test_named_mail_tag_route_keeps_its_name(): void
    {
        $route = $this->route('POST', 'api/v1/mails/{id}/tags');

        $this->assertNotNull($route);
        $this->assertSame('admin.mail.tags.attach', $route->getName());
    }
}
