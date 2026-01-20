<?php

namespace App\Livewire;

use Filament\Widgets\ChartWidget;
use App\Models\SalesItem;
use App\Models\Item;

class TopProductsChart extends ChartWidget
{
    protected ?string $heading = 'Top 5 Products';

    protected function getData(): array
    {
        //Get top 5 by total quantity sold
        $topProducts = SalesItem::select('item_id')
            ->selectRaw('SUM(quantity) as total_sold')
            ->groupBy('item_id')
            ->orderByDesc('total_sold')
            ->limit(5)->with('item')->get()
            ->mapWithKeys(fn ($record) => [
                $record->item?->name ?? 'Unknown' => $record->total_sold
            ])
            ->toArray();
            

        return [
            'labels' => array_keys($topProducts),
            'datasets' => [
                [
                    'label' => 'Quantity Sold',
                    'data' => array_values($topProducts),
                    'backgroundColor' => [
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(34, 197, 94, 0.8)',
                        'rgba(5, 150, 105, 0.8)',
                        'rgba(52, 211, 153, 0.8)',
                        'rgba(22, 163, 74, 0.8)',
                    ],
                    'borderColor' => [
                        'rgba(5, 150, 105, 1)',
                        'rgba(5, 150, 105, 1)',
                        'rgba(5, 150, 105, 1)',
                        'rgba(5, 150, 105, 1)',
                        'rgba(5, 150, 105, 1)',
                    ],
                    'borderWidth' => 1,
                    'hoverOffset' => 4,
                ],
            ],

            'options' => [
                'plugins' => [
                    'tooltip' => [
                        'enabled' => true,
                        'mode' => 'index',
                        'intersect' => false,
                    ],
                    'legend' => [
                        'display' => false,
                    ],
                ],
                'scales' => [
                    'y' => [
                        'beginAtZero' => true,
                        'title' => [
                            'display' => true,
                            'text' => 'Quantity Sold',
                        ],
                    ],
                    'x' => [
                        'title' => [
                            'display' => true,
                            'text' => 'Products',
                        ],
                    ],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
