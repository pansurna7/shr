<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class HolidayTemplateExport implements FromCollection, WithHeadings,WithTitle,ShouldAutoSize
{

    /**
    * Mengisi contoh data di bawah header
    */
    public function collection()
    {
        return collect([
            [
                '2026-01-01',
                'Tahun Baru 2026 (Contoh)'
            ]
        ]);
    }
   /**
    * Menentukan Header Excel
    */
    public function headings(): array
    {
        return [
            'tanggal',    // Kolom A
            'keterangan', // Kolom B
        ];
    }

    /**
    * Nama Sheet
    */
    public function title(): string
    {
        return 'Template Import Libur';
    }
}
