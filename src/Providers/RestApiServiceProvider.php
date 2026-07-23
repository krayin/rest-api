<?php

namespace Webkul\RestApi\Providers;

use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Webkul\RestApi\Exceptions\Handler;

class RestApiServiceProvider extends ServiceProvider
{
    /**
     * Register your middleware aliases here.
     *
     * @var array
     */
    protected $middlewareAliases = [
        'sanctum.admin' => \Webkul\RestApi\Http\Middleware\AdminMiddleware::class,
    ];

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->activateMiddlewareAliases();

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'rest-api');

        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        $this->publishes([
            __DIR__.'/../Config/l5-swagger.php' => config_path('l5-swagger.php'),
        ], 'krayin-rest-api-swagger');

        $this->publishes([
            __DIR__.'/../Config/rest-api.php' => config_path('rest-api.php'),
        ], 'krayin-rest-api-config');

        $this->bindExceptionHandler();

        $this->configureGuestAuthRedirect();
    }

    /**
     * Bind our exception handler as the application's active one.
     *
     * Krayin's own `Webkul\Admin\AdminServiceProvider` also rebinds
     * `ExceptionHandler::class` (so its own error views work), and provider
     * boot order between packages isn't something we control — whichever
     * provider's `boot()` runs last wins the container binding. Deferring our
     * call to the application's `booted()` callback queue guarantees ours
     * runs strictly after every provider's `boot()` has finished, so our JSON
     * error contract for `api/*` always wins regardless of provider order.
     *
     * @return void
     */
    protected function bindExceptionHandler()
    {
        $this->app->booted(function () {
            if (! $this->app['config']->get('rest-api.override_exception_handler', true)) {
                return;
            }

            $this->app->singleton(ExceptionHandler::class, Handler::class);
        });
    }

    /**
     * Ensure an unauthenticated api/* request never falls back to a
     * `route('login')` redirect.
     *
     * Laravel's Authenticate middleware calls this callback (if one is
     * registered by any provider) to build the AuthenticationException's
     * redirect target. Krayin's admin panel has no named `login` route
     * reachable from the API guard, so resolving it throws
     * RouteNotFoundException — surfacing as a raw 500 instead of the 401 JSON
     * our own exception handler otherwise renders for AuthenticationException.
     * Scoping the null-return to api/* preserves whatever redirect behavior
     * the host app relies on for its own web/admin routes.
     *
     * @return void
     */
    protected function configureGuestAuthRedirect()
    {
        Authenticate::redirectUsing(function (Request $request) {
            if ($request->is('api/*')) {
                return null;
            }

            return Route::has('login') ? route('login') : null;
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../Config/rest-api.php', 'rest-api');

        $this->mapApiRoutes();

        $this->registerCommands();
    }

    /**
     * Define the "api" routes for the application.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware(['api', \Webkul\RestApi\Http\Middleware\ForceJsonResponse::class])
            ->group(__DIR__.'/../Routes/api.php');
    }

    /**
     * Register commands.
     *
     * @return void
     */
    public function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Webkul\RestApi\Console\Commands\Install::class,
            ]);
        }
    }

    /**
     * Activate middleware aliases.
     *
     * @return void
     */
    protected function activateMiddlewareAliases()
    {
        collect($this->middlewareAliases)->each(function ($className, $alias) {
            $this->app['router']->aliasMiddleware($alias, $className);
        });
    }
}
