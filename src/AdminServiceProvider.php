<?php

namespace ChuJC\Admin;

use ChuJC\Admin\Commands\InstallCommand;
use ChuJC\Admin\Commands\VendorPublishCommand;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{


    /**
     * @var array
     */
    protected $commands = [
        InstallCommand::class,
        VendorPublishCommand::class
    ];


    protected $routeMiddleware = [
        'admin.auth' => Middleware\Authenticate::class,
        'admin.permission' => Middleware\Permission::class,
        'admin.operationLogs' => Middleware\OperationLog::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'admin' => [
            'admin.auth',
            'admin.permission',
            'admin.operationLogs'
        ],
    ];

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {

        $this->loadAdminAuthConfig();

        if (file_exists($routes = admin_path('routes.php'))) {
            $this->loadRoutesFrom($routes);
        }

        $this->registerPublishing();
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    protected function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__ . '/../config' => config_path()], 'v-admin-config');
            $this->publishes([__DIR__ . '/../database/migrations' => database_path('migrations')], 'v-admin-migrations');
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        if (!isLaravel()) {
            $this->app->configure('admin');
        } else {
            $configPath = __DIR__ . '/../config/admin.php';
            $this->mergeConfigFrom($configPath, 'admin');
        }

        $this->registerRouteMiddleware();

        $this->commands($this->commands);

    }

    /**
     * Setup auth configuration.
     *
     * @return void
     */
    protected function loadAdminAuthConfig()
    {
        config(Arr::dot(config('admin.auth', []), 'auth.'));
    }

    /**
     * Register the route middleware.
     *
     * @return void
     */
    protected function registerRouteMiddleware()
    {
        if (isLaravel()) {
//             register route middleware.
            foreach ($this->routeMiddleware as $key => $middleware) {
                app('router')->aliasMiddleware($key, $middleware);
            }

            // register middleware group.
            foreach ($this->middlewareGroups as $key => $middleware) {
                app('router')->middlewareGroup($key, $middleware);
            }
        } else {
            $this->app->routeMiddleware($this->routeMiddleware);
        }
    }
}
