<?php

namespace Lightworx\FilamentSettings\Filament\Clusters\Settings\Resources;

use Lightworx\FilamentSettings\Filament\Clusters\Settings\Resources\Pages\CreateFilamentSetting;
use Lightworx\FilamentSettings\Filament\Clusters\Settings\Resources\Pages\EditFilamentSetting;
use Lightworx\FilamentSettings\Filament\Clusters\Settings\Resources\Pages\ListFilamentSettings;
use Lightworx\FilamentSettings\Filament\Clusters\Settings\Resources\Schemas\FilamentSettingForm;
use Lightworx\FilamentSettings\Filament\Clusters\Settings\Resources\Tables\FilamentSettingsTable;
use Lightworx\FilamentSettings\Models\FilamentSetting;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Lightworx\FilamentSettings\Filament\Clusters\SettingsCluster;

class FilamentSettingResource extends Resource
{
    protected static ?string $model = FilamentSetting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'label';

    protected static ?string $cluster = SettingsCluster::class;

    public static function form(Schema $schema): Schema
    {
        return FilamentSettingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FilamentSettingsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFilamentSettings::route('/'),
            'create' => CreateFilamentSetting::route('/create'),
            'edit' => EditFilamentSetting::route('/{record}/edit')
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
