<?php

namespace App\Livewire;

use App\Models\SalesItem;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class SalesTrendChart extends ChartWidget
{
    // protected ?string $heading = 'Sales Over Time';

    protected function getData(): array
    {
        $salesData = [];

        //Last 7 days
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->format('d M');
            $totalSales = SalesItem::whereDate('created_at', Carbon::today()->subDays($i))->sum('total');
            $salesData[$date] = $totalSales;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Sales ($)',
                    'data' => array_values($salesData),
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)', // blue with transparency
                    'borderColor' => 'rgb(59, 130, 246)',
                    'fill' => true,
                ],
            ],
            'labels' => array_keys($salesData),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
