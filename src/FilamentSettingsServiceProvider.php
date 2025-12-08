<?php

namespace Lightworx\FilamentSettings;

use Illuminate\Support\ServiceProvider;

class FilamentSettingsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/Config/filament-settings.php', 'filament-settings');
        $this->publishes([
            __DIR__ . '/Config/filament-settings.php' => config_path('filament-settings.php'),
        ], 'config');
        $this->loadRoutesFrom(__DIR__.'/Http/routes.php');
        $this->loadMigrationsFrom(__DIR__.'/Database/migrations');
        $this->loadViewsFrom(__DIR__.'/Resources/views', 'filament-settings');
        if (file_exists($file = __DIR__ . '/helpers.php')) {
            require_once $file;
        }
    }
}
