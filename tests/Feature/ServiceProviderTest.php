<?php

namespace Webkul\RestApi\Tests\Feature;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Webkul\RestApi\Exceptions\Handler;
use Webkul\RestApi\Http\Middleware\AdminMiddleware;
use Webkul\RestApi\Tests\TestCase;

class ServiceProviderTest extends TestCase
{
    public function test_the_package_provider_boots(): void
    {
        $this->assertTrue(
            $this->app->providerIsLoaded(\Webkul\RestApi\Providers\RestApiServiceProvider::class)
        );
    }

    public function test_it_registers_the_sanctum_admin_middleware_alias(): void
    {
        $aliases = $this->app['router']->getMiddleware();

        $this->assertArrayHasKey('sanctum.admin', $aliases);
        $this->assertSame(AdminMiddleware::class, $aliases['sanctum.admin']);
    }

    public function test_it_binds_the_custom_exception_handler(): void
    {
        $this->assertInstanceOf(Handler::class, $this->app->make(ExceptionHandler::class));
    }

    public function test_it_loads_the_package_translations(): void
    {
        // A key that exists in the package's lang files must resolve to a string
        // (not echo the key back), proving the `rest-api` namespace is registered.
        $translated = trans('rest-api::app.common.unauthenticated');

        $this->assertIsString($translated);
        $this->assertNotSame('rest-api::app.common.unauthenticated', $translated);
    }

    public function test_it_registers_the_install_command(): void
    {
        $this->assertArrayHasKey('krayin-rest-api:install', $this->app[\Illuminate\Contracts\Console\Kernel::class]->all());
    }
}
