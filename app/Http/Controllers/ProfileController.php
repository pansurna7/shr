<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
// use Spatie\BackupServer\Models\User;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
            // 🔥 PENTING: Gunakan DB Transaction jika ada operasi lain di luar User
        // Jika hanya update User, transaksi opsional, tapi tetap bagus untuk keamanan.
        DB::beginTransaction();

        try {
            // 1. Validasi
            $request->validate([
                'name'          => 'required|string|max:255',
                // 🔥 PERBAIKAN: Abaikan email pengguna saat ini
                'email'         => 'required|email|unique:users,email,' . $request->id,
                'password'      => 'nullable|string|min:8',
                'avatar'        => 'nullable|image|mimes:jpg,png,jpeg|max:2048' // Tambahkan validasi file yang lebih baik
            ]);

            $user = User::findOrFail($request->id);

            // 2. Update Field Dasar (Name, Email, Mobile, Address)
            $user->name    = $request->name;
            $user->email   = $request->email;
            // Asumsi mobile dan address adalah kolom di tabel users (atau tabel terkait)
           

            // 3. Update Password (Kondisional)
            if (!empty($request->password)) {
                $user->password = Hash::make($request->password);
            }
            // Hapus blok else { unset($request->password); } karena tidak diperlukan

            // 4. Penanganan Avatar
            if ($request->hasFile('avatar')) {
                // Hapus file LAMA jika ada
                if ($user->avatar) {
                    Storage::disk('public')->delete($user->avatar);
                }
                // Simpan file baru
                $user->avatar = $request->file("avatar")->store("avatar", "public");
            }

            // 5. Simpan Perubahan
            $user->save();

            DB::commit(); // Commit Transaction

            flash()->success('Profile updated successfully');
            return redirect()->back();

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback jika terjadi error
            flash()->error('Gagal memperbarui data. Silakan coba lagi. ' . $e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
