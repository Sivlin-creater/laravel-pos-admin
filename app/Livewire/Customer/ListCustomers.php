<?php

namespace App\Livewire\Customer;

use App\Models\Customer;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use App\Exports\CustomersExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Select;
use Livewire\Component;

class ListCustomers extends Component implements HasActions, HasSchemas, HasTable
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
            ->query(fn (): Builder => Customer::query())
            ->columns([
                TextColumn::make('id')->label('#')->sortable()->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('name')->label('Name')->searchable()->sortable()->weight('medium'),
                TextColumn::make('email')->label('Email')->searchable()->sortable()->icon('heroicon-o-envelope'),
                TextColumn::make('phone_display')->label('Phone Number')->searchable()->icon('heroicon-o-phone')
                    ->getStateUsing(fn ($record) =>
                        trim(($record->phone_country_code ?? '') . ' ' . ($record->phone_number ?? ''))
                    ),
                TextColumn::make('status')->badge()->searchable()->sortable()->color(fn ($state) => $state === 'active' ? 'success' : 'danger'),
                TextColumn::make('created_at')
                    ->label('Registered On')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])

            ->filters([
                Filter::make('created_at')
                    ->label('Registered Between')
                    ->form([
                        DatePicker::make('created_from')->label('From'),
                        DatePicker::make('created_until')->label('To'),
                    ])
                    ->query(function ($query, array $data) {
                        if (!empty($data['created_from'])) {
                            $query->whereDate('created_at', '>=', $data['created_from']);
                        }
                        if (!empty($data['created_until'])) {
                            $query->whereDate('created_at', '<=', $data['created_until']);
                        }
                        return $query;
                    }),
            ])

            ->headerActions([
                Action::make('add')->label('Add Customer')->icon('heroicon-o-plus')->color('success')
                    ->form([
                        TextInput::make('name')->label('Name')->required(),
                        TextInput::make('email')->label('Email')->email()->required(),
                        Select::make('phone_country_code')->label('Country Code')
                            ->options([
                                '+855' => 'KH Cambodia (+855)',
                                '+1' => 'US USA (+1)',
                                '+84' => 'VN Vietnam (+84)',
                                '+88' => '￥ China (+88)',
                            ])
                            ->default('+855')
                            ->required(),
                        TextInput::make('phone_number')->label('Phone Number')
                            ->tel()
                            ->numeric()
                            ->required()
                            ->placeholder('97 293 439'),
                    ])
                    ->action(fn ($data) => Customer::create($data))
                    ->successNotification(
                        Notification::make()
                            ->title('Customer Added')
                            ->body('New Customer has been added.')
                            ->success()
                    ),

                //Export Buttons
                Action::make('export_excel')
                    ->tooltip('Export Excel')
                    ->icon('heroicon-o-document-arrow-down')
                    ->iconButton()
                    ->color('success')
                    ->action(fn () => Excel::download(new CustomersExport, 'customers.xlsx')),

                // Export CSV
                Action::make('export_csv')
                    ->tooltip('Export CSV')
                    ->icon('heroicon-o-document-text')
                    ->iconButton()
                    ->action(fn () => Excel::download(new CustomersExport, 'customers.csv')),

                // Export PDF
                Action::make('export_pdf')
                    ->tooltip('Export PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->iconButton()
                    ->url(route('customers.pdf.download')),

            ])

            ->recordActions([
                Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil')
                    ->color('primary')
                    ->form(fn (Customer $record) => [
                        TextInput::make('name')->label('Name')->default($record->name)->maxLength(255),
                        TextInput::make('email')->label('Email')->default($record->email)->email()->maxLength(100),
                        Select::make('phone_country_code')->label('Country Code')
                            ->options([
                                '+855' => 'KH Cambodia (+855)',
                                '+1' => 'US USA (+1)',
                                '+84' => 'VN Vietnam (+84)',
                                '+88' => '￥ China (+88)',
                            ])
                            ->default($record->phone_country_code)
                            ->required(),
                        TextInput::make('phone_number')->label('Phone Number')
                            ->tel()
                            ->numeric()
                            ->default($record->phone_number)
                            ->required(),
                    ])
                    ->action(fn (Customer $record, array $data) => $record->update($data))
                    ->successNotification(
                        Notification::make()
                            ->title('Customer Updated')
                            ->body('Customer details updated successfully.')
                            ->success()
                    ),

                Action::make('delete')
                    ->label('Delete')
                    ->requiresConfirmation()
                    ->color('danger')
                    ->action(fn (Customer $record) => $record->delete())
                    ->successNotification(
                        Notification::make()
                            ->title('Customer Deleted')
                            ->success()
                    ),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    Action::make('delete_selected')
                        ->label('Delete Selected')
                        ->requiresConfirmation()
                        ->color('danger')
                        ->action(fn ($records) => $records->each->delete()) 
                        ->successNotification(Notification::make()->title('Customers Deleted')->success()),

                    Action::make('export_selected_excel')
                        ->label('Export Selected (Excel)')
                        // ->accessSelectedRecords()
                        ->action(function ($records) {
                            return response()->streamDownload(
                                fn () => Excel::download(
                                    new CustomersExport($records->values()),
                                    'selected_customers.xlsx'
                                ),
                            );
                        }),

                ]),
            ]);
            
    }

    public function render(): View
    {
        return view('livewire.customer.list-customers');
    }
}
