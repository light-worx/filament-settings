<?php

namespace Lightworx\FilamentSettings\Filament\Resources\Pages;

use Lightworx\FilamentSettings\Filament\Resources\FilamentSettingResource;
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
