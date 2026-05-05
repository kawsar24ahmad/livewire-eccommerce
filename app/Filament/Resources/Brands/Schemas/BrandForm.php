<?php

namespace App\Filament\Resources\Brands\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class BrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Brand Information')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('slug')
                            ->readOnly()
                            ->visibleOn('edit')
                            ->unique(ignoreRecord: true)
                            ->required(),
                        Textarea::make('description')
                            ->rows(3)
                            ->default(null)
                            ->columnSpanFull(),
                        FileUpload::make('logo')
                            ->disk('public')
                            ->directory('brands')
                            ->imageEditor()
                            ->downloadable()
                            ->maxSize(2024)
                            ->image()
                            ->default(null),
                        TextInput::make('website')
                            ->url()
                            ->placeholder('https://example.com')
                            ->default(null),
                    ]),
                Section::make('Display Settings')
                    ->schema([
                        Toggle::make('is_active')
                            ->required(),
                        TextInput::make('sort_order')
                            ->required()
                            ->numeric()
                            ->default(0),
                    ]),



            ]);
    }
}
