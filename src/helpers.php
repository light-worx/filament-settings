<?php

use Lightworx\FilamentSettings\Models\FilamentSetting;

if (! function_exists('setting')) {
    function setting(string $key, $default = null)
    {
        // Allow two modes:
        // setting('mailer', 'smtp')
        // setting('mailer', ['value'=>'smtp','category'=>'Email','setting_type'=>'list', 'options'=>[]])

        $defaults = [
            'label' => ucfirst(str_replace('_', ' ', $key)),
            'category' => 'General',
            'value' => null,
            'setting_type' => 'text',
            'options' => null,
        ];

        if (is_array($default)) {
            // Merge user-provided advanced config
            $config = array_merge($defaults, $default);
        } else {
            // Simple mode: only default value
            $config = $defaults;
            $config['value'] = $default;
        }

        // Handle options: only keep it if setting_type == 'list'
        if ($config['setting_type'] !== 'list') {
            $config['options'] = null;
        }

        // First-or-create logic
        $setting = FilamentSetting::firstOrCreate(
            ['key' => $key],
            $config
        );

        // If the user later changes type/category/etc. in code,
        // we can keep DB authoritative OR allow updates here if you want:
        // $setting->update($config);

        return $setting->value;
    }
}
