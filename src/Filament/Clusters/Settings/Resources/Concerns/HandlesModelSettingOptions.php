<?php

namespace Lightworx\FilamentSettings\Filament\Clusters\Settings\Resources\Concerns;

trait HandlesModelSettingOptions
{
    protected function packModelOptions(array $data): array
    {
        if (($data['setting_type'] ?? null) === 'model') {
            $data['options'] = [
                'model' => $data['model_class'] ?? null,
                'label_field' => $data['label_field'] ?? 'name',
                'value_field' => $data['value_field'] ?? 'id',
                'where' => $data['where_filter'] ?? [],
                'scope' => $data['query_scope'] ?? null,
            ];
        }

        unset(
            $data['model_class'],
            $data['label_field'],
            $data['value_field'],
            $data['where_filter'],
            $data['query_scope'],
        );

        return $data;
    }

    protected function unpackModelOptions(array $data): array
    {
        if (($data['setting_type'] ?? null) === 'model' && is_array($data['options'] ?? null)) {
            $data['model_class'] = $data['options']['model'] ?? null;
            $data['label_field'] = $data['options']['label_field'] ?? 'name';
            $data['value_field'] = $data['options']['value_field'] ?? 'id';
            $data['where_filter'] = $data['options']['where'] ?? [];
            $data['query_scope'] = $data['options']['scope'] ?? null;
        }

        return $data;
    }
}