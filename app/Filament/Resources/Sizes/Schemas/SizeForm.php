<?php

namespace App\Filament\Resources\Sizes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SizeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make("Product Size Management")
                    ->schema([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('chest')
                            ->required()
                            ->numeric(),
                        TextInput::make('length')
                            ->required()
                            ->numeric(),
                    ]),
            ]);
    }
}
