<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Management\ListUsers;
use App\Livewire\Items\ListItems;
use App\Livewire\Items\ListInventories;
use App\Livewire\Customer\ListCustomers;
use App\Models\Customer;
use App\Livewire\Sales\ListSales;
use App\Livewire\Management\ListPaymentMethods;
use App\Livewire\POS;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Livewire\Reports\Index;
use App\Livewire\Items\ItemHistory;
use App\Livewire\Items\InventoryHistory;
use App\Livewire\Management\PaymentMethodHistory;
use App\Livewire\Management\UserHistory;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__.'/settings.php';

Route::middleware(['auth'])->group(function() {

    // Only Admin & Manager
    
        Route::get('/manage-users', ListUsers::class)->name('users.index');
        Route::get('/manage-items', ListItems::class)->name('items.index');
        Route::get('/manage-inventories', ListInventories::class)->name('inventories.index');
        Route::get('/manage-customers', ListCustomers::class)->name('customers.index');
        Route::get('/customers/pdf/download', function () {
            $customers = Customer::all();
            $pdf = Pdf::loadView('livewire.customer.customers-pdf', compact('customers'));
            return $pdf->download('customers.pdf');
        })->name('customers.pdf.download');

        Route::get('/manage-payment-methods', ListPaymentMethods::class)->name('payment.methods.index');
        Route::get('/reports', Index::class)->name('reports.index');
        Route::get('/users/pdf/download', function() {
            $ids = request()->query('ids', null);
            $users = $ids ? User::whereIn('id', $ids)->get() : User::all();
            $pdf = Pdf::loadView('livewire.management.users-pdf', compact('users'))
                ->setPaper('A4', 'portrait');
            return $pdf->download($ids ? 'selected_users.pdf' : 'users.pdf');
        })->name('users-pdf.download')->middleware('signed');
    

    //POS is available to all roles
    Route::get('/pos', POS::class)->name('pos');

    //Sales page
    Route::get('/manage-sales', ListSales::class)->name('sales.index');
        // ->middleware('role:admin,manager,cashier');
});
