<?php

namespace Lightworx\FilamentSettings\Filament\Clusters\Settings\Resources\Pages;

use Lightworx\FilamentSettings\Filament\Clusters\Settings\Resources\Concerns\HandlesModelSettingOptions;
use Lightworx\FilamentSettings\Filament\Clusters\Settings\Resources\FilamentSettingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFilamentSetting extends EditRecord
{
    use HandlesModelSettingOptions;

    protected static string $resource = FilamentSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return $this->unpackModelOptions($data);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $this->packModelOptions($data);
    }
}