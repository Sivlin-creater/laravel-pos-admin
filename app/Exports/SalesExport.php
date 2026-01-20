<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class SalesExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $records;

    public function __construct($records = null) {
        $this->records = $records;
    }

    public function collection()
    {
        $sales = $this->records
            ? collect($this->records) : Sale::with(['customer', 'paymentMethod', 'salesItems'])->get();

        return $sales->map(function ($sale) {
            $remaining = $sale->total - $sale->paid_amount;

            return [
                'ID' => $sale->id,
                'Customer' => $sale->customer?->name ?? '-',
                'Payment Method' => $sale->paymentmethod?->name ?? '-',
                'Items' => $sale->salesItems
                        ->map(fn ($i) => $i->item?->name)
                        ->filter()
                        ->join(', '),
                'Total ($)' => $sale->total,
                'Paid ($)' => $sale->paid_amount,
                'Remaining ($)' => $remaining,
                'Discount ($)' => $sale->discount,
                'Status' => $sale->total <= $sale->paid_amount
                    ? 'Paid'
                    : ($sale->paid_amount > 0 ? 'Partial' : 'Pending'),
                'Created At' => $sale->created_at,
            ];
        });
    }

    public function headings(): array {
        return [
            'ID', 'Customer', 'Payment Method', 'Items', 'Total($)', 'Paid($)', 'Remaining($)', 'Discount($)', 'Status', 'Created At',
        ];
    }
    
    public function styles(Worksheet $sheet) {
        $highestRow = $sheet->getHighestRow();

        // Header style (same as ItemsExport)
        $sheet->getStyle('A1:J1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 14,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2563EB'], // blue-600
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

        // Data rows style
        $sheet->getStyle("A2:J{$highestRow}")->applyFromArray([
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

        $sheet->freezePane('A2');

        return [];
    }
}
