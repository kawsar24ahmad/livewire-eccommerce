<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Illuminate\Support\Facades\Hash;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Customer Information')
                    ->columns(2)

                    ->schema([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('email')
                            ->label('Email address')
                            ->email()
                            ->unique(ignoreRecord: true)
                            ->required(),
                        DateTimePicker::make('email_verified_at'),

                        TextInput::make('phone')
                            ->tel()
                            ->default(null),
                        DatePicker::make('date_of_birth')
                            ->native(false)
                            ->displayFormat('M d, Y'),
                        Select::make('gender')
                            ->options(['male' => 'Male', 'female' => 'Female', 'other' => 'Other'])
                            ->default(null)
                            ->native(false),
                        Toggle::make('is_active')
                            ->required(),
                    ]),
                Section::make('Password Info')
                    ->schema([
                        TextInput::make('password')
                            ->password()
                            ->revealable()
                            ->minLength(6)
                            ->rules(['numeric'])
                            ->dehydrated(fn($state) => filled($state))
                            ->dehydrateStateUsing(fn($state) => filled($state) ? Hash::make($state) : null)
                            ->required(fn($operation) => $operation === 'create'),
                        TextInput::make('password_confirmation')
                            ->password()
                            ->revealable()
                            ->same('password')
                            ->dehydrated(false)
                            ->required(fn($operation) => $operation === 'create'),
                    ]),

            ]);
    }
}
