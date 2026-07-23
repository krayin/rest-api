<?php

namespace Webkul\RestApi\Tests\Feature;

use OpenApi\Generator;
use Webkul\RestApi\Tests\TestCase;

/**
 * Smoke-tests the OpenAPI documentation, which the Laravel 12 migration rewrote
 * from doctrine docblock annotations (`@OA\...`) to PHP 8 attributes (`#[OA\...]`).
 * Scanning the whole `src/Docs` tree fails loudly on any malformed attribute —
 * exactly the class of regression flagged in the CHANGELOG.
 */
class SwaggerDocsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // The doc classes reference the APP_URL constant that l5-swagger normally
        // defines from config before generating. Define it for the standalone scan.
        if (! defined('APP_URL')) {
            define('APP_URL', 'http://localhost');
        }
    }

    public function test_openapi_spec_generates_from_php_attributes(): void
    {
        $openapi = Generator::scan([realpath(__DIR__.'/../../src/Docs')]);

        $this->assertNotNull($openapi, 'swagger-php produced no OpenAPI document.');

        // Top-level Info block must have survived the attribute conversion.
        $this->assertNotSame(Generator::UNDEFINED, $openapi->info);
        $this->assertSame('Krayin Rest API Documentation', $openapi->info->title);

        // The migration added the previously-missing sanctum_admin security scheme.
        $schemes = collect($openapi->components->securitySchemes ?? [])
            ->map(fn ($s) => $s->securityScheme);
        $this->assertTrue(
            $schemes->contains('sanctum_admin'),
            'The sanctum_admin security scheme is missing from the generated spec.'
        );
    }

    public function test_generated_spec_has_documented_paths(): void
    {
        $openapi = Generator::scan([realpath(__DIR__.'/../../src/Docs')]);

        $this->assertNotSame(Generator::UNDEFINED, $openapi->paths);
        $this->assertNotEmpty($openapi->paths, 'No API paths were generated from the doc attributes.');
    }

    public function test_generated_spec_serializes_to_valid_json(): void
    {
        $openapi = Generator::scan([realpath(__DIR__.'/../../src/Docs')]);

        $json = $openapi->toJson();

        $this->assertJson($json);
        $this->assertArrayHasKey('openapi', json_decode($json, true));
    }
}
