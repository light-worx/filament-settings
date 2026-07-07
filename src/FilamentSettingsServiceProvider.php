<?php

namespace Lightworx\FilamentSettings;

use Illuminate\Database\QueryException;
use Illuminate\Support\ServiceProvider;
use Lightworx\FilamentSettings\Support\PermissionInstaller;
use Throwable;

class FilamentSettingsServiceProvider extends ServiceProvider
{
    protected array $skipForCommands = [
        'migrate', 'migrate:fresh', 'migrate:rollback', 'migrate:reset',
        'migrate:refresh', 'db:seed', 'package:discover', 'vendor:publish',
        'config:cache', 'config:clear',
    ];

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
        $this->ensureSettingsPermissionExists();
    }

    protected function ensureSettingsPermissionExists(): void
    {
        if ($this->app->runningInConsole()) {
            $command = $_SERVER['argv'][1] ?? null;
            if (in_array($command, $this->skipForCommands, true)) {
                return;
            }
        }

        try {
            PermissionInstaller::ensureExists();
        } catch (QueryException|Throwable) {
            // DB unreachable or table mid-migration — this is a
            // convenience, not a hard requirement, so fail silently.
        }
    }
}
