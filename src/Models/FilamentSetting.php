<?php

namespace Lightworx\FilamentSettings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FilamentSetting extends Model
{

    public $table = 'filament_settings';
    protected $guarded = ['id'];
    public $timestamps = false;

    protected $casts = [
        'options' => 'array'
    ];

    /**
     * Accessor: When you call $setting->value
     */
    public function getValueAttribute($value)
    {
        if (empty($value)) {
            return $value;
        }

        // Check if the string is valid JSON (starts with [ or {)
        if (is_string($value) && (str_starts_with($value, '[') || str_starts_with($value, '{'))) {
            $decoded = json_decode($value, true);
            
            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }
        }

        return $value;
    }

    /**
     * Mutator: When you save to the database
     */
    public function setValueAttribute($value)
    {
        // If it's an array (from TagsInput/KeyValue), encode it.
        // Otherwise, store it as a raw string.
        if (is_array($value)) {
            $this->attributes['value'] = json_encode($value);
        } else {
            $this->attributes['value'] = $value;
        }
    }

}