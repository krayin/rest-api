<?php

namespace Webkul\RestApi\Tests\Fixtures;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\ServiceProvider;

/**
 * Stands in for `Webkul\Admin\AdminServiceProvider`, which rebinds
 * `ExceptionHandler::class` in its own register()/boot() so its error views
 * work. Registered after `RestApiServiceProvider` in the test harness to
 * reproduce the real-world provider ordering that clobbered our binding.
 */
class CompetingExceptionHandlerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(ExceptionHandler::class, ThirdPartyHandler::class);
    }

    public function boot()
    {
        $this->app->singleton(ExceptionHandler::class, ThirdPartyHandler::class);
    }
}
