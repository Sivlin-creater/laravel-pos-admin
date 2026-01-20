<?php

namespace App\Livewire\Items;

use App\Models\Inventory;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class InventoryHistory extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithTable;
    use InteractsWithSchemas;

    public function table(Table $table): Table {
        return $table
            ->query(fn (): Builder => Inventory::onlyTrashed()->with('item'))
            ->columns([
                TextColumn::make('id')->label('#')->sortable(),

                TextColumn::make('item.name')
                    ->label('Item Name')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('quantity')
                    ->label('Stock')
                    ->badge()
                    ->color(fn ($state) => $state < 5 ? 'danger' : 'success')
                    ->icon(fn ($state) => $state < 5 ? 'heroicon-o-exclamation-triangle' : 'heroicon-o-check-circle'),

                TextColumn::make('deleted_at')
                    ->label('Deleted At')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->defaultSort('deleted_at', 'desc')

            ->recordActions([
                Action::make('restore')
                    ->label('Restore')
                    ->color('success')
                    ->icon('heroicon-o-arrow-path')
                    ->action(fn (Inventory $record) => $record->restore())
                    ->successNotification(
                        Notification::make()
                            ->title('Inventory Restored')
                            ->success()
                    ),

                Action::make('forceDelete')
                    ->label('Delete Permanently')
                    ->color('danger')
                    ->icon('heroicon-o-trash')
                    ->requiresConfirmation()
                    ->modalHeading('Delete Inventory Permanently')
                    ->modalDescription('This action cannot be undone.')
                    ->action(fn (Inventory $record) => $record->forceDelete())
                    ->successNotification(
                        Notification::make()
                            ->title('Inventory Permanently Deleted')
                            ->success()
                    ),
            ])

            ->filters([
                TrashedFilter::make(), 
                Filter::make('date')
                    ->form([
                        DatePicker::make('from'),
                        DatePicker::make('to'),
                    ])
                    ->query(function ($query, $data) {
                        if (!empty($data['from']) && !empty($data['to'])) {
                            $query->whereBetween('deleted_at', [$data['from'], $data['to']]);
                        } elseif (!empty($data['from'])) {
                            $query->where('deleted_at', '>=', $data['from']);
                        } elseif (!empty($data['to'])) {
                            $query->where('deleted_at', '<=', $data['to']);
                        }
                    })
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    Action::make('restore')
                        ->action(fn ($records) => $records->each->restore())
                        ->label('Restore Selected')
                        ->color('success')
                        ->successNotification(fn () => Notification::make()->title('Selected Restored')->success()),
                    Action::make('forceDelete')
                        ->action(fn ($records) => $records->each->forceDelete())
                        ->label('Delete Selected')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->successNotification(fn () => Notification::make()->title('Selected Deleted')->success()),
                ]),
            ]);
    }

    public function render()
    {
        return view('livewire.items.inventory-history');
    }
}
