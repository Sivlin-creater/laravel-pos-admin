<?php

namespace App\Livewire\Items;

use App\Models\Item;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class ItemHistory extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithTable;
    use InteractsWithSchemas;

    protected $listeners = [
        'itemDeleted' => '$refresh',
        'itemRestored' => '$refresh',
    ];

    public function table(Table $table): Table {
        return $table
            ->query(fn (): Builder => Item::onlyTrashed())
            ->columns([
                TextColumn::make('id')->label('#')->sortable(),
                TextColumn::make('name')->label('Item Name')->searchable(),
                TextColumn::make('sku')->label('SKU')->searchable(),
                TextColumn::make('price')
                    ->label('Price')
                    ->formatStateUsing(fn ($state) => '$ ' . number_format($state, 2)),
                TextColumn::make('deleted_at')
                    ->label('Deleted At')
                    ->dateTime(),
            ])
            ->defaultSort('deleted_at', 'desc')
            ->recordActions([
                Action::make('restore')
                    ->label('Restore')
                    ->color('success')
                    ->icon('heroicon-o-arrow-path')
                    ->action(fn (Item $record) => $record->restore())
                    ->successNotification(
                        Notification::make()
                            ->title('Item Restored')
                            ->success()
                    ),

                Action::make('delete_permanently')
                    ->label('Delete Permanently')
                    ->color('danger')
                    ->icon('heroicon-o-trash')
                    ->requiresConfirmation()
                    ->modalHeading('Delete Permanently')
                    ->modalDescription('This action cannot be undone.')
                    ->action(fn (Item $record) => $record->forceDelete())
                    ->successNotification(
                        Notification::make()
                            ->title('Item Permanently Deleted')
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
                    ->query(function ($query, $data){
                        if (!empty($data['from']) && !empty($data['to'])) {
                            $query->whereBetween('deleted_at', [$data['from'], $data['to']]);
                        } elseif (!empty($data['from'])) {
                            $query->where('deleted_at', '>=', $data['from']);
                        } elseif (!empty($data['to'])) {
                            $query->where('deleted_at', '<=', $data['to']);
                        }
                    }),
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
        return view('livewire.items.item-history');
    }
}
