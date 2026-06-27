<?php

namespace App\Filament\Resources\Colors\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ColorPicker;

class ColorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->required(),
                ColorPicker::make('hex_code')
                    ->label('color')
                    ->helperText('Pick the color for this swatch')
                    ->required()
                    ->hex(),
            ]);
    }
}
