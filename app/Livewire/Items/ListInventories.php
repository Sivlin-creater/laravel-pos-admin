<?php

namespace App\Livewire\Items;

use App\Models\Inventory;
use App\Exports\InventoriesExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class ListInventories extends Component implements HasActions, HasSchemas, HasTable
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
            ->heading('Inventory stores how much stock is available for an item')
            ->query(fn (): Builder => Inventory::query())
            ->columns([
                TextColumn::make('id')->label('#')->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('item.name')->label('Item Name')->searchable()->sortable()
                    ->weight('medium'),
                TextColumn::make('quantity')->label('Stock')->sortable()
                    ->badge()
                    ->color(fn (Inventory $record) => match($record->stock_status) {
                        'Out of Stock' => 'danger',
                        'Low Stock' => 'warning',
                        'In Stock' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')->label('Added On')->dateTime('d M Y, H:i')->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')->label('Last Updated')->dateTime('d M Y, H:i')->sortable()
            ])

            ->filters([
                SelectFilter::make('stock_status')
                    ->options([
                        'in' => 'In Stock',
                        'low' => 'Low Stock',
                        'out' => 'Out of Stock',
                    ])
                    ->query(fn ($query, $value) => match($value) {
                        'in' => $query->where('quantity', '>', 10),
                        'low' => $query->whereBetween('quantity', [1, 10]),
                        'out' => $query->where('quantity', 0),
                        default => $query,
                    }),
                Filter::make('created_at')->label('Added Between')->form([
                    DatePicker::make('created_from')->label('From'),
                    DatePicker::make('created_until')->label('To'),
                ])->query(fn ($query, $data) => $query
                    ->when($data['created_from'], fn($q) => $q->whereDate('created_at', '>=', $data['created_from']))
                    ->when($data['created_until'], fn($q) => $q->whereDate('created_at', '<=', $data['created_until']))
                ),

                Filter::make('updated_at')->label('Updated Between')->form([
                    DatePicker::make('updated_from')->label('From'),
                    DatePicker::make('updated_until')->label('To'),
                ])->query(fn ($query, $data) => $query
                    ->when($data['updated_from'], fn($q) => $q->whereDate('updated_at', '>=', $data['updated_from']))
                    ->when($data['updated_until'], fn($q) => $q->whereDate('updated_at', '<=', $data['updated_until']))
                ),
            ])

            ->headerActions([
                Action::make('create')->label('Add Inventory')->color('success')
                    ->icon('heroicon-o-plus')
                    ->form([
                        Select::make('item_id')->relationship('item', 'name')->required(),
                        TextInput::make('quantity')->numeric()->minValue(0)->required(),
                    ])
                    ->action(fn ($data) => Inventory::create($data))
                    ->successNotification(
                            Notification::make()
                            ->title('Inventory Added')
                            ->success()),

                Action::make('export_excel')
                    ->tooltip('Export Excel')
                    ->icon('heroicon-o-document-arrow-down')
                    ->iconButton()
                    ->color('success')
                    ->action(fn () =>
                        Excel::download(new InventoriesExport, 'inventories.xlsx')
                    ),

                Action::make('export_csv')
                    ->tooltip('Export CSV')
                    ->icon('heroicon-o-document-text')
                    ->iconButton()
                    ->action(fn () =>
                      Excel::download(new InventoriesExport, 'inventories.csv')  
                    ),

                Action::make('export_pdf')
                    ->tooltip('Export PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->iconButton()
                    ->action(function () {
                        $inventories = Inventory::with('item')->get();
                        $pdf = Pdf::loadView('livewire.items.inventories-pdf', compact('inventories'));

                        return response()->streamDownload(
                            fn() => print($pdf->output()),
                            'inventories.pdf'
                        );
                    }),
            ])

            ->recordActions([
                Action::make('edit')->label('Edit')->icon('heroicon-o-pencil')->color('primary')
                    ->form(fn (Inventory $record) => [
                        TextInput::make('quantity')
                            ->label('Stock Quantity')
                            ->numeric()->minValue(0)
                            ->rule('integer')
                            ->default($record->quantity)
                            ->required(),
                    ])
                    ->action(fn (Inventory $record, array $data) => $record->update($data))
                    ->successNotification(
                        Notification::make()
                            ->title('Inventory Updated')
                            ->body('Stock quantity updated successfully.')
                            ->success()
                    ),

                Action::make('delete')
                    ->requiresConfirmation()
                    ->color('danger')
                    ->action(fn (Inventory $record) => $record->delete())
                    ->successNotification(
                        Notification::make()
                            ->title('Deleted Successfully')
                            ->success()
                    ),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    Action::make('bulkDelete')
                        ->label('Delete Selected')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->delete())
                        ->successNotification(Notification::make()->title('Deleted Successfully')->success()),
                    
                    Action::make('export_selected_excel')
                        ->label('Export Selected (Excel)')
                        ->action(function ($records) {
                            return Excel::download(
                                new InventoriesExport($records),
                                'selected_inventories.xlsx'
                            );
                        }),
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.items.list-inventories');
    }
}
