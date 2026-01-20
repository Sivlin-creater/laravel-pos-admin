<?php

namespace App\Livewire;

use App\Models\Customer;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class CustomerGrowthChart extends ChartWidget
{
    protected ?string $heading = 'Customer Growth (Last 30 days)';

    protected function getData(): array
    {
        $labels = [];
        $values = [];

        // Last 30 days
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labels[] = $date->format('d M'); // e.g., 14 Jan
            $values[] = Customer::whereDate('created_at', $date)->count();
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'New Customers',
                    'data' => $values,
                    'backgroundColor' => 'rgba(16, 185, 129, 0.2)', // green with transparency
                    'borderColor' => 'rgb(16, 185, 129)', // green line
                    'borderWidth' => 2,
                    'fill' => true, // fills under the line
                    'tension' => 0.3, // smooth curve
                    'pointBackgroundColor' => 'rgb(5, 150, 105)', // point color
                    'pointRadius' => 4,
                ],
            ],
            'options' => [
                'plugins' => [
                    'tooltip' => [
                        'enabled' => true,
                        'mode' => 'index',
                        'intersect' => false,
                    ],
                ],
                'scales' => [
                    'y' => [
                        'beginAtZero' => true,
                        'title' => [
                            'display' => true,
                            'text' => 'Customers',
                        ],
                    ],
                    'x' => [
                        'title' => [
                            'display' => true,
                            'text' => 'Date',
                        ],
                    ],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
