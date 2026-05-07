<?php

namespace App\Filament\Resources\Coupons\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CouponsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->copyable()
                    ->weight('bold')
                    ->searchable(),
                TextColumn::make('type')
                    ->badge(),
                TextColumn::make('value')
                    ->label('Discount')
                    ->weight('bold')
                    ->formatStateUsing(fn($record) => $record->type === 'percentage' ? $record->value . '%' : '$' . number_format($record->value, 2))
                    // ->numeric()
                    ->sortable(),
                TextColumn::make('minimum_order_value')
                    ->label('Min. Order')
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('maximum_discount')
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('usage_limit')
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('usage_count')
                    ->label('Used')
                    ->color('warning')
                    ->counts('usages')
                    ->sortable(),
                TextColumn::make('usage_limit_per_customer')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('starts_at')
                    ->label('Active Now')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('expires_at')
                    ->dateTime()
                    ->color(fn($state) => $state->isPast() ? 'danger' : 'success')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'fixed' => 'Fixed',
                        'percentage' => 'Percentage'
                    ])
                    ->native(false),
                TernaryFilter::make('is_active')
                ->label('Status')
                ->trueLabel('Active Only')
                ->falseLabel('Inactive Only')
                ->native(false)
                ->boolean(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
