<?php

namespace App\Filament\Resources\Coupons\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CouponForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Coupon Information')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('code')
                            ->unique(ignoreRecord: true)
                            ->live(debounce: 500)
                            ->afterStateUpdated(
                                fn($state, callable $set) =>
                                $set('code', strtoupper($state))
                            )
                            ->required(),
                        Select::make('type')
                            ->options(['fixed' => 'Fixed', 'percentage' => 'Percentage'])
                            ->default('percentage')
                            ->live()
                            ->required(),
                        TextInput::make('value')
                            ->minValue(0)
                            ->prefix(fn($get) => $get('type') === 'fixed' ? '$' : null)
                            ->suffix(fn($get) => $get('type') === 'percentage' ? '%' : null)
                            ->required()
                            ->numeric(),
                        Toggle::make('is_active')
                            ->required(),
                    ]),
                Section::make('Conditions & Limits')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('minimum_order_value')
                            ->prefix('$')
                            ->numeric()
                            ->default(null),
                        TextInput::make('maximum_discount')
                            ->numeric()
                            ->suffix('%')
                            ->visible(fn($get)=>$get('type') === 'percentage')
                            ->default(null),
                        TextInput::make('usage_limit')
                            ->numeric()
                            ->minValue(1)
                            ->default(null),
                        TextInput::make('usage_limit_per_customer')
                            ->numeric()
                            ->minValue(1)
                            ->default(null),

                    ]),
                Section::make('Validity Period')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        DateTimePicker::make('starts_at')
                        ->native(false)
                        ->helperText('When The coupon becomes active'),
                        DateTimePicker::make('expires_at')
                        ->native(false),

                    ]),





            ]);
    }
}
