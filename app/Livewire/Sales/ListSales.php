<?php

namespace App\Livewire\Sales;

use App\Models\Customer;
use App\Models\PaymentMethod;
use App\Exports\SalesExport;
use App\Services\ExchangeRateService;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\BulkActionGroup;
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
use Filament\Actions\Action;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use App\Models\Sale;

class ListSales extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithTable;
    use InteractsWithSchemas;

    public ?Sale $modalSale = null;

    public function table(Table $table): Table
    {
        $rate = app(ExchangeRateService::class)->getUsdToKhrRate();

        return $table
            ->query(fn (): Builder => Sale::with(['customer', 'paymentmethod'])->withCount('salesItems')->withTrashed())
            ->columns([
                TextColumn::make('id')->label('#')->searchable()->sortable(),
                TextColumn::make('customer.name')->label('Customer')->searchable()->sortable(),
                TextColumn::make('paymentmethod.name')->label('Payment Method')->searchable()->sortable(),
                TextColumn::make('sales_items_count')->label('Saled Items')->sortable(),
                TextColumn::make('total')->label('Total')->sortable()
                    ->formatStateUsing(fn ($state) => '$ ' . number_format($state, 2) . ' / ' . number_format($state * $rate, 0) . '៛')
                    ->tooltip(fn ($record) => 'Total sale amount'),
                TextColumn::make('paid_amount')->label('Paid Amount')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => '$ ' . number_format($state, 2) . ' / ' . number_format($state * $rate, 0) . '៛')
                    ->tooltip(fn ($record) => 'Amount paid so far'),
                TextColumn::make('remaining_amount')
                    ->label('Remaining')
                    ->getStateUsing(fn ($record) => $record->total - $record->paid_amount) // calculate remaining
                    ->formatStateUsing(fn ($state) => '$ ' . number_format($state, 2) . ' / ' . number_format($state * $rate, 0) . '៛')
                    ->color(fn ($state) => $state > 0 ? 'warning' : 'success')
                    ->tooltip(fn ($state) => 'Remaining balance'),
                BadgeColumn::make('payment_status')
                    ->label('Status')
                    ->getStateUsing(fn ($record) => $record->total <= $record->paid_amount ? 'Paid' : ($record->paid_amount > 0 ? 'Partial' : 'Pending'))
                    ->colors([
                        'success' => 'Paid',
                        'warning' => 'Partial',
                        'danger' => 'Pending',
                    ])
                    ->sortable(),
                TextColumn::make('discount')->label('Discount')->sortable()->formatStateUsing(fn ($state) => '$ ' . number_format($state, 2) . ' / ' . number_format($state * $rate, 0) . '៛'),
                TextColumn::make('created_at')->label('Date')->sortable()->searchable(),
                TextColumn::make('deleted_at')->label('Deleted At')->sortable()->searchable()->dateTime('d M Y H:i')->toggleable(isToggledHiddenByDefault: true),
            ])

            ->filters([
                SelectFilter::make('customer')->relationship('customer', 'name')->label('Customer'),
                SelectFilter::make('paymentmethod')->relationship('paymentmethod', 'name')->label('Payment Method'),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('from')->label('From'),
                        DatePicker::make('until')->label('To'),
                    ])
                    ->query(fn ($query, array $data) => $query
                        ->when($data['from'] ?? null, fn ($q, $from) => $q->whereDate('created_at', '>=', $from))
                        ->when($data['until'] ?? null, fn ($q, $until) => $q->whereDate('created_at', '<=', $until))
                    ),
                SelectFilter::make('customer_id')
                    ->label('Customer')
                    ->options(Customer::pluck('name', 'id')->toArray()),
                SelectFilter::make('payment_method_id')
                    ->label('Payment Method')
                    ->options(PaymentMethod::pluck('name', 'id')->toArray()),
                SelectFilter::make('payment_status')
                    ->label('Payment Status')
                    ->options([
                        'paid' => 'Paid',
                        'partial' => 'Partial',
                        'pending' => 'Pending',
                    ])
                    ->query(fn ($query, $value) => $query->when($value === 'paid', fn ($q) => $q->whereColumn('paid_amount', '>=', 'total'))
                        ->when($value === 'partial', fn ($q) => $q->where('paid_amount', '>', 0)->whereColumn('paid_amount', '<', 'total'))
                        ->when($value === 'pending', fn ($q) => $q->where('paid_amount', 0))
                    ),
            ])

            ->headerActions([
                //Export Excel
                Action::make('export_excel')
                    ->label('Export Excel')
                    ->icon('heroicon-o-document-arrow-down')
                    ->iconButton()
                    ->color('success')
                    ->action(fn () => Excel::download(new SalesExport, 'sales.xlsx')),

                // CSV Export
                Action::make('export_csv')
                    ->label('Export CSV')
                    ->icon('heroicon-o-document-text')
                    ->iconButton()
                    ->action(fn () => Excel::download(new SalesExport, 'sales.csv')),

                // PDF Export
                Action::make('export_pdf')
                    ->label('Export PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->iconButton()
                    ->action(function () {
                        $sales = Sale::with(['customer', 'paymentmethod', 'salesItems'])->get();
                        $pdf = Pdf::loadView('livewire.sales.sales-pdf', compact('sales'))
                            ->setPaper('A4', 'landscape');
                        return response()->streamDownload(fn () => print($pdf->output()), 'sales.pdf');
                    }),
            ])

            ->recordActions([
                //Only Admin Manager can
                Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil')
                    ->color('primary')
                    ->visible(fn (Sale $record) => 
                        ! auth()->user()->isCashier() &&
                        is_null($record->deleted_at))
                    ->form(fn (Sale $record) => [
                        Select::make('customer_id')->relationship('customer', 'name')->required()->default($record->customer_id),
                        Select::make('paymentmethod_id')->relationship('paymentmethod', 'name')->required()->default($record->paymentmethod_id),
                        TextInput::make('total')->numeric()->minValue(0)->default($record->total),
                        TextInput::make('paid_amount')->numeric()->minValue(0)->default($record->paid_amount),
                        TextInput::make('discount')->numeric()->minValue(0)->default($record->discount),
                    ])
                    ->action(fn (Sale $record, array $data) => $record->update($data))
                    ->successNotification(Notification::make()->title('Sale Updated')->success()),

                Action::make('delete')
                    ->label('Delete')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (Sale $record) => 
                        ! auth()->user()->isCashier() &&
                        is_null($record->deleted_at))
                    ->action(fn (Sale $record) => $record->delete())
                    ->successNotification(
                        Notification::make()
                            ->title('Sale Deleted')
                            ->success()
                    ),

                Action::make('view')
                    ->label('View Details')
                    ->icon('heroicon-o-eye')
                    ->color('primary')
                    ->modalHeading(fn ($record) => "Sale #{$record->id} Details")
                    ->form(function (Sale $record) {
                        $record->load(['customer', 'paymentMethod', 'salesItems.item']);

                        return [
                            TextInput::make('customer')->label('Customer')
                                ->default($record->customer?->name ?? 'N/A')
                                ->disabled(),

                            TextInput::make('payment_method')->label('Payment Method')
                                ->default($record->paymentMethod?->name ?? 'N/A')
                                ->disabled(),

                            TextInput::make('items')->label('Items')
                                ->default(
                                    $record->salesItems
                                        ->map(fn ($i) => $i->item?->name ?? 'N/A')
                                        ->join(', ')
                                )
                                ->disabled(),

                            TextInput::make('total')->label('Total')
                                ->default(number_format($record->total, 2))
                                ->disabled(),

                            TextInput::make('paid_amount')->label('Paid Amount')
                                ->default(number_format($record->paid_amount, 2))
                                ->disabled(),

                            TextInput::make('remaining')->label('Remaining')
                                ->default(number_format($record->total - $record->paid_amount, 2))
                                ->disabled(),

                            TextInput::make('discount')->label('Discount')
                                ->default(number_format($record->discount, 2))
                                ->disabled(),

                            TextInput::make('status')->label('Status')
                                ->default(
                                    $record->total <= $record->paid_amount
                                        ? 'Paid'
                                        : ($record->paid_amount > 0 ? 'Partial' : 'Pending')
                                )
                                ->disabled(),

                            TextInput::make('date')->label('Date')
                                ->default($record->created_at->format('d M Y H:i'))
                                ->disabled(),
                        ];
                    })
                    ->action(null)
                    ->modalFooterActions([]),

    

                //Admin Manager Only
                Action::make('restore')
                    ->label('Restore')
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->visible(fn (Sale $record) => 
                        ! auth()->user()->isCashier() &&
                        !is_null($record->deleted_at))
                    ->action(fn (Sale $record) => $record->restore())
                    ->successNotification(fn () =>
                        Notification::make()
                            ->title('Sale Restored')
                            ->success()
                    ),

                Action::make('forceDelete')
                    ->label('Delete Permanently')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->visible(fn (Sale $record) => 
                        ! auth()->user()->isCashier() &&
                        !is_null($record->deleted_at))
                    ->requiresConfirmation()
                    ->modalHeading('Delete Sale Permanently')
                    ->modalDescription('This action cannot be undone.')
                    ->action(fn (Sale $record) => $record->forceDelete())
                    ->successNotification(fn () =>
                        Notification::make()
                            ->title('Sale Permanently Deleted')
                            ->success()
                    ),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    Action::make('bulkDelete')
                        ->label('Delete Selected')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->forceDelete())
                        ->successNotification(Notification::make()
                            ->title('Sales Deleted')
                            ->success()),

                    Action::make('export_selected_excel')
                        ->label('Export Selected (Excel)')
                        ->action(fn ($records) =>Excel::download(new SalesExport($records), 'selected_sales.xlsx')),
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.sales.list-sales');
    }
}
