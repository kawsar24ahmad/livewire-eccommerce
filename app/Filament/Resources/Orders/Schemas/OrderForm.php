<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Order Status')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Select::make('status')
                            ->label('Order Status')
                            ->options([
                                'pending' => 'Pending',
                                'processing' => 'Processing',
                                'shipped' => 'Shipped',
                                'delivered' => 'Delivered',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('pending')
                            ->native(false)
                            ->required(),
                        TextInput::make('tracking_number')
                            ->helperText('Shipping Tracking Number')
                            ->default(null),
                        Select::make('payment_status')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'failed' => 'Failed',
                                'returned' => 'Returned',
                            ])
                            ->native(false)
                            ->required()
                            ->default('pending'),
                        Textarea::make('admin_notes')
                            ->default(null)
                            ->columnSpanFull(),
                    ]),
                // TextInput::make('order_number')
                //     ->required(),
                // TextInput::make('customer_id')
                //     ->required()
                //     ->numeric(),
                // TextInput::make('coupon_id')
                //     ->numeric()
                //     ->default(null),
                // TextInput::make('subtotal')
                //     ->required()
                //     ->numeric(),
                // TextInput::make('discount_amount')
                //     ->required()
                //     ->numeric()
                //     ->default(0.0),
                // TextInput::make('shipping_cost')
                //     ->required()
                //     ->numeric()
                //     ->default(0.0)
                //     ->prefix('$'),
                // TextInput::make('tax_amount')
                //     ->required()
                //     ->numeric()
                //     ->default(0.0),
                // TextInput::make('total')
                //     ->numeric()
                //     ->default(null),
                // TextInput::make('shipping_full_name')
                //     ->required(),
                // TextInput::make('shipping_phone')
                //     ->tel()
                //     ->required(),
                // TextInput::make('shipping_address_line_1')
                //     ->required(),
                // TextInput::make('shipping_address_line_2')
                //     ->default(null),
                // TextInput::make('shipping_city')
                //     ->required(),
                // TextInput::make('shipping_state')
                //     ->default(null),
                // TextInput::make('shipping_postal_code')
                //     ->default(null),
                // TextInput::make('shipping_country')
                //     ->default(null),
                // Toggle::make('is_default')
                //     ->required(),
                // Select::make('type')
                //     ->options(['shipping' => 'Shipping', 'billing' => 'Billing', 'both' => 'Both'])
                //     ->default('shipping')
                //     ->required(),
                // Select::make('payment_method')
                //     ->options(['stripe' => 'Stripe', 'cash_on_delivery' => 'Cash on delivery'])
                //     ->default('cash_on_delivery')
                //     ->required(),

                // TextInput::make('transaction_id')
                //     ->default(null),


                // Textarea::make('customer_notes')
                //     ->default(null)
                //     ->columnSpanFull(),

            ]);
    }
}
