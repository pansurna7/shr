<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\HolidayTemplateExport;
use App\Imports\HolidayImport;
use Illuminate\Http\Request;


class Holidaycontroller extends Controller
{
    public function index()
    {
        $holidays=Holiday::all();
        return view('backend.holiday.index', compact('holidays'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'holiday_date' => 'required|date|unique:holidays,holiday_date',
            'name'         => 'required|string|max:255',
        ], [
            'holiday_date.unique' => 'Tanggal ini sudah terdaftar sebagai hari libur.',
            'name.required'       => 'Keterangan libur tidak boleh kosong.'
        ]);

        try {
            Holiday::create([
                'holiday_date' => $request->holiday_date,
                'description'         => $request->name,
                // 'is_national'  => $request->has('is_national') ? 1 : 0
            ]);

            return redirect()->back()->with('success', 'Hari libur berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function edit(Request $request)
    {
        $holiday = Holiday::findOrFail($request->id);
        return view('backend.holiday.edit', compact('holiday'));
    }

    public function update(Request $request, $id)
    {
        // // 1. Validasi Input
        $request->validate([
            'holiday_date'  => 'required|date',
            'name'          => 'required|string|max:255',
        ]);

        try {
            $holiday = Holiday::findOrFail($id);

            // 2. Mapping Data
            // Pastikan 'description' di sini sesuai dengan nama kolom di database Anda
            $data = [
                'holiday_date' => $request->holiday_date,
                'description'  => $request->name // Sesuaikan dengan name="description" di form edit
            ];

            $holiday->update($data);
            flash()->success('Holiday updated successfully');
            // 3. Berikan Response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Data hari libur berhasil diperbarui.'
            ], 200);

        } catch (\Exception $e) {
            // Response jika gagal
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function delete($id)
    {
        if($id){
            $holiday = Holiday::find($id);
            $holiday->delete();
            flash()->success('holiday deleted successfully');
            return redirect()->back();
        }

    }

    public function download()
    {
        return Excel::download(new HolidayTemplateExport, 'template_hari_libur.xlsx');
    }

    public function importExcel(Request $request)
    {
        set_time_limit(300);
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new HolidayImport, $request->file('file_excel'));
            return redirect()->back()->with('success', 'Data hari libur berhasil diimport!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Cek format file Anda. Pastikan kolom sesuai.');
        }
    }
}
