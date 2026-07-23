<?php

namespace Webkul\RestApi\Tests\Feature;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Laravel\Sanctum\SanctumServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Webkul\RestApi\Exceptions\Handler;
use Webkul\RestApi\Providers\RestApiServiceProvider;
use Webkul\RestApi\Tests\Fixtures\CompetingExceptionHandlerServiceProvider;
use Webkul\RestApi\Tests\Fixtures\ThirdPartyHandler;

/**
 * Regression test for a real host-app bug: `Webkul\Admin\AdminServiceProvider`
 * also rebinds `ExceptionHandler::class` (for its own error views), and since
 * it is registered after this package, its binding clobbered ours whenever
 * its own boot() ran after ours — silently disabling the JSON error contract
 * for api/* routes. RestApiServiceProvider now defers its binding to the
 * application's `booted()` callback queue, which always fires after every
 * provider's boot() has completed, guaranteeing our binding wins regardless
 * of provider order. This extends the base TestCase's provider list with a
 * fixture provider registered *after* ours to reproduce that ordering.
 */
class ExceptionHandlerBindingPrecedenceTest extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            SanctumServiceProvider::class,
            RestApiServiceProvider::class,
            CompetingExceptionHandlerServiceProvider::class,
        ];
    }

    public function test_our_handler_wins_even_when_a_later_provider_rebinds_it(): void
    {
        $resolved = $this->app->make(ExceptionHandler::class);

        $this->assertInstanceOf(Handler::class, $resolved);
        $this->assertNotInstanceOf(ThirdPartyHandler::class, $resolved);
    }
}
