<?php

namespace Lightworx\FilamentSettings\Filament\Clusters\Settings\Resources\Pages;

use Lightworx\FilamentSettings\Filament\Clusters\Settings\Resources\FilamentSettingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFilamentSettings extends ListRecords
{
    protected static string $resource = FilamentSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
