<?php

namespace App\Providers;

use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->isLocal()) {
            $this->app->register(IdeHelperServiceProvider::class);
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Add fallback backpack view namespace for backward compatibility with published views
        if (app()->bound('view')) {
            $backpackOldPath = resource_path('views/vendor/backpack_old/ui');
            $backpackUiPath = resource_path('views/vendor/backpack/ui');
            $vendorUiPath = base_path('vendor/backpack/crud/src/resources/views/ui');

            view()->addNamespace('backpack', array_filter([
                file_exists($backpackUiPath) ? $backpackUiPath : null,
                file_exists($backpackOldPath) ? $backpackOldPath : null,
                $vendorUiPath,
            ]));
        }
    }
}