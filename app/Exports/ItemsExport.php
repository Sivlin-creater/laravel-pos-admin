<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ItemsExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{

    public function collection()
    {
        return Item::with('inventory')->get()->map(function ($item) {
            return [
                'ID' => $item->id,
                'Name' => $item->name,
                'SKU' => $item->sku,
                'Original Price' => $item->original_price,
                'Selling Price' => $item->selling_price,
                'Quanity' => $item->inventory?->quantity ?? 0,
                'Status' => $item->status,
                'Created At' => $item->created_at,
            ];
        });
    }

    public function headings(): array {
        return [
            'ID',
            'Name',
            'SKU',
            'Original Price',
            'Selling Price',
            'Quantity',
            'Status',
            'Created At',
        ];
    }

    public function styles(Worksheet $sheet) {
        $highestRow = $sheet->getHighestRow();

        //Header Style
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 14,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb', '2563EB'], //Blue-600
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

        //Data Rows Style
        $sheet->getStyle("A2:H{$highestRow}")->applyFromArray([
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

        //Freeze Header
        $sheet->freezePane('A2');

        return [];
    }
}
