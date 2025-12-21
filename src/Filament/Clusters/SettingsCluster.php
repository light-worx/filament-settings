<?php

namespace Lightworx\FilamentSettings\Filament\Clusters;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;

class SettingsCluster extends Cluster
{
    protected static ?string $navigationLabel = 'Settings';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::Cog6Tooth;
}
