<?php

namespace App\Livewire;

use Filament\Widgets\TableWidget;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\BulkActionGroup;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Sale;
use App\Services\ExchangeRateService;

class LatestSales extends TableWidget
{

    public function table(Table $table): Table
    {
        $rate = app(ExchangeRateService::class)->getUsdToKhrRate();

        return $table
            ->query(fn (): Builder => Sale::with(['customer', 'paymentMethod'])->withCount('salesItems')->latest())
            ->columns([
                TextColumn::make('id')->label('#')->sortable(),
                TextColumn::make('customer.name')->label('Customer')->sortable(),
                TextColumn::make('paymentMethod.name')->label('Payment Method')->sortable(),
                TextColumn::make('items_count')
                    ->label('Items')
                    ->getStateUsing(fn ($record) => $record->salesItems->count())
                    ->sortable()
                    ->tooltip(fn ($record) => $record->salesItems->pluck('product_name')->join(', ')),
                TextColumn::make('total')
                    ->label('Total')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => '$' . number_format($state, 2) . ' / ' . number_format($state * $rate, 0) . '៛'),
                TextColumn::make('paid_amount')
                    ->label('Paid')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => '$' . number_format($state, 2) . ' / ' . number_format($state * $rate, 0) . '៛'),
                TextColumn::make('remaining_amount')
                    ->label('Remaining')
                    ->getStateUsing(fn ($record) => $record->total - $record->paid_amount)
                    ->formatStateUsing(fn ($state) => '$' . number_format($state, 2) . ' / ' . number_format($state * $rate, 0) . '៛')
                    ->color(fn ($state) => $state > 0 ? 'warning' : 'success'),
                TextColumn::make('created_at')
                    ->label('Date')
                    ->sortable()
                    ->dateTime('d M Y H:i'),
            ])
            
            ->filters([
                // Add filters later if needed
            ])
            ->headerActions([
                // Add actions later
            ])
            ->recordActions([
                // Add actions later
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // Add bulk actions later
                ]),
            ]);
    }
}
