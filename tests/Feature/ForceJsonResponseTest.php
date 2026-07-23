<?php

namespace Webkul\RestApi\Tests\Feature;

use Illuminate\Support\Facades\Validator;
use Webkul\RestApi\Http\Middleware\ForceJsonResponse;
use Webkul\RestApi\Tests\TestCase;

/**
 * Covers the Phase 0 infrastructure: the ForceJsonResponse middleware and the
 * validation / not-found branches added to the exception handler. Together they
 * guarantee that API consumers always receive JSON (never an HTML error page)
 * with the correct status code, even when they forget the Accept header.
 */
class ForceJsonResponseTest extends TestCase
{
    protected function defineRoutes($router): void
    {
        $router->middleware(['api', ForceJsonResponse::class])->group(function ($router) {
            $router->get('/force/validation', function () {
                Validator::make([], ['name' => 'required'])->validate();
            });

            $router->get('/force/not-found', fn () => abort(404));

            $router->get('/force/ok', fn () => response()->json(['ok' => true]));
        });
    }

    public function test_validation_error_renders_json_422_without_accept_header(): void
    {
        // Plain get() sends no Accept header; the middleware must still force JSON.
        $this->get('/force/validation')
            ->assertStatus(422)
            ->assertHeader('content-type', 'application/json')
            ->assertJsonStructure(['message', 'errors' => ['name']]);
    }

    public function test_not_found_renders_json_404_without_accept_header(): void
    {
        $this->get('/force/not-found')
            ->assertStatus(404)
            ->assertHeader('content-type', 'application/json')
            ->assertJsonStructure(['message']);
    }

    public function test_successful_response_stays_json(): void
    {
        $this->get('/force/ok')
            ->assertStatus(200)
            ->assertJson(['ok' => true]);
    }
}
