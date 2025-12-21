<?php

namespace Lightworx\FilamentSettings\Filament\Clusters;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class SettingsCluster extends Cluster
{
    protected static ?string $navigationLabel = 'Settings';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::Cog6Tooth;
    protected static string|UnitEnum|null $navigationGroup = 'Administration';
    protected static SubNavigationPosition|null $subNavigationPosition = SubNavigationPosition::Top;
    protected static ?string $slug = 'settings';
}
