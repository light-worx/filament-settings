<?php

namespace Lightworx\FilamentSettings\Filament\Clusters\Settings\Resources\Pages;

use Lightworx\FilamentSettings\Filament\Clusters\Settings\Resources\Concerns\HandlesModelSettingOptions;
use Lightworx\FilamentSettings\Filament\Clusters\Settings\Resources\FilamentSettingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFilamentSetting extends CreateRecord
{
    use HandlesModelSettingOptions;

    protected static string $resource = FilamentSettingResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->packModelOptions($data);
    }
}