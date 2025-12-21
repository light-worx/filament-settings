<?php

namespace Lightworx\FilamentSettings\Filament\Clusters\Settings\Resources\Pages;

use Lightworx\FilamentSettings\Filament\Clusters\Settings\Resources\FilamentSettingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFilamentSetting extends EditRecord
{
    protected static string $resource = FilamentSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
