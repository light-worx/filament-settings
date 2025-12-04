<?php

namespace Lightworx\FilamentSettings\Filament\Resources\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class FilamentSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')->required(),
                TextInput::make('value'),
                Select::make('setting_type')
                    ->required()
                    ->selectablePlaceholder(false)
                    ->default('text')
                    ->options([
                        'text' => 'Text',
                        'number' => 'Number',
                        'list' => 'List'
                    ]),
                TextInput::make('category')->required()
                    ->default('General') 
            ]);
    }
}
