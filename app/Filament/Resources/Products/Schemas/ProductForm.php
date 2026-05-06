<?php

namespace App\Filament\Resources\Products\Schemas;

use Illuminate\Support\Str;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use function Livewire\Volt\placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\Placeholder;
use Filament\Infolists\Components\TextEntry;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Tabs::make('Product details')
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make('Basic Information')
                            ->icon(Heroicon::InformationCircle)
                            ->schema([
                                Section::make('Product Details')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('name')
                                            ->required(),
                                        TextInput::make('slug')
                                            ->unique(ignoreRecord: true)
                                            ->visible(fn($operation) => $operation === 'edit')
                                            ->required(),

                                        Select::make('category_id')
                                            ->relationship('category', 'name')
                                            ->preload()
                                            // ->searchable()
                                            ->required()
                                            ->createOptionForm([
                                                TextInput::make('name')
                                                    ->required(),
                                                TextInput::make('slug')
                                                    ->unique(ignoreRecord: true)
                                                    ->readOnly()
                                                    ->visibleOn('edit'),
                                            ]),
                                        Select::make('brand_id')
                                            ->relationship('brand', 'name')
                                            ->preload()
                                            ->required()
                                            ->createOptionForm([
                                                TextInput::make('name')
                                                    ->required(),
                                                TextInput::make('slug')
                                                    ->unique(ignoreRecord: true)
                                                    ->readOnly()
                                                    ->visibleOn('edit'),
                                            ]),


                                    ]),
                                Section::make('Product Description')
                                    ->schema([

                                        Textarea::make('short_description')
                                            ->default(null)
                                            ->columnSpanFull(),
                                        RichEditor::make('description')
                                            ->default(null)
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                        Tab::make('Pricing & Inventory')
                            ->icon(Heroicon::CurrencyDollar)
                            ->schema([
                                Section::make('Pricing')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('sku')
                                            ->label('SKU')
                                            ->unique(ignoreRecord: true)
                                            ->helperText('Stock keeping Unit - unique identifier')
                                            ->required()
                                            ->default(fn() => 'SKU-' . strtoupper(Str::random(8))),
                                        TextInput::make('price')
                                            ->required()
                                            ->numeric()
                                            ->minValue(0)
                                            ->step(0.1)
                                            ->helperText('Selling Price')
                                            ->prefix('$'),
                                        TextInput::make('compare_price')
                                            ->numeric()
                                            ->minValue(0)
                                            ->step(0.1)
                                            ->helperText('Original Price to show discount')
                                            ->prefix('$'),
                                        TextInput::make('cost_price')
                                            ->numeric()
                                            ->minValue(0)
                                            ->step(0.1)
                                            ->helperText('Cost from Supplier (for profit calculation)')
                                            ->prefix('$'),
                                    ]),
                                Section::make('Inventory')
                                    ->columns(2)
                                    ->schema([
                                        Toggle::make('manage_stock')
                                            ->default(true)
                                            ->live()
                                            ->required(),
                                        TextInput::make('stock_quantity')
                                            ->required(fn(callable $get) => $get('manage_stock'))
                                            ->disabled(fn(callable $get) => !$get('manage_stock'))
                                            ->numeric()
                                            ->default(0),
                                        TextInput::make('low_stock_threshold')
                                            ->required()
                                            ->numeric()
                                            ->minValue(0)
                                            ->default(10),

                                        Select::make('stock_status')
                                            ->options([
                                                'in_stock' => 'In stock',
                                                'out_of_stock' => 'Out of stock',
                                                'on_back_order' => 'On back order'
                                            ])
                                            ->native(false)
                                            ->default('in_stock')
                                            ->required(),
                                        TextInput::make('weight')
                                            ->numeric()
                                            ->minValue(0)
                                            ->helperText('Used for shipping calculation')
                                            ->default(null),
                                    ]),
                            ]),
                        Tab::make('Images')
                            ->icon(Heroicon::Photo)
                            ->schema([
                                Section::make('Product Images')
                                    ->description('Upload multiple images. the first image will be the primary image.')
                                    ->schema([
                                        FileUpload::make('Images')
                                            ->label('Product Images')
                                            ->multiple()
                                            ->image()
                                            ->directory('products')
                                            ->disk('public')
                                            ->imageEditor()
                                            ->maxSize(2048)
                                            ->reorderable()
                                            ->columnSpanFull()
                                            ->helperText('You can drug and drop to reorder images.')
                                            ->saveRelationshipsUsing(function ($component, $state, $record) {
                                                $record->images()->delete();
                                                if (is_array($state)) {
                                                    foreach ($state as $index => $imagePath) {
                                                        $record->images()->create([
                                                            'image_path' => $imagePath,
                                                            'is_primary' => $index === 0,
                                                            'sort_order' => $index,
                                                        ]);
                                                    }
                                                }
                                            }),
                                    ]),
                            ]),
                        Tab::make('Settings')
                            ->icon(Heroicon::Cog6Tooth)
                            ->schema([
                                Section::make('Product Status')
                                    ->schema([
                                        Toggle::make('is_active')
                                            ->required(),
                                        Toggle::make('is_featured')
                                            ->required(),
                                        TextInput::make('sort_order')
                                            ->required()
                                            ->numeric()
                                            ->default(0),


                                    ]),
                                Section::make('Statics')
                                    ->schema([
                                        TextEntry::make('views_cont')
                                            ->label('Total Views')
                                            ->badge()
                                            ->state(fn($record) => $record?->views_cont ?? 0),
                                        TextEntry::make('created_at')
                                            ->label('Crated At')
                                            ->badge()
                                            ->state(fn($record) => $record?->created_at->diffForHumans() ?? '-'),
                                    ]),
                            ]),
                        Tab::make('Product Variant')
                            ->icon(Heroicon::Squares2x2)
                            ->schema([
                                Toggle::make('has_variants')
                                    ->live()
                                    ->required(),
                                Section::make('Product Variant')
                                    ->description('Add variants like different sizes or colors')
                                    ->visible(fn($get) => $get('has_variants'))
                                    ->schema([

                                        Repeater::make('variants')
                                            ->relationship('variants')
                                            ->schema([
                                                TextInput::make('name')
                                                    ->label('Variant Name')
                                                    ->required()
                                                    ->placeholder('e.g. Red - Large'),

                                                KeyValue::make('options'),
                                                TextInput::make('sku')
                                                    ->label('SKU')
                                                    ->unique(ignoreRecord: true)
                                                    ->helperText('Stock keeping Unit - unique identifier')
                                                    ->default(fn() => 'VAR-' . strtoupper(Str::random(8)))
                                                    ->required()
                                                    ->columnSpanFull(),
                                                TextInput::make('price')
                                                    ->required()
                                                    ->numeric()
                                                    ->minValue(0)
                                                    ->step(0.1)
                                                    ->helperText('Selling Price')
                                                    ->prefix('$'),
                                                TextInput::make('compare_price')
                                                    ->numeric()
                                                    ->minValue(0)
                                                    ->step(0.1)
                                                    ->helperText('Original Price to show discount')
                                                    ->prefix('$'),
                                                TextInput::make('cost_price')
                                                    ->numeric()
                                                    ->minValue(0)
                                                    ->step(0.1)
                                                    ->helperText('Cost from Supplier (for profit calculation)')
                                                    ->prefix('$'),


                                                Select::make('stock_status')
                                                    ->options([
                                                        'in_stock' => 'In stock',
                                                        'out_of_stock' => 'Out of stock',
                                                        'on_back_order' => 'On back order'
                                                    ])
                                                    ->native(false)
                                                    ->default('in_stock')
                                                    ->required(),
                                                Toggle::make('is_active')
                                                    ->label('Active')
                                                    ->default(true),
                                            ])
                                            ->columns(2)
                                            ->defaultItems(0)
                                            ->collapsible()
                                            ->itemLabel(fn($state)=> $state['name'] ?? null )
                                            ->addActionLabel('Add Variant'),

                                    ]),

                            ]),
                        Tab::make('SEO')
                            ->icon(Heroicon::MagnifyingGlass)
                            ->schema([
                                Section::make('Search Engine Optimization')
                                    ->schema([
                                        TextInput::make('meta_title')
                                            ->default(null),
                                        Textarea::make('meta_description')
                                            ->default(null)
                                            ->columnSpanFull(),

                                    ]),

                            ]),
                    ]),









            ]);
    }
}
