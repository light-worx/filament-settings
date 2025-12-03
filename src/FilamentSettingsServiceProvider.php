<?php

namespace Lightworx\FilamentSettings;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Lightworx\FilamentSettings\Models\HelpDocument;
use Lightworx\FilamentSettings\Commands\InstallCommand;

class FilamentSettingsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-settings')
            ->hasConfigFile()
            ->hasTranslations()
            ->hasCommand(InstallCommand::class)
            ->hasMigration('create_filament_settings_tables');
    }

    public function packageBooted(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/Http/routes.php');
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'filament-settings');
    }

    /**
     * Return the contextual help document for the current route.
     */
    protected function getContextualHelp(): ?HelpDocument
    {
        $currentRoute = request()->route()?->getName();

        if (!$currentRoute) {
            return null;
        }

        // Exact match
        $helpDoc = HelpDocument::where('slug', $currentRoute)
            ->where('is_published', true)
            ->first();

        if ($helpDoc) {
            return $helpDoc;
        }

        // Partial matching: last 1-3 segments of route
        $routeParts = explode('.', $currentRoute);
        $slugPatterns = [
            implode('.', array_slice($routeParts, -2)),
            implode('.', array_slice($routeParts, -3)),
            end($routeParts),
        ];

        foreach ($slugPatterns as $pattern) {
            $helpDoc = HelpDocument::where('slug', $pattern)
                ->where('is_published', true)
                ->first();
            if ($helpDoc) {
                return $helpDoc;
            }
        }

        return null;
    }
}
