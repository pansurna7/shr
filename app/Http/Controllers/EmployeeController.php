<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Support\Facades\Storage;
use Log;
use Illuminate\Support\Facades\DB;
use App\Models\Employee;
use App\Models\Position;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employee::all();
        // dd($employees);
        return view('backend.employee.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $user = User::where('id',0)->first();
        $employee = Employee::where('id' , 0)->first();
        $positions = Position::all();
        $branchs = Branch::all();

        return view('backend.employee.create',compact('employee','positions','branchs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // dd($request->all());
    $request->validate([
        'password' =>'required|string|min:8',
        'email' =>'required|string|min:8',
    ]);

    DB::beginTransaction();
    try {
        $user = User::create([
            'name'      => $request->fName,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            // 'status'    => $request->checkStatus,
            'status'    => filled('checkStatus'),
        ]);

        // B. Dapatkan user_id dari user yang baru dibuat
        $new_user_id    = $user->id; // ✨ INI KUNCI UNTUK MENGAMBIL ID ✨
        $role           = Role::where('id',10005)->get();
        if ($role) {
            // Panggil assignRole() pada objek User ($user), bukan ID-nya
            $user->assignRole($role);
        }

        // 1. Tangani Pengunggahan File (Avatar)
        $avatarPath = null; // Inisialisasi default null
        if ($request->hasFile('avatar')) {
            // Simpan file ke direktori 'avatar' di storage/app/public
            // Variabel $avatarPath akan berisi path/nama file, misalnya: 'avatar/randomstring.jpg'
            $avatarPath = $request->file('avatar')->store('avatar', 'public');
        }
        Employee::create([
            'user_id'               => $new_user_id,
            'position_id'           => $request->jabatan,
            'branch_id'             => $request->branch,
            'nik'                   => $request->nik,
            'nomor_ktp'             => $request->nKtp,
            'first_name'            => $request->fName,
            'last_name'             => $request->lName,
            'place_of_birth'        => $request->tLahir,
            'date_of_birth'         => $request->tglLahir,
            'gender'                => $request->gendre,
            'religion'              => $request->agama,
            'marital_status'        => $request->statusNikah,
            'jumlah_anak'           => $request->jAnak,
            'education'             => $request->pendidikan,
            'address'               => $request->alamat,
            'mobile'                => $request->handphone,
            'gaji_pokok'            => $request->gapok,
            'tanggal_diangkat'      => $request->tglKontrak,
            'tanggal_keluar'        => $request->tglResign,
            'nomor_rekening'        => $request->nRek,
            'rekening_atas_nama'    => $request->pRek,
            'avatar'                => $avatarPath

        ]);
        DB::commit();
        flash()->success('Employee created successfully');
        return redirect()->route('employee.index');
    } catch (\Exception $e) {
        DB::rollBack();
        flash()->error('Please fix the errors in the form :' . $e->getMessage());
        return back();

    }


}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $employee   = Employee::where('id' , $id)->first();
        $user       = User::where('id',$employee->user_id)->first();
        $positions  = Position::all();
        $branchs    = Branch::all();

        return view('backend.employee.edit',compact('employee','positions','branchs','user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // 🔥 PENTING: Gunakan Database Transaction untuk memastikan atomisitas User & Employee
        DB::beginTransaction();

        try {
            // 1. Validasi

            $employee = Employee::findOrFail($id); // Gunakan findOrFail untuk penanganan 404

            // 2. Penanganan Avatar (Sebelum update data employee)
            if ($request->hasFile('avatar')) {
                // Hapus file lama jika ada
                if ($employee->avatar) {
                    Storage::disk('public')->delete($employee->avatar);
                }
                $employee->avatar = $request->file("avatar")->store("avatar", "public");
            }
            // Jika tidak ada file baru diunggah, biarkan $employee->avatar seperti semula

            // 3. Update Data Employee
            // $employee->fill($request->only([
            //     'position_id', 'branch_id', 'nik', 'nomor_ktp', 'first_name', 'last_name',
            //     'place_of_birth', 'date_of_birth', 'gender', 'religion', 'marital_status',
            //     'jumlah_anak', 'education', 'address', 'mobile', 'gaji_pokok',
            //     'tanggal_diangkat', 'tanggal_keluar', 'nomor_rekening', 'rekening_atas_nama'
            // ]));
            // Menggunakan fill() untuk mass assignment yang lebih rapi

            // MAPPING FIELD YANG TIDAK SESUAI (Pastikan sesuai dengan nama request dan database)
            $employee->position_id           = $request->jabatan;
            $employee->branch_id             = $request->branch;
            $employee->nik                   = $request->nik;
            $employee->nomor_ktp             = $request->nKtp;
            $employee->first_name            = $request->fName;
            $employee->last_name             = $request->lName;
            $employee->place_of_birth        = $request->tLahir;
            $employee->date_of_birth         = $request->tglLahir;
            $employee->gender                = $request->gendre;
            $employee->religion              = $request->agama;
            $employee->marital_status        = $request->statusNikah;
            $employee->jumlah_anak           = $request->jAnak;
            $employee->education             = $request->pendidikan;
            $employee->address               = $request->alamat;
            $employee->mobile                = $request->handphone;
            $employee->gaji_pokok            = $request->gapok;
            $employee->tanggal_diangkat      = $request->tglKontrak;
            $employee->tanggal_keluar        = $request->tglResign;
            $employee->nomor_rekening        = $request->nRek;
            $employee->rekening_atas_nama    = $request->pRek;

            $employee->save();

            $request->validate([
                // Email harus unik kecuali milik user ini
                'email' => 'required|email|unique:users,email,'.$employee->user_id, // Asumsikan user_id tersedia di request atau employee
                'password' => 'nullable|string|min:8',
                'avatar' => 'nullable|image|mimes:jpg,png,jpeg|max:2048', // Tambahkan validasi file yang lebih baik
            ]);
            // 4. Update Data User
            $user = User::findOrFail($employee->user_id);

            $user->name = $request->fName;
            $user->email = $request->email; // ✨ PERBAIKAN: Mengatur email user

            // ✨ PERBAIKAN: Menangani checkbox/switch Status
            $user->status = $request->has('checkStatus') ? 1 : 0;

            // 5. Update Password (Kondisional)
            if (!empty($request->password)) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            DB::commit(); // Commit Transaction

            flash()->success('Employee updated successfully');
            return redirect()->route('employee.index');

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback jika ada error


            flash()->error('Gagal memperbarui data. Silakan coba lagi. ' . $e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function delete($id)
    // {
    //     if($id){
    //         $permission = Employee::findById($id);
    //         $permission->delete();
    //         flash()->success('Employee deleted successfully');
    //         return redirect()->back();
    //     }
    // }
    public function delete($id)
{
    // 1. Mulai Database Transaction
    DB::beginTransaction();

    try {
        // Gunakan findOrFail untuk penanganan 404 yang lebih baik
        $employee = Employee::findOrFail($id);

        // Simpan user_id untuk menghapus user nanti
        // $user_id = $employee->user_id;

        // 2. Hapus File Avatar (jika ada)
        if ($employee->avatar) {
            Storage::disk('public')->delete($employee->avatar);
        }

        // 3. Hapus Data Employee
        $employee->delete();

        // 4. Hapus Data User terkait (PENTING!)
        // Cari dan hapus User yang berelasi
        // $user = User::find($user_id);
        // if ($user) {
        //     $user->delete();
        // }

        // 5. Commit Transaksi (semua operasi berhasil)
        DB::commit();

        flash()->success('Employee deleted successfully');
        return redirect()->back();

    } catch (\Exception $e) {
        // 6. Rollback Transaksi (jika ada error)
        DB::rollBack();


        flash()->error('Gagal menghapus Employee. Silakan coba lagi.' . $e->getMessage());
        return redirect()->back();
    }
}
}
