<?php

namespace Lightworx\FilamentSettings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FilamentSetting extends Model
{

    public $table = 'filament_settings';
    protected $guarded = ['id'];

}