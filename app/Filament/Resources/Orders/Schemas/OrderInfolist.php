<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use App\Filament\Resources\Customers\CustomerResource;

class OrderInfolist
{
    /**
     * Configure the Order Infolist schema according to Filament v5 specifications.
     */
    public static function configure(Schema $schema): Schema
    {
        // return $schema
        //     ->components([

        //         // ১. অর্ডার স্ট্যাটাস ও কোর ডেটার জন্য ৪-কলামের ক্লিন ওভারভিউ গ্রিড
        //         Section::make('Order Status')
        //             ->columnSpanFull()
        //             ->schema([
        //                 Grid::make(4)
        //                     ->schema([
        //                         TextEntry::make('total')
        //                             ->money('BDT')
        //                             ->label('Grand Total')
        //                             ->weight(FontWeight::Bold)
        //                             ->size(TextSize::Large)
        //                             ->color('primary'),

        //                         TextEntry::make('status')
        //                             ->badge()
        //                             ->color(fn(string $state): string => match ($state) {
        //                                 'pending' => 'warning',
        //                                 'processing' => 'info',
        //                                 'shipped' => 'primary',
        //                                 'delivered' => 'success',
        //                                 'cancelled' => 'danger',
        //                                 default => 'gray',
        //                             }),

        //                         TextEntry::make('payment_status')
        //                             ->badge()
        //                             ->color(fn(string $state): string => match ($state) {
        //                                 'paid' => 'success',
        //                                 'pending' => 'warning',
        //                                 'failed', 'returned' => 'danger',
        //                                 default => 'gray',
        //                             }),

        //                         TextEntry::make('created_at')
        //                             ->label('Ordered On')
        //                             ->dateTime('d M Y, h:i A'),
        //                     ]),
        //             ]),

        //         // ২. অর্ডারড আইটেম লিস্ট
        //         Section::make('Ordered Items')
        //             ->icon('heroicon-o-shopping-bag')
        //             ->columnSpanFull()
        //             ->collapsible()
        //             ->schema([
        //                 RepeatableEntry::make('items')
        //                     ->hiddenLabel()
        //                     ->schema([
        //                         Grid::make(6)
        //                             ->schema([

        //                                 ImageEntry::make('product.primaryImage.image_path')
        //                                     ->disk('public')
        //                                     ->imageHeight(80)
        //                                     ->imageWidth(80)
        //                                     ->label('Image')
        //                                     ->columnSpan(1),

        //                                 TextEntry::make('product.name')
        //                                     ->label('Product')
        //                                     ->weight(FontWeight::Medium)
        //                                     ->columnSpan(3),

        //                                 TextEntry::make('quantity')
        //                                     ->label('Qty')
        //                                     ->columnSpan(1),

        //                                 TextEntry::make('price')
        //                                     ->money('BDT')
        //                                     ->label('Price')
        //                                     ->columnSpan(1),
        //                             ]),
        //                     ]),
        //             ]),

        //         // ৩. ফাইনান্সিয়াল লেজার ব্রেকডাউন
        //         Section::make('Financial Ledger Breakdown')
        //             ->icon('heroicon-o-calculator')
        //             ->columnSpanFull()
        //             ->schema([
        //                 Grid::make(3)
        //                     ->schema([
        //                         TextEntry::make('subtotal')
        //                             ->money('BDT')
        //                             ->alignStart(),

        //                         TextEntry::make('discount_amount')
        //                             ->label('Promo Discount')
        //                             ->money('BDT')
        //                             ->color('danger')
        //                             ->alignStart(),

        //                         TextEntry::make('shipping_cost')
        //                             ->label('Shipping Fee')
        //                             ->money('BDT')
        //                             ->alignStart(),
        //                     ]),
        //             ]),

        //         // ৪. লজিস্টিকস ও ফুলফিলমেন্ট
        //         Section::make('Logistics / Fulfillment')
        //             ->icon('heroicon-o-truck')
        //             ->columnSpanFull()
        //             ->schema([
        //                 Grid::make(3)
        //                     ->schema([
        //                         TextEntry::make('type')
        //                             ->badge()
        //                             ->label('Order Type'),

        //                         TextEntry::make('payment_method')
        //                             ->badge()
        //                             ->label('Payment Method'),

        //                         TextEntry::make('tracking_number')
        //                             ->label('Tracking Number')
        //                             ->placeholder('No tracking assigned yet')
        //                             ->copyable(),
        //                     ]),
        //             ]),

        //         // ৫. কাস্টমার প্রোফাইল
        //         Section::make('Customer Profile')
        //             ->icon('heroicon-o-user')
        //             ->columnSpanFull()
        //             ->schema([
        //                 Grid::make(2)
        //                     ->schema([
        //                         TextEntry::make('customer.name')
        //                             ->label('Name')
        //                             ->weight(FontWeight::Bold)
        //                             ->color('primary')
        //                             ->url(
        //                                 fn($record) => $record->customer
        //                                     ? CustomerResource::getUrl('edit', ['record' => $record->customer])
        //                                     : null
        //                             ),

        //                         TextEntry::make('customer.email')
        //                             ->label('Account Email')
        //                             ->icon('heroicon-m-envelope')
        //                             ->copyable(),
        //                     ]),
        //             ]),

        //         // ৬. শিপিং এড্রেস টার্গেট
        //         Section::make('Shipping Address Target')
        //             ->icon('heroicon-o-map-pin')
        //             ->columnSpanFull()
        //             ->schema([
        //                 Grid::make(3)
        //                     ->schema([
        //                         TextEntry::make('shipping_full_name')
        //                             ->label('Recipient')
        //                             ->weight(FontWeight::Medium),

        //                         TextEntry::make('shipping_phone')
        //                             ->label('Contact Number')
        //                             ->icon('heroicon-m-phone')
        //                             ->badge()
        //                             ->url(fn($record) => 'tel:' . $record->shipping_phone),

        //                         TextEntry::make('address_block')
        //                             ->label('Delivery Address')
        //                             ->state(fn($record) => trim(implode(", ", array_filter([
        //                                 $record->shipping_address_line_1,
        //                                 $record->shipping_address_line_2,
        //                                 $record->shipping_city,
        //                                 $record->shipping_state,
        //                                 $record->shipping_postal_code,
        //                                 $record->shipping_country,
        //                             ]))))
        //                             ->lineClamp(3)
        //                             ->prose(),
        //                     ]),
        //             ]),
        //     ]);
        return $schema
            ->components([

                Grid::make()
                    ->columns([
                        'default' => 1,
                        'xl' => 3,
                    ])
                    ->columnSpanFull()
                    ->schema([

                        // LEFT SIDE
                        Grid::make(1)
                            ->columnSpan(2)
                            ->schema([

                                Section::make('Order Information')
                                    ->icon('heroicon-o-information-circle')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                TextEntry::make('order_number')
                                                    ->label('Order Number'),

                                                TextEntry::make('created_at')
                                                    ->label('Order Date')
                                                    ->dateTime(),

                                                TextEntry::make('status')
                                                    ->badge(),

                                                TextEntry::make('payment_status')
                                                    ->badge(),
                                            ])
                                    ]),

                                Section::make('Ordered Items')
                                    ->icon('heroicon-o-shopping-bag')
                                    ->schema([
                                        RepeatableEntry::make('items')
                                            ->hiddenLabel()
                                            ->schema([
                                                Grid::make(6)
                                                    ->schema([

                                                        ImageEntry::make('product.primaryImage.image_path')
                                                            ->disk('public')
                                                            ->imageHeight(70)
                                                            ->imageWidth(70)
                                                            ->columnSpan(1),

                                                        TextEntry::make('product.name')
                                                            ->weight(FontWeight::Bold)
                                                            ->columnSpan(3),

                                                        TextEntry::make('quantity')
                                                            ->badge()
                                                            ->columnSpan(1),

                                                        TextEntry::make('subtotal')
                                                            ->money('BDT')
                                                            ->columnSpan(1),

                                                    ]),
                                            ]),
                                    ]),

                                Section::make('Shipping Address')
                                    ->icon('heroicon-o-map-pin')
                                    ->schema([
                                        TextEntry::make('Customer Info')->state(fn($record) => [
                                            $record->shipping_full_name,
                                            $record->shipping_phone,
                                        ]),



                                        TextEntry::make('address')
                                            ->state(fn($record) => collect([
                                                $record->shipping_address_line_1,
                                                $record->shipping_address_line_2,
                                                $record->shipping_city,
                                                $record->shipping_state,
                                                $record->shipping_postal_code,
                                                $record->shipping_country,
                                            ])->filter()->implode(', '))
                                            ->columnSpanFull(),
                                    ]),

                                Section::make('Order History')
                                    ->icon('heroicon-o-clock')
                                    ->visible(fn($record) => $record->orderStatuses->isNotEmpty())
                                    ->schema([
                                        RepeatableEntry::make('orderStatuses')
                                            ->hiddenLabel()
                                            ->contained(false)
                                            ->grid(1)
                                            ->schema([
                                                Section::make()
                                                    ->schema([
                                                        Grid::make([
                                                            'default' => 1,
                                                            'md' => 3,
                                                        ])
                                                            ->schema([
                                                                TextEntry::make('status')
                                                                    ->badge()
                                                                    ->label('Status')
                                                                    ->color(fn(string $state): string => match ($state) {
                                                                        'pending' => 'warning',
                                                                        'processing' => 'info',
                                                                        'shipped' => 'primary',
                                                                        'delivered' => 'success',
                                                                        'cancelled' => 'danger',
                                                                        default => 'gray',
                                                                    }),

                                                                TextEntry::make('notes')
                                                                    ->label('Notes')
                                                                    ->placeholder('No notes provided'),

                                                                TextEntry::make('created_at')
                                                                    ->label('Time')
                                                                    ->since()
                                                                    ->color('gray'),
                                                            ]),
                                                    ])
                                            ]),
                                    ])
                                    ->collapsible(),
                            ]),

                        // RIGHT SIDEBAR
                        Grid::make(1)
                            ->columnSpan(1)
                            ->schema([

                                Section::make('Order Summary')
                                    ->icon('heroicon-o-calculator')
                                    ->schema([
                                        Grid::make(1)
                                            ->schema([

                                                TextEntry::make('subtotal')
                                                    ->label('Subtotal')
                                                    ->money('BDT')
                                                    ->color('gray')
                                                    ->columnSpanFull(),

                                                TextEntry::make('discount_amount')
                                                    ->label('Discount')
                                                    ->money('BDT')
                                                    ->color('danger'),

                                                TextEntry::make('shipping_cost')
                                                    ->label('Shipping Fee')
                                                    ->money('BDT')
                                                    ->color('gray'),

                                                TextEntry::make('total')
                                                    ->label('Grand Total')
                                                    ->money('BDT')
                                                    ->weight(FontWeight::Bold)
                                                    ->size(TextSize::Large)
                                                    ->color('primary'),
                                            ]),
                                    ])
                                    ->extraAttributes([
                                        'class' => 'rounded-xl border border-gray-200 shadow-sm p-2'
                                    ])
                                    ->collapsible(),

                                Section::make('Customer Profile')
                                    ->icon('heroicon-o-user')
                                    ->schema([

                                        Grid::make(1)
                                            ->schema([

                                                // Name (highlighted)
                                                TextEntry::make('customer.name')
                                                    ->hiddenLabel()
                                                    ->weight(FontWeight::Bold)
                                                    ->size(TextSize::Large)
                                                    ->color('primary'),

                                                // Email
                                                TextEntry::make('customer.email')
                                                    ->hiddenLabel()
                                                    ->icon('heroicon-m-envelope')
                                                    ->copyable()
                                                    ->color('gray'),

                                                // Phone (click to call)
                                                TextEntry::make('shipping_phone')
                                                    ->hiddenLabel()
                                                    ->icon('heroicon-m-phone')
                                                    ->url(fn($record) => 'tel:' . $record->shipping_phone)
                                                    ->copyable()
                                                    ->color('success'),
                                            ]),
                                    ])
                                    ->collapsible(),



                                Section::make('Logistics')
                                    ->icon('heroicon-o-truck')
                                    ->schema([
                                        TextEntry::make('tracking_number')
                                            ->copyable(),

                                        TextEntry::make('payment_method')
                                            ->badge(),

                                        TextEntry::make('type')
                                            ->badge(),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}
