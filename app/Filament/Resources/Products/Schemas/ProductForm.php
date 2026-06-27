<?php

namespace App\Filament\Resources\Products\Schemas;

use Illuminate\Support\Str;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\ColorPicker;
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
                                        TextInput::make('name')->required(),
                                        TextInput::make('slug')
                                            ->unique(ignoreRecord: true)
                                            ->visibleOn('edit')
                                            ->required(),
                                        // Select::make('category_id')
                                        //     ->relationship('category', 'name')
                                        //     ->preload()->searchable()->native(false)->required()
                                        //     ->createOptionForm([
                                        //         TextInput::make('name')->required(),
                                        //         TextInput::make('slug')
                                        //             ->unique(ignoreRecord: true)
                                        //             ->readOnly()
                                        //             ->visibleOn('edit'),
                                        //     ]),


                                        Select::make('category_id')
                                            ->relationship('category', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->native(false)
                                            ->allowHtml()
                                            ->required()
                                            ->getOptionLabelFromRecordUsing(function (Model $record) {

                                                if ($record->image) {
                                                    return '
                                                        <div style="display:flex;align-items:center;gap:10px;">
                                                            <img
                                                                src="' . asset('storage/' . $record->image) . '"
                                                                style="
                                                                    width:20px;
                                                                    height:20px;
                                                                    border-radius:6px;
                                                                    object-fit:cover;
                                                                "
                                                            >
                                                            <span>' . e($record->name) . '</span>
                                                        </div>
                                                    ';
                                                }

                                                return e($record->name);
                                            })
                                            ->createOptionForm([
                                                TextInput::make('name')->required(),
                                                TextInput::make('slug')
                                                    ->unique(ignoreRecord: true)
                                                    ->readOnly()
                                                    ->visibleOn('edit'),
                                                FileUpload::make('image'),
                                            ]),

                                    ]),
                                Section::make('Product Description')
                                    ->schema([
                                        Textarea::make('short_description')->default(null)->columnSpanFull(),
                                        RichEditor::make('description')->default(null)->columnSpanFull(),
                                    ]),
                            ]),
                        Tab::make('Pricing & Inventory')
                            ->icon(Heroicon::CurrencyDollar)
                            ->schema([
                                Section::make('Pricing')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('sku')
                                            ->label('SKU')->unique(ignoreRecord: true)
                                            ->helperText('Stock keeping Unit - unique identifier')
                                            ->required()
                                            ->default(fn() => 'SKU-' . strtoupper(Str::random(8))),
                                        TextInput::make('price')->required()->numeric()->minValue(0)->step(0.01)
                                            ->helperText('Selling Price')->prefix('$'),
                                        TextInput::make('compare_price')->numeric()->minValue(0)->step(0.01)
                                            ->helperText('Original Price to show discount')->prefix('$'),
                                        TextInput::make('cost_price')->numeric()->minValue(0)->step(0.01)
                                            ->helperText('Cost from Supplier (for profit calculation)')->prefix('$'),
                                    ]),
                                Section::make('Inventory')
                                    ->columns(2)
                                    ->schema([
                                        Toggle::make('manage_stock')->default(true)->live()->required(),
                                        TextInput::make('stock_quantity')
                                            ->required(fn($get) => $get('manage_stock'))
                                            ->disabled(fn($get) => !$get('manage_stock'))
                                            ->numeric()->default(0),
                                        TextInput::make('low_stock_threshold')
                                            ->required()->numeric()->minValue(0)->default(10),
                                        Select::make('stock_status')
                                            ->options([
                                                'in_stock' => 'In stock',
                                                'out_of_stock' => 'Out of stock',
                                                'on_back_order' => 'On back order',
                                            ])
                                            ->native(false)->default('in_stock')->required(),
                                        TextInput::make('weight')->numeric()->minValue(0)
                                            ->helperText('Used for shipping calculation')->default(null),
                                    ]),
                            ]),
                        // Tab::make('Images')
                        //     ->icon(Heroicon::Photo)
                        //     ->schema([
                        //         Section::make('Product Images')
                        //             ->description('Default product gallery. The first image will be the primary image. Use the Variants tab to add color-specific images.')
                        //             ->schema([
                        //                 FileUpload::make('images')
                        //                     ->label('Product Images')
                        //                     ->multiple()
                        //                     ->image()
                        //                     ->directory('products')
                        //                     ->disk('public')
                        //                     ->imageEditor()
                        //                     ->maxSize(2048)
                        //                     ->reorderable()
                        //                     ->columnSpanFull()
                        //                     ->helperText('You can drag and drop to reorder images.')
                        //                     ->saveRelationshipsUsing(function ($component, $state, $record) {
                        //                         $record->images()->delete();
                        //                         if (is_array($state)) {
                        //                             foreach ($state as $index => $imagePath) {
                        //                                 $record->images()->create([
                        //                                     'image_path' => $imagePath,
                        //                                     'is_primary' => $index === 0,
                        //                                     'sort_order' => $index,
                        //                                 ]);
                        //                             }
                        //                         }
                        //                     }),
                        //             ]),
                        //     ]),
                        Tab::make('Images')
                            ->icon(Heroicon::Photo)
                            ->schema([
                                Section::make('Product Images')
                                    ->description('Upload multiple images. the first image will be the primary image.')
                                    ->schema([
                                        FileUpload::make('images')
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
                                                // dd($state);

                                                if (is_array($state)) {
                                                    foreach (array_values($state ?? []) as $index => $imagePath) {
                                                        $record->images()->create([
                                                            'image_path' => $imagePath,
                                                            'sort_order' => $index,
                                                        ]);
                                                    }

                                                    $record->images()
                                                        ->orderBy('sort_order')
                                                        ->first()
                                                        ?->update(['is_primary' => true]);
                                                }
                                            }),
                                    ]),
                            ]),
                        Tab::make('Settings')
                            ->icon(Heroicon::Cog6Tooth)
                            ->schema([
                                Section::make('Product Status')
                                    ->schema([
                                        Toggle::make('is_active')->required(),
                                        Toggle::make('is_featured')->required(),
                                        TextInput::make('sort_order')->required()->numeric()->default(0),
                                    ]),
                                Section::make('Statistics')
                                    ->schema([
                                        TextEntry::make('views_count')
                                            ->label('Total Views')->badge()
                                            ->state(fn($record) => $record?->views_count ?? 0),
                                        TextEntry::make('created_at')
                                            ->label('Created At')->badge()
                                            ->state(fn($record) => $record?->created_at?->diffForHumans() ?? '-'),
                                    ]),
                            ]),

                        Tab::make('Variants (Size & Color)')
                            ->icon(Heroicon::Squares2x2)
                            ->schema([
                                Toggle::make('has_variants')->live()->required(),

                                Section::make()
                                    ->description('Enable if this product comes in multiple size/color combinations. Each row = one sellable variant.')
                                    ->visible(fn($get) => $get('has_variants'))
                                    ->schema([
                                        Repeater::make('variants')
                                            ->relationship()
                                            ->schema([
                                                Select::make('color_id')
                                                    ->label('color')
                                                    ->relationship('color', 'name')
                                                    ->searchable()->preload()->native(false)
                                                    ->native(false)
                                                    ->allowHtml()
                                                    ->getOptionLabelFromRecordUsing(function (Model $record) {

                                                        return '
                                                            <div style="display:flex;align-items:center;gap:8px;">
                                                                <span
                                                                    style="
                                                                        width:18px;
                                                                        height:18px;
                                                                        border-radius:9999px;
                                                                        background:' . $record->hex_code . ';
                                                                        border:1px solid #d1d5db;
                                                                        display:inline-block;
                                                                    ">
                                                                </span>

                                                                <span>' . e($record->name) . '</span>


                                                            </div>
                                                        ';
                                                    })
                                                    ->createOptionForm([
                                                        TextInput::make('name')->required(),
                                                        ColorPicker::make('hex_code')
                                                            ->label('Color')
                                                            ->helperText('Pick the color for this swatch')
                                                            ->required()
                                                            ->hex(),
                                                    ])
                                                    ->columnSpan(1),

                                                Select::make('size_id')
                                                    ->label('Size')
                                                    ->relationship('size', 'name')
                                                    ->searchable()->preload()->native(false)
                                                    ->createOptionForm([
                                                        TextInput::make('name')->required(),
                                                        TextInput::make('chest')->numeric()->step(0.1),
                                                        TextInput::make('length')->numeric()->step(0.1),
                                                    ])
                                                    ->columnSpan(1),

                                                TextInput::make('name')
                                                    ->label('Variant Label')
                                                    ->helperText('Auto-generated, e.g. "Red - Large"')
                                                    ->disabled()
                                                    ->dehydrated(false)
                                                    ->columnSpan(1),

                                                TextInput::make('sku')
                                                    ->label('SKU')
                                                    ->required()
                                                    ->default(fn() => 'VAR-' . strtoupper(Str::random(8)))
                                                    ->columnSpan(1),

                                                TextInput::make('price')
                                                    ->numeric()->required()->prefix('$')->step(0.01)
                                                    ->columnSpan(1),

                                                TextInput::make('compare_price')
                                                    ->numeric()->prefix('$')->step(0.01)
                                                    ->columnSpan(1),

                                                TextInput::make('stock_quantity')
                                                    ->numeric()->default(0)
                                                    ->columnSpan(1),

                                                Select::make('stock_status')
                                                    ->options([
                                                        'in_stock' => 'In stock',
                                                        'out_of_stock' => 'Out of stock',
                                                        'on_back_order' => 'On back order',
                                                    ])
                                                    ->native(false)->default('in_stock')->required()
                                                    ->columnSpan(1),

                                                FileUpload::make('image_path')
                                                    ->label('Variant Image')
                                                    ->image()
                                                    ->directory('variants')
                                                    ->disk('public')
                                                    ->imageEditor()
                                                    ->helperText('Color-specific photo. Overrides product gallery when this variant is selected.')
                                                    ->columnSpan(1),

                                                Toggle::make('is_active')
                                                    ->default(true)
                                                    ->inline(false)
                                                    ->columnSpan(1),
                                            ])
                                            ->columns(3)
                                            ->collapsible()
                                            ->itemLabel(fn($state) => $state['name'] ?? 'New Variant')
                                            ->addActionLabel('Add Variant')
                                            ->reorderable()
                                            ->defaultItems(0),
                                    ]),
                            ]),

                        Tab::make('SEO')
                            ->icon(Heroicon::MagnifyingGlass)
                            ->schema([
                                Section::make('Search Engine Optimization')
                                    ->schema([
                                        TextInput::make('meta_title')->default(null),
                                        Textarea::make('meta_description')->default(null)->columnSpanFull(),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}
