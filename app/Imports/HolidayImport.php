<?php


namespace App\Imports;

use App\Models\Holiday;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow; // Tambahkan ini
use Carbon\Carbon;

class HolidayImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Sekarang $row['tanggal'] merujuk pada teks di baris pertama kolom A
        $tanggal = $this->transformDate($row['tanggal']);

        if (!$tanggal) {
            return null;
        }

        // Cek duplikasi agar tidak double import
        $exists = Holiday::where('holiday_date', $tanggal->format('Y-m-d'))->first();
        if ($exists) {
            return null;
        }

        return new Holiday([
            'holiday_date' => $tanggal->format('Y-m-d'),
            'description'  => $row['keterangan'], // Sesuaikan dengan header di Excel
        ]);
    }

    private function transformDate($value)
    {
        if (empty($value)) return null;

        // Jika user mengisi dengan format Date di Excel (Numeric)
        if (is_numeric($value)) {
            return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
        }

        // Jika user mengisi dengan format Text biasa (YYYY-MM-DD)
        try {
            return Carbon::parse($value);
        } catch (\Exception $e) {
            return null;
        }
    }
}
