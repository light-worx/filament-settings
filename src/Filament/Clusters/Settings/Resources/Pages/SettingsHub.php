<?php

namespace Lightworx\FilamentSettings\Filament\Clusters\Settings\Resources\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Facades\Filament;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Lightworx\FilamentSettings\Filament\Clusters\SettingsCluster;

class SettingsHub extends Page
{
    // Sidebar / cluster settings
    protected static ?string $navigationLabel = 'Settings';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;
    protected static ?string $slug = 'settings-hub'; // must be unique
    protected static ?string $cluster = SettingsCluster::class;

    // Blade view
    protected string $view = 'filament-settings::filament.resources.filament-setting-resource.pages.settings-hub';

    /**
     * Return the full URL for this page.
     * Overrides default getUrl() to prevent route-not-found errors.
     */
    public static function getUrl(
        array $parameters = [],
        bool $isAbsolute = true,
        ?string $panel = null,
        ?Model $tenant = null
    ): string {
        $panelInstance = $panel ?? Filament::getCurrentPanel();

        if (! $panelInstance) {
            return '#';
        }

        $url = $panelInstance->getUrl() . '/' . static::$slug;

        if ($isAbsolute) {
            return url($url);
        }

        return $url;
    }


    /**
     * Returns all resources in the SettingsCluster for display in the hub.
     */
    public function getSettingsResources(): array
    {
        $panel = Filament::getCurrentPanel();

        if (! $panel) {
            return [];
        }

        return collect($panel->getResources())
            ->filter(fn ($resource) =>
                $resource::getCluster() === SettingsCluster::class &&
                $resource::canViewAny() &&
                $resource !== static::class // exclude the hub itself
            )
            ->map(fn ($resource) => [
                'label' => $resource::getNavigationLabel(),
                'icon'  => $resource::getNavigationIcon(),
                'url'   => $resource::getNavigationUrl(), // safe URL string
            ])
            ->values()
            ->all();
    }
}
