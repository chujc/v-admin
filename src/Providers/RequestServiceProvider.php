<?php

namespace ChuJC\Admin\Providers;

use ChuJC\Admin\Requests\FormRequest;
use Laravel\Lumen\Http\Redirector;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;

class RequestServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(): void
    {
        if (!isLaravel()) {
            $this->app->afterResolving(ValidatesWhenResolved::class, function ($resolved) {
                $resolved->validateResolved();
            });
            $this->app->resolving(FormRequest::class, function ($request, $app) {
                $request = FormRequest::createFrom($app['request'], $request);
                $request->setContainer($app)->setRedirector($app->make(Redirector::class));
            });
        }

    }
}
