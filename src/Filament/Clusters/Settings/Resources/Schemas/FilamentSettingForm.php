<?php

namespace Lightworx\FilamentSettings\Filament\Clusters\Settings\Resources\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class FilamentSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('label')->required(),
                TextInput::make('key')->required(),
                TextInput::make('value')->label('Default'),
                Select::make('setting_type')
                    ->required()
                    ->live()
                    ->selectablePlaceholder(false)
                    ->default('text')
                    ->options([
                        'text' => 'Text',
                        'password' => 'Password',
                        'textarea' => 'Paragraph',
                        'number' => 'Number',
                        'boolean' => 'Toggle',
                        'list' => 'List',
                        'tags' => 'Tags',
                        'keyvalue' => 'Key / Value',
                        'model' => 'Model dropdown',
                    ]),

                Textarea::make('options')
                    ->helperText('Comma-separated, or a JSON object of value => label.')
                    ->visible(fn (?string $setting_type) => $setting_type === 'list'),

                TextInput::make('options.model')
                    ->label('Model class')
                    ->placeholder('App\\Models\\Rubric')
                    ->helperText('Fully-qualified Eloquent model class name.')
                    ->required(fn (?string $setting_type) => $setting_type === 'model')
                    ->rule(function () {
                        return function (string $attribute, $value, $fail) {
                            if (! class_exists($value) || ! is_subclass_of($value, \Illuminate\Database\Eloquent\Model::class)) {
                                $fail("{$value} is not a valid Eloquent model class.");
                            }
                        };
                    })
                    ->visible(function (Get $get){
                        $setting_type = $get('setting_type');
                        return $setting_type === 'model';
                    }),

                TextInput::make('options.label_field')
                    ->label('Label field')
                    ->default('name')
                    ->required(function (Get $get){
                        $setting_type = $get('setting_type');
                        return $setting_type === 'model';
                    })
                    ->visible(function (Get $get){
                        $setting_type = $get('setting_type');
                        return $setting_type === 'model';
                    }),

                TextInput::make('options.value_field')
                    ->label('Value field')
                    ->default('id')
                    ->required(function (Get $get){
                        $setting_type = $get('setting_type');
                        return $setting_type === 'model';
                    })
                    ->visible(function (Get $get){
                        $setting_type = $get('setting_type');
                        return $setting_type === 'model';
                    }),

                KeyValue::make('options.where')
                    ->label('Filter (equality only)')
                    ->helperText('e.g. active => 1. For anything more complex, use a scope below instead.')
                    ->visible(function (Get $get){
                        $setting_type = $get('setting_type');
                        return $setting_type === 'model';
                    }),

                TextInput::make('options.scope')
                    ->label('Query scope (optional)')
                    ->placeholder('active')
                    ->helperText('Name of a local scope on the model, e.g. "active" calls Model::active().')
                    ->visible(function (Get $get){
                        $setting_type = $get('setting_type');
                        return $setting_type === 'model';
                    }),

                TextInput::make('category')->required()
                    ->default('General'),
            ]);
    }
}