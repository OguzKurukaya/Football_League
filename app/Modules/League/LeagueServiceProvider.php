<?php

namespace Leaguesim\League;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class LeagueServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function boot(): void
    {

        $router = $this->app->make(Router::class);
        $router->prefix('api')->group(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->commands([]);
        }
        /**
         * Automatically added files
         */
        // #do_not_touch#
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        //Facades
    }
}
