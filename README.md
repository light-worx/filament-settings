# Filament-settings

## Installation
composer require "light-worx/filament-settings:dev-master"

Add FilamentSettingsPlugin::make() to your AdminPanelProvider

To add your own resources to the Settings menu, assign them to Lightworx\FilamentSettings\Filament\Clusters\SettingsCluster.

## Usage
Settings can be added via the forms at /admin/settings/filament-settings and then called in your site, using the settings helper as follows:

settings('app_name','Laravel')