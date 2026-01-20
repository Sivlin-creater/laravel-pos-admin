<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use App\Models\Inventory;

class InventoriesExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{

    public function collection()
    {
        return Inventory::with('item')->get()->map(function ($inventory) {
            return [
                $inventory->id,
                $inventory->item?->name ?? '',
                $inventory->quantity,
                ucfirst($inventory->stock_status),
                $inventory->created_at->format('Y-m-d H:i'),
                $inventory->updated_at->format('Y-m-d H:i'),
            ];
        });
    }

    public function headings(): array {
        return [
            'ID',
            'Item Name',
            'Quantity',
            'Stock Status',
            'Added On',
            'Last Updated',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();

        // Header row style
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2563EB'], // blue header
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        // Data rows style (center all)
        $sheet->getStyle("A2:F{$highestRow}")->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        // Freeze header
        $sheet->freezePane('A2');

        return [];
    }
}
