<?php

namespace Webkul\RestApi\Tests\Feature;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Webkul\RestApi\Tests\TestCase;

/**
 * Locks in the JSON error contract produced by the package's custom exception
 * handler. This is the highest-risk area of the Laravel 12 migration: the
 * handler was rewritten from the removed `App\Exceptions\Handler` base to the
 * L11/12 `register()` + `renderable()` idiom. Every branch of
 * renderCustomResponse() is covered here.
 *
 * The custom rendering only runs when APP_DEBUG is false (set in phpunit.xml).
 */
class ExceptionHandlerTest extends TestCase
{
    protected function defineRoutes($router): void
    {
        $router->middleware('api')->group(function ($router) {
            $router->get('/throw/auth', fn () => throw new AuthenticationException);
            $router->get('/throw/model', fn () => throw (new ModelNotFoundException)->setModel('Lead'));
            $router->get('/throw/forbidden', fn () => abort(403));
            $router->get('/throw/authz', fn () => throw new AuthorizationException);
            $router->get('/throw/teapot', fn () => abort(418));
            $router->get('/throw/pdo', fn () => throw new \PDOException('db is down'));
        });
    }

    public function test_authentication_exception_renders_json_401(): void
    {
        $this->getJson('/throw/auth')
            ->assertStatus(401)
            ->assertJsonStructure(['message']);
    }

    public function test_model_not_found_renders_json_404(): void
    {
        $this->getJson('/throw/model')
            ->assertStatus(404)
            ->assertJsonStructure(['message']);
    }

    public function test_forbidden_http_exception_renders_json_403(): void
    {
        $this->getJson('/throw/forbidden')
            ->assertStatus(403)
            ->assertJsonStructure(['message']);
    }

    public function test_http_exception_preserves_its_real_status_code(): void
    {
        // The handler now honors the HttpException's real status code instead
        // of collapsing anything outside a small whitelist to 500, so client
        // errors like 405/429/418 reach the API consumer as themselves (and,
        // for a 429, keep their Retry-After header).
        $this->getJson('/throw/teapot')
            ->assertStatus(418)
            ->assertJsonStructure(['message']);
    }

    public function test_authorization_exception_renders_json_403(): void
    {
        // Exercises the dedicated AuthorizationException renderable (not the
        // abort(403) HttpException path covered above).
        $this->getJson('/throw/authz')
            ->assertStatus(403)
            ->assertJsonStructure(['message']);
    }

    public function test_pdo_exception_renders_json_500(): void
    {
        $this->getJson('/throw/pdo')
            ->assertStatus(500)
            ->assertJsonStructure(['message']);
    }

    public function test_error_message_is_a_resolved_string_not_a_raw_translation_key(): void
    {
        // The headline fix of the error contract is that clients never receive a
        // raw `rest-api::app.common.*` key. Assert the message resolves to the
        // localized human string (no `::`).
        $message = $this->getJson('/throw/model')->json('message');

        $this->assertIsString($message);
        $this->assertStringNotContainsString('::', $message);
        $this->assertSame(trans('rest-api::app.common.resource-not-found'), $message);
    }
}
