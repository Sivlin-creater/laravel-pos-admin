<?php

namespace App\Livewire\Management;

use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use App\Models\PaymentMethod;

class ListPaymentMethods extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithTable;
    use InteractsWithSchemas;

    public function mount() {
        $user = auth()->user();

        //Manual role check
        if(!$user->isAdmin() && !$user->isManager()) {
            abort(403, 'You do not have permission to access this page.');
        }
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => PaymentMethod::query())
            ->columns([
                TextColumn::make('id')->label('#')->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('name')->label('Payment Method')->searchable()->sortable()->toggleable()->weight('medium'),
                TextColumn::make('description')->label('Description')->limit(50)->toggleable(),
                TextColumn::make('created_at')->label('Created At')->dateTime('d M Y H:i')->sortable(),
            ])

            ->filters([
                SelectFilter::make('name')
                    ->label('Payment Method Name')
                    ->options(PaymentMethod::pluck('name', 'id')->toArray()),

                Filter::make('created_at')
                    ->form([
                        DatePicker::make('from')->label('From'),
                        DatePicker::make('until')->label('To'),
                    ])
                    ->query(fn ($query, array $data) => $query
                        ->when($data['from'] ?? null, fn($q, $from) => $q->whereDate('created_at', '>=', $from))
                        ->when($data['until'] ?? null, fn($q, $until) => $q->whereDate('created_at', '<=', $until))
                    ),
            ])

            ->headerActions([
                //
            ])

            ->recordActions([
                Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil')
                    ->color('primary')
                    ->form(fn (PaymentMethod $record) => [
                        TextInput::make('name')->label('Payment Method')->default($record->name)->required(),
                        TextInput::make('description')->label('Description')->default($record->description),
                    ])
                    ->action(fn (PaymentMethod $record, array $data) => $record->update($data))
                    ->successNotification(
                        Notification::make()
                            ->title('Payment Method Updated')
                            ->body('The payment method was updated successfully.')
                            ->success()
                    ),

                Action::make('delete')
                    ->label('Delete')
                    ->icon('heroicon-o-trash')
                    ->requiresConfirmation()
                    ->color('danger')
                    ->action(fn (PaymentMethod $record) => $record->delete())
                    ->successNotification(
                        Notification::make()
                            ->title('Payment Method deleted successfully.')
                            ->success()
                    ),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    Action::make('delete_selected')
                        ->label('Delete Selected')
                        ->icon('heroicon-o-trash')
                        ->requiresConfirmation()
                        ->action(fn ($record) => $record->each->delete())
                        ->successNotification(
                            Notification::make()
                                ->title('Delete Successfully')
                                ->success()
                        ),
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.management.list-payment-methods');
    }
}
