<?php

namespace Lightworx\FilamentSettings;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Gate;
use Lightworx\FilamentSettings\Filament\Clusters\Settings\Resources\FilamentSettingResource;
use Lightworx\FilamentSettings\Filament\Clusters\Settings\Resources\Pages\FilamentSettings;
use Lightworx\FilamentSettings\Filament\Clusters\SettingsCluster;
use Lightworx\FilamentSettings\Models\FilamentSetting;

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
            SettingsCluster::class,
            FilamentSettings::class,
        ]);
        $panel->userMenuItems([
            Action::make('settings')
                ->label('Settings')
                ->icon('heroicon-o-cog-6-tooth')
                //->url(fn () => FilamentSettingResource::getUrl())
                ->url('/admin/settings/general')
                ->visible(function () {
                    $user = auth()->user();

                    if (! $user) {
                        return false;
                    }

                    // If a policy exists, enforce it
                    if (Gate::getPolicyFor(FilamentSetting::class)) {
                        return $user->can('update', FilamentSetting::class);
                    }

                    // No policy exists → single-user site → allow
                    return true;
                }),
        ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
