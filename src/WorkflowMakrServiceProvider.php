<?php

namespace AlvariumDigital\WorkflowMakr;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class WorkflowMakrServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->configure();
    }

    /**
     * Setup the configuration for workflowmakr
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/workflowmakr.php', 'workflowmakr'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerRoutes();
        $this->registerMigrations();
        $this->registerPublishing();
    }

    /**
     * Register the package routes
     *
     * @return void
     */
    protected function registerRoutes(): void
    {
        Route::prefix('workflowmakr')
            ->namespace('AlvariumDigital\\WorkflowMakr\\Http\\Controllers')
            ->as('workflowmakr.')
            ->middleware(config('workflowmakr.routes_middleware'))
            ->group(__DIR__ . '/routes/api.php');
    }

    /**
     * Register the package migrations
     *
     * @return void
     */
    protected function registerMigrations(): void
    {
        if (config('workflowmakr.migrations')) {
            $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        }
    }

    /**
     * Register the package's publishable resources
     *
     * @return void
     */
    protected function registerPublishing(): void
    {
        $this->publishes([
            __DIR__ . '/config/workflowmakr.php' => config_path('workflowmakr.php'),
        ]);

        $this->publishes([
            __DIR__ . '/database/migrations/' => database_path('migrations')
        ], 'migrations');
    }
}
