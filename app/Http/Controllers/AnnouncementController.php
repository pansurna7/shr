<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Exception;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::orderBy('created_at', 'desc')->get();
        return view('backend.announcement.index', compact('announcements'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'file_name' => 'nullable|mimes:pdf,jpg,png,jpeg|max:2048',
        ]);

        try {
            $data = $request->all();

            if ($request->hasFile('file_name')) {
                $file = $request->file('file_name');

                // Cek apakah ada error pada file itu sendiri
                if ($file->getError() !== UPLOAD_ERR_OK) {
                    return back()
                        ->with('error', 'Upload gagal: ' . $file->getErrorMessage())
                        ->withInput();
                }

                $filename = date('Ymd_His') . '_' . Str::slug($request->title) . '.' . $file->getClientOriginalExtension();

                // Path tujuan: public/storage/announcements
                $destinationPath = public_path('storage/announcements');

                // Pastikan folder ada
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }

                // Gunakan move() sebagai alternatif storeAs()
                $file->move($destinationPath, $filename);
                $data['file_name'] = $filename;
            }

            Announcement::create($data);
            return back()->with('success', 'Pengumuman berhasil diterbitkan!');
        } catch (\Exception $e) {
            // Hapus file jika database gagal menyimpan
            if (isset($filename)) {
                Storage::disk('public')->delete('announcements/' . $filename);
            }
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);

        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'file_name' => 'nullable|mimes:pdf,jpg,png,jpeg|max:2048',
        ]);

        try {
            $data = $request->except('file_name');

            if ($request->hasFile('file_name')) {
                $file = $request->file('file_name');
                $name = date('Ymd_His') . '_' . Str::slug($request->title) . '.' . $file->getClientOriginalExtension();

                // Simpan file
                $file->storeAs('announcements', $name, 'public');
                $data['file_name'] = $name;

                // Hapus file lama jika ada
                if ($announcement->file_name) {
                    Storage::disk('public')->delete('announcements/' . $announcement->file_name);
                }
            }

            $announcement->update($data);
            return back()->with('success', 'Data berhasil diperbarui!');
        } catch (Exception $e) {
            return back()->with('error', 'Gagal memperbarui: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $announcement = Announcement::findOrFail($id);

            // Hapus file dari storage jika ada
            if ($announcement->file_name) {
                // Menggunakan disk public agar sesuai dengan folder storage/app/public/announcements
                Storage::disk('public')->delete('announcements/' . $announcement->file_name);
            }

            $announcement->delete();
            return back()->with('success', 'Pengumuman berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
