<?php

namespace App\Livewire\Management;

use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
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
use Illuminate\Support\Facades\URL;
use App\Models\User;

class ListUsers extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithTable;
    use InteractsWithSchemas;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => User::query())
            ->columns([
                TextColumn::make('id')->label('#')->sortable(),
                TextColumn::make('name')->label('Name')->searchable()->sortable()->toggleable(),
                TextColumn::make('email')->label('Email')->searchable()->sortable()->toggleable(),
                // TextColumn::make('password')->label('Password')->toggleable()->visible(fn($record) =>true),
                TextColumn::make('role')->label('Role')->searchable()->sortable()->toggleable(),
                TextColumn::make('status')->label('Status')->sortable()->badge()
                    ->color(fn ($state) => match($state) {
                        'active' => 'success',
                        'inactive' => 'warning',
                        'suspended' => 'danger',
                        default => 'secondary',
                    }),
                TextColumn::make('last_login_at')->label('Last Login')->dateTime('d M Y, H:i')->sortable(),
                TextColumn::make('created_at')->label('Created At')->dateTime('d M Y, H:i')->sortable(),
            ])

            ->filters([
                SelectFilter::make('role')
                    ->options([
                        'admin' => 'Admin',
                        'manager' => 'Manager',
                        'cashier' => 'Cashier',
                    ]),
                
                SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'suspended' => 'Suspended',
                        'default' => 'secondary',
                    ]),

                Filter::make('created_at')
                    ->form([
                        DatePicker::make('from')->label('From'),
                        DatePicker::make('until')->label('To'),
                    ])
                    ->query(fn ($query, array $data) => $query
                        ->when($data['from'], fn($q) => $q->whereDate('created_at', '>=', $data['from']))
                        ->when($data['until'], fn($q) => $q->whereDate('created_at', '<=', $data['until']))
                    ),
            ])

            ->headerActions([
                Action::make('create')->label('Add User')
                    ->icon('heroicon-o-plus')
                    ->color('primary')
                    ->form([
                        TextInput::make('name')->required(),
                        TextInput::make('email')->email()->required(),
                        TextInput::make('password')->label('Password')->required()->minLength(8),
                        Select::make('role')->options([
                            'admin' => 'Admin',
                            'manager' => 'Manager',
                            'cashier' => 'Cashier',
                        ])->required(),
                        Select::make('status')->options([
                            'active' => 'Active',
                            'inactive' => 'Inactive',
                            'suspended' => 'Suspended',
                            'default' => 'secondary',
                        ])->required(),
                    ])
                    ->action(fn ($data) =>
                        User::create($data)
                    )
                    ->visible(fn() => auth()->user()->isAdmin())
                    ->successNotification(fn () =>
                        Notification::make()
                            ->title('User Created')
                            ->success()
                    ),

                //Export Files
                Action::make('export_excel')->tooltip('Export Excel')
                    ->icon('heroicon-o-document-arrow-down')
                    ->iconButton()
                    ->color('success')
                    ->visible(fn() => !auth()->user()->isCashier())
                    ->action(fn () => 
                        Excel::download(new UsersExport, 'users.xlsx')
                    ),

                Action::make('export_csv')->tooltip('Export CSV')
                    ->icon('heroicon-o-document-text')
                    ->iconButton()
                    ->visible(fn() => !auth()->user()->isCashier())
                    ->action(fn () => 
                        Excel::download(new UsersExport, 'users.csv')
                    ),

                Action::make('export_pdf')->tooltip('Export PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->iconButton()
                    ->visible(fn() => !auth()->user()->isCashier())
                    ->action(function () {
                        // Generate a temporary signed URL for download
                        $url = URL::temporarySignedRoute(
                            'users-pdf.download',
                            now()->addMinutes(5),
                            []
                        );

                        // Redirect browser to that URL (will trigger download)
                        return redirect($url);
                    }),
            ])

            ->recordActions([
                Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil')
                    ->color('primary')
                    ->visible(fn() => auth()->user()->isAdmin())
                    ->form(fn (User $record) => [
                        TextInput::make('name')->label('Name')->default($record->name)->required(),
                        TextInput::make('email')->label('Email')->default($record->email)->required(),
                        TextInput::make('password')->label('New Password')->password()->minLength(8)->nullable()->helperText('Leave empty to keep current password.'),
                        TextInput::make('phone')->label('Phone')->default($record->phone),
                        Select::make('role')->label('Role')->options([
                            'admin' => 'Admin',
                            'manager' => 'Manager',
                            'cashier' => 'Cashier',
                        ])->default($record->role)->required(),
                        Select::make('status')->label('Status')->options([
                            'active' => 'Active',
                            'inactive' => 'Inactive',
                            'suspended' => 'Suspended',
                        ])->default($record->status)->required(),
                    ])
                    ->action(function (User $record, array $data) {
                        // Only update password if a new one is provided
                        if (!empty($data['password'])) {
                            $record->password = bcrypt($data['password']);
                        }

                        // Update the rest of the data
                        $record->update(collect($data)->except('password')->toArray());
                    })
                    ->successNotification(
                        Notification::make()
                            ->title('User Updated')
                            ->success()
                    ),

                Action::make('delete')
                    ->label('Delete')
                    ->icon('heroicon-o-trash')
                    ->requiresConfirmation()
                    ->color('danger')
                    ->visible(fn() => auth()->user()->isAdmin())
                    ->action(fn (User $record) => $record->delete())
                    ->successNotification(
                        Notification::make()
                            ->title('User deleted successfully.')
                            ->success()
                    ),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                     Action::make('delete')
                        ->label('Delete Selected')
                        ->color('danger')
                        ->accessSelectedRecords()
                        ->requiresConfirmation()
                        ->visible(fn() => auth()->user()->isAdmin())
                        ->action(fn ($records) =>
                            User::whereIn('id', $records->pluck('id'))->delete()
                        )
                        ->successNotification(fn () => Notification::make()
                            ->title('Selected users deleted')
                            ->success()
                        ),

                    // Excel export (all or selected)
                    Action::make('export_excel')
                        ->tooltip('Export Excel')
                        ->icon('heroicon-o-table-cells')
                        ->iconButton()
                        ->color('success')
                        ->visible(fn() => !auth()->user()->isCashier())
                        ->action(fn () => Excel::download(new UsersExport(), 'users.xlsx')),

                    Action::make('export_selected_excel')
                        ->tooltip('Export Selected Excel')
                        ->icon('heroicon-o-table-cells')
                        ->iconButton()
                        ->color('success')
                        ->visible(fn() => !auth()->user()->isCashier())
                        ->action(fn ($records) => Excel::download(
                            new UsersExport($records->pluck('id')),
                            'selected_users.xlsx'
                        )),
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.management.list-users');
    }
}
