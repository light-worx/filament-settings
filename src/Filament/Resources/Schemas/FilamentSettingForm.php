<?php

namespace Lightworx\FilamentSettings\Filament\Resources\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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
                    ->selectablePlaceholder(false)
                    ->default('text')
                    ->options([
                        'text' => 'Text',
                        'password' => 'Password',
                        'textarea' => 'Paragraph',
                        'number' => 'Number',
                        'boolean' => 'Toggle',
                        'list' => 'List'
                    ]),
                Textarea::make('options'),
                TextInput::make('category')->required()
                    ->default('General') 
            ]);
    }
}
