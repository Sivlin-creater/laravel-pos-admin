<?php

namespace App\Livewire\Items;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use App\Models\Item;
use App\Models\Inventory;
use Livewire\Component;
use App\Exports\ItemsExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\ExchangeRateService;

class ListItems extends Component implements HasActions, HasSchemas, HasTable
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
        $rate = app(ExchangeRateService::class)->getUsdToKhrRate();

        return $table
            ->heading('Product catalog- describes what the item is')
            ->query(fn (): Builder => Item::query()->with('inventory'))
            ->columns([
                TextColumn::make('id')->label('#')->sortable()->searchable(),
                ImageColumn::make('image')
                    ->label('Image')
                    ->disk('public')
                    ->height(50)
                    ->circular()
                    ->defaultImageUrl(asset('images/no-image.jpg')),

                TextColumn::make('name')->label('Item Name')->sortable()->searchable(),
                TextColumn::make('sku')->label('SKU')->sortable()->searchable(),
                TextColumn::make('original_price')
                    ->label('Original Price')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn ($state) => '$' . number_format($state, 2) . ' / ' . number_format($state * $rate, 0) . '៛'),
                TextColumn::make('selling_price')
                    ->label('Selling Price')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn ($state) => '$' . number_format($state, 2) . ' / ' . number_format($state * $rate, 0) . '៛'),
                BadgeColumn::make('stock_status')
                    ->label('Stock Status')
                    ->getStateUsing(fn ($record) => $record->stock_status)
                    ->colors([
                        'success' => 'In Stock',
                        'warning' => 'Low Stock',
                        'danger' => 'Out of Stock',
                    ])
                    ->tooltip(fn ($record) => 'Quantity: '.($record->inventory?->quantity ?? 0))
                    ->sortable(),
            ])
            
            ->filters([
                SelectFilter::make('stock')
                    ->label('Stock Status')
                    ->options([
                        'in_stock' => 'In Stock',
                        'low_stock' => 'Low Stock',
                        'out_of_stock' => 'Out of Stock',
                    ])
                    ->query(function (Builder $query, $value) {
                        if ($value === 'in_stock') {
                            $query->whereHas('inventory', fn($q) => $q->where('quantity', '>', 10));
                        } elseif ($value === 'low_stock') {
                            $query->whereHas('inventory', fn($q) => $q->whereBetween('quantity', [1, 10]));
                        } elseif ($value === 'out_of_stock') {
                            $query->whereHas('inventory', fn($q) => $q->where('quantity', 0));
                        }
                    }),
                
                Filter::make('created_at')
                    ->label('Created Between')
                    ->form([
                        DatePicker::make('created_from')->label('From'),
                        DatePicker::make('created_until')->label('To'),
                    ])
                    ->query(fn ($query, array $data) => $query
                        ->when($data['created_from'], fn($q) => $q->whereDate('created_at', '>=', $data['created_from']))
                        ->when($data['created_until'], fn($q) => $q->whereDate('created_at', '<=', $data['created_until']))
                    ),

                Filter::make('updated_at')
                    ->label('Updated Between')
                    ->form([
                        DatePicker::make('updated_from')->label('From'),
                        DatePicker::make('updated_until')->label('To'),
                    ])
                    ->query(fn ($query, array $data) => $query
                        ->when($data['updated_from'], fn($q) => $q->whereDate('updated_at', '>=', $data['updated_from']))
                        ->when($data['updated_until'], fn($q) => $q->whereDate('updated_at', '<=', $data['updated_until']))
                    ),
            ])

            ->headerActions([
                Action::make('create')
                    ->label('Add Item')
                    ->icon('heroicon-o-plus')
                    ->color('success')
                    ->form([
                        FileUpload::make('image')
                            ->label('Item Image')
                            ->image()
                            ->disk('public')
                            ->directory('items')
                            ->imagePreviewHeight(150)
                            ->maxSize(2048)
                            ->required(),

                        TextInput::make('name')->required(),
                        TextInput::make('sku')->required(),

                        TextInput::make('original_price')
                            ->label('Original Price')
                            ->numeric()->minValue(0)->required(),
                        TextInput::make('selling_price')
                            ->label('Selling Price')
                            ->numeric()->minValue(0)->required(),
                        Select::make('status')->options(['active'=>'Active','inactive'=>'Inactive'])->required(),
                        TextInput::make('initial_stock')->label('Initial Stock')
                            ->numeric()
                            ->minValue(0)->required(),
                    ])
                    ->action(function ($data) {
                        // Create Item
                        $item = Item::create([
                            'name' => $data['name'],
                            'sku' => $data['sku'],
                            'image' => $data['image'] ?? null,
                            'original_price' => $data['original_price'],
                            'selling_price' => $data['selling_price'],
                            'status' => $data['status'],
                        ]);

                        // Create Inventory row automatically
                        Inventory::create([
                            'item_id' => $item->id,
                            'quantity' => $data['initial_stock'],
                        ]);
                    })
                    ->successNotification(Notification::make()->title('Item Added')->success()
                    ),

                    Action::make('export_excel')
                        ->tooltip('Export Excel')
                        ->icon('heroicon-o-document-arrow-down')
                        ->iconButton()
                        ->color('success')
                        ->action(fn () =>
                            Excel::download(new ItemsExport, 'items.xlsx')
                        ),

                    Action::make('export_csv')
                        ->tooltip('Export CSV')
                        ->icon('heroicon-o-document-text')
                        ->iconButton()
                        ->action(fn () =>
                            Excel::download(new ItemsExport, 'items.csv')
                        ),

                    Action::make('export_pdf')
                        ->tooltip('Export PDF')
                        ->icon('heroicon-o-document-arrow-down')
                        ->iconButton()
                        ->action(function () {
                            $items = Item::with('inventory')->get();

                            $pdf = Pdf::loadView('livewire.items.items-pdf', compact('items'));

                            return response()->streamDownload(
                                fn () => print($pdf->output()),
                                'items.pdf'
                            );
                        }),
            ])

            ->recordActions([
                Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil')
                    ->color('primary')
                    ->form(fn (Item $record) => [
                        FileUpload::make('image')->image()->disk('public')->directory('items'),
                        TextInput::make('name')->label('Item Name')->default($record->name)->maxLength(255),
                        TextInput::make('sku')->label('SKU')->default($record->sku)->maxLength(100),
                        TextInput::make('original_price')->label('Original Price')->default($record->original_price)->numeric()->minValue(0)->step(0.01),
                        TextInput::make('selling_price')->label('Selling Price')->default($record->selling_price)->numeric()->minValue(0)->step(0.01),
                        Select::make('status')->label('Status')->default($record->status)->options([
                            'active' => 'Active',
                            'inactive' => 'Inactive',
                        ]),
                    ])

                    ->action(fn (Item $record, array $data) => $record->update($data))
                    ->successNotification(
                        Notification::make()
                            ->title('Item Updated')
                            ->body('Item details updated successfully.')
                            ->success()
                    ),

                    Action::make('delete')
                    ->requiresConfirmation()
                    ->color('danger')
                    ->action(fn (Item $record) => $record->delete())
                    ->successNotification(
                        Notification::make()
                            ->title('Item Deleted')
                            ->body('The item has been successfully deleted.')
                            ->success()
                    ),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    Action::make('delete_selected')
                        ->label('Delete Selected')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->delete())
                        ->successNotification(Notification::make()->title('Deleted Successfully')->success()),

                    Action::make('exported_selected_excel')
                        ->label('Export Selected (Excel)')
                        ->action(function ($records) {
                            return Excel::download(
                                new ItemsExport($records),
                                'selected_items.xlsx'
                            );
                        }),
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.items.list-items');
    }
}
