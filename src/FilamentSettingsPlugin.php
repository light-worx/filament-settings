<?php

namespace Lightworx\FilamentSettings;

use Filament\Actions\Action;
use Filament\Contracts\Plugin;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Filament\Support\Concerns\EvaluatesClosures;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
use Lightworx\FilamentSettings\Filament\HelpDocuments\HelpDocumentResource;
use Lightworx\FilamentSettings\Filament\HelpSettings\HelpIssueResource;
use Lightworx\FilamentSettings\Http\Livewire\HelpModal;
use Lightworx\FilamentSettings\Models\HelpDocument;
use Lightworx\FilamentSettings\Models\HelpIssue;
use Livewire\Livewire;

class FilamentSettingsPlugin implements Plugin
{
    use EvaluatesClosures;

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
        // Register resources
        $panel->resources([
            HelpIssueResource::class,
            HelpDocumentResource::class,
        ]);

        // Prepare user menu items
        $menuItems = [
            // Static Help Settings link
            Action::make('settings')
                ->label('Settings')
                ->icon('heroicon-o-cog-6-tooth')
                ->url(fn () => HelpIssueResource::getUrl('index')),
        ];
        $panel->userMenuItems($menuItems);
    }
}
