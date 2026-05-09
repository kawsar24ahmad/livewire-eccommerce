<?php

namespace App\Filament\Resources\Reviews\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Moderation')
                    ->schema([
                        // TextInput::make('product_id')
                        //     ->required()
                        //     ->numeric(),
                        // TextInput::make('customer_id')
                        //     ->required()
                        //     ->numeric(),
                        // TextInput::make('order_id')
                        //     ->required()
                        //     ->numeric(),
                        // TextInput::make('rating')
                        //     ->required()
                        //     ->numeric(),
                        // TextInput::make('title')
                        //     ->default(null),
                        // Textarea::make('content')
                        //     ->default(null)
                        //     ->columnSpanFull(),
                        // Toggle::make('is_verified_purchase')
                        //     ->required(),
                        Toggle::make('is_approved')
                            ->label('Approve Review')
                            ->helperText('Approved Reviews will be visible on product page')
                            ->required(),
                    ]),
            ]);
    }
}
