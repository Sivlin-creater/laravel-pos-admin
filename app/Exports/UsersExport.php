<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;

class UsersExport implements FromCollection, WithHeadings, WithEvents, ShouldAutoSize
{
    protected ?array $ids;

    public function __construct(?array $ids = null) {
        $this->ids = $ids;
    }

    public function collection()
    {
        return User::when(
            $this->ids, fn($q) => $q->whereIn('id', $this->ids)
        )->get([
            'id', 'name', 'email', 'role', 'status', 'created_at'
        ]);
    }

    public function headings(): array {
        return [
            ['ID', 'Name', 'Email', 'Role', 'Status', 'Created At'],
        ];
    }

    public function registerEvents(): array {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Title row
                $sheet->insertNewRowBefore(1, 2);
                $sheet->mergeCells('A1:F1');
                $sheet->setCellValue('A1', 'Users Report');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

                $sheet->mergeCells('A2:F2');
                $sheet->setCellValue('A2', 'Generated on: ' . now()->format('d M Y, H:i'));
                $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(11);
                $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');

                // Header styling
                $sheet->getStyle('A3:F3')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '007bff'],
                    ],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
                ]);

                // Freeze header
                $sheet->freezePane('A4');

                $highestRow = $sheet->getHighestRow();
                for ($row = 4; $row <= $highestRow; $row++) {
                    // Stripe rows
                    if ($row % 2 == 0) {
                        $sheet->getStyle("A{$row}:F{$row}")->getFill()->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('F2F2F2');
                    }
                    // Borders
                    $sheet->getStyle("A{$row}:F{$row}")->getBorders()->getAllBorders()
                        ->setBorderStyle(Border::BORDER_THIN)
                        ->setColor(new Color('000000'));
                    // Align text
                    $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal('center'); // ID
                    $sheet->getStyle("D{$row}:F{$row}")->getAlignment()->setHorizontal('center'); // Role, Status, Date
                }

                // Format created_at column as date
                $sheet->getStyle("F4:F{$highestRow}")->getNumberFormat()->setFormatCode('dd mmm yyyy');
            },
        ];
    }
}
