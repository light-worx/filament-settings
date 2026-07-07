<?php

namespace Lightworx\FilamentSettings\Support;

use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionInstaller
{
    /**
     * Make sure the permission that gates the Settings page exists.
     * Returns true if it exists/was created, false if it was skipped
     * (e.g. spatie/laravel-permission isn't installed yet).
     */
    public static function ensureExists(): bool
    {
        if (! class_exists(Permission::class)) {
            return false;
        }

        $table = config('permission.table_names.permissions', 'permissions');

        if (! Schema::hasTable($table)) {
            return false;
        }

        $name = config('filament-settings.permission_name', 'access_settings');
        $guard = config('filament-settings.permission_guard') ?? config('auth.defaults.guard');

        Permission::firstOrCreate([
            'name' => $name,
            'guard_name' => $guard,
        ]);

        // Bust Shield/Spatie's cached permission list so it shows up
        // immediately in the Roles UI.
        if (class_exists(PermissionRegistrar::class)) {
            app(PermissionRegistrar::class)->forgetCachedPermissions();
        }

        return true;
    }
}