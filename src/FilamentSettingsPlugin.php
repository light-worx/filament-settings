<?php

namespace Lightworx\FilamentSettings;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Actions\Action;
use Lightworx\FilamentSettings\Filament\Clusters\Settings\Resources\FilamentSettingResource;
use Lightworx\FilamentSettings\Filament\Clusters\Settings\Resources\Pages\FilamentSettings;
use Lightworx\FilamentSettings\Filament\Clusters\Settings\Resources\Pages\SettingsHub;

class FilamentSettingsPlugin implements Plugin
{
    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());
        return $plugin;
    }

    public function getId(): string
    {
        return 'filament-settings';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            FilamentSettingResource::class,
        ]);
        $panel->pages([
            FilamentSettings::class,
            SettingsHub::class,
        ]);
        $panel->userMenuItems([
            Action::make('settings')
                ->label('Settings')
                ->icon('heroicon-o-cog-6-tooth')
                ->url('/admin/filament-settings/settings'),
        ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
