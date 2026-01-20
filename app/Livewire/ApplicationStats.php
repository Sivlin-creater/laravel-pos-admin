<?php

namespace App\Livewire;

use App\Models\Item;
use App\Models\Inventory;
use App\Models\User;
use App\Models\Sale;
use App\Models\Customer;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ApplicationStats extends StatsOverviewWidget
{

    protected function getStats(): array
    {
        return [
            Stat::make('Items', Item::count())
                ->description('Total Items in System')
                ->icon('heroicon-o-cube')
                ->color('primary'),

            Stat::make('Users', User::count())
                ->description('Active Users')
                ->icon('heroicon-o-users')
                ->color('success'),

            Stat::make('Sales', Sale::count())
                ->description('Total Sales')
                ->icon('heroicon-o-shopping-cart')
                ->color('warning'),
            
            Stat::make('Customers', Customer::count())
                ->description('Total Customers')
                ->icon('heroicon-o-users')
                ->color('success'),

            Stat::make('Revenue Today', Sale::whereDate('created_at', today())->sum('total'))
                ->description('Total revenue for today')
                ->icon('heroicon-o-currency-dollar')
                ->color('success'),

            Stat::make('Low Stock', Inventory::where('quantity', '<=', 5)->count())
                ->description('Items running low on Stock')
                ->icon('heroicon-o-exclamation-triangle')
                ->color('danger'),

        ];
    }
}
