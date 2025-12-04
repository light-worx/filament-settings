<?php

use Lightworx\FilamentSettings\Models\FilamentSetting;

if (!function_exists('setting')) {
    /**
     * Get a setting value by slug using the FilamentSetting model.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting(string $key, $default = null)
    {
        return FilamentSetting::where('key', $key)->value('value') ?? $default;
    }
}
