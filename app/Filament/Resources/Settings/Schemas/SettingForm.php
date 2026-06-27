<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')
                    ->default(null),
                TextInput::make('value')
                    ->default(null),
                Select::make('type')
                    ->label('Data Type')
                    ->options([
                        'string'  => 'String / Text',
                        'numeric' => 'Numeric / Number',
                        'boolean' => 'Boolean (True/False)',
                        'json'    => 'JSON Array',
                    ])
                    ->required()
                    ->default('string'),
                TextInput::make('group')
                    ->required()
                    ->default('general'),
            ]);
    }
}
