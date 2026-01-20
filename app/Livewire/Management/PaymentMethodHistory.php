<?php

namespace App\Livewire\Management;

use App\Models\PaymentMethod;
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

class PaymentMethodHistory extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithTable;
    use InteractsWithSchemas;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => PaymentMethod::onlyTrashed())
            ->columns([
                TextColumn::make('id')->label('#')->sortable(),

                TextColumn::make('name')
                    ->label('Payment Method')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('deleted_at')
                    ->label('Deleted At')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            
            ->defaultSort('deleted_at', 'desc')
            ->recordActions([
                Action::make('restore')
                    ->label('Restore')
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->action(fn (PaymentMethod $record) => $record->restore())
                    ->successNotification(
                        Notification::make()
                            ->title('Payment Method Restored')
                            ->success()
                    ),

                Action::make('forceDelete')
                    ->label('Delete Permanently')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Delete Permanently')
                    ->modalDescription('This action cannot be undone.')
                    ->action(fn (PaymentMethod $record) => $record->forceDelete())
                    ->successNotification(
                        Notification::make()
                            ->title('Payment Method Permanently Deleted')
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
        return view('livewire.management.payment-method-history');
    }
}
