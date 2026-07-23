<?php

namespace Webkul\RestApi\Tests;

use Laravel\Sanctum\SanctumServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Webkul\RestApi\Providers\RestApiServiceProvider;

abstract class TestCase extends Orchestra
{
    /**
     * Register the package (and its dependencies') service providers.
     *
     * We deliberately register only the package's own provider plus Sanctum —
     * NOT the full Krayin CRM. The controllers reference `Webkul\*` classes that
     * are not installed, but route registration only stores them as class-strings
     * and never resolves them, so everything the package owns boots cleanly.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            SanctumServiceProvider::class,
            RestApiServiceProvider::class,
        ];
    }

    /**
     * Define the environment the package boots into.
     *
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function defineEnvironment($app): void
    {
        $config = $app['config'];

        /**
         * The AdminMiddleware falls back to an `admin` guard for session-based
         * (first-party) requests. Krayin defines it; here we point it at a
         * lightweight test provider so the guard resolves without the CRM.
         */
        $config->set('auth.guards.admin', [
            'driver'   => 'session',
            'provider' => 'admins',
        ]);

        $config->set('auth.providers.admins', [
            'driver' => 'eloquent',
            'model'  => \Webkul\RestApi\Tests\Fixtures\User::class,
        ]);
    }
}
