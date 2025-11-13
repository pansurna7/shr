<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Presence;
use App\Models\Submission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PresensiController extends Controller
{
    public function create(){
        $today = date('Y-m-d');
        $nik = Auth::user()->employee->id;
        $branch_id = Auth::user()->employee->branch_id;
        $cek = DB::table('presences')->where('date',$today)->where('employee_id',$nik)->count();
        $office_location = Branch::where('id',$branch_id)->first();
        return view('frontend.presensi.create',compact('cek','office_location'));
    }

    //Menghitung Jarak titik koordinat
    function distance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $feet = $miles * 5280;
        $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        return compact('meters');
    }

    public function store(Request $request)
    {

        $nik = Auth::user()->employee->id;
        $branch_id = Auth::user()->employee->branch_id;
        $branch_location = Branch::where('id',$branch_id)->first();
        $location = $branch_location->location;
        $lok_branch = explode(',',$location);
        $latOffice = $lok_branch[0];
        $longOffice = $lok_branch[1];
        $branch_radius = $branch_location->radius;

        $date = date('Y-m-d');
        $time_in = \Carbon\Carbon::now()->format('H:i:s');
        $time_out = \Carbon\Carbon::now()->format('H:i:s');

        $lokasi = $request->lokasi;
        $userLocation = explode(",",$lokasi);
        $latUser= $userLocation[0];
        $longUser = $userLocation[1];
        $jarak = $this->distance($latOffice, $longOffice , $latUser, $longUser);
        $radius = round($jarak["meters"]);

        $image = $request->image;
        $folderPath = "absensi/";
        $cek = DB::table('presences')->where('date',$date)->where('employee_id',$nik)->count();
        if ($cek > 0) {
            $ket ="out";
        }else{
            $ket ="in";
        }
        $formatName = $nik."-".$date."-".$ket;
        $image_parts = explode(";base64", $image);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = $formatName.".png";
        $file =  $folderPath . $fileName;

        $data = [
            'employee_id'   => $nik,
            'date'          => $date,
            'time_in'       => $time_in,
            'photo_in'      => $fileName,
            'location_in'   => $lokasi,
        ];
        try {
            $cek = DB::table('presences')->where('date',$date)->where('employee_id',$nik)->count();
            // cek radius user dengan target
            if($radius > $branch_radius){
                echo "Error_radius|Maaf Anda Berada Diluar Radius, Jarak Anda " . $radius ." meter dari kantor";
            }else{
                if($cek > 0){
                    $data_pulang =[
                        'time_out' => $time_out,
                        'photo_out' => $fileName,
                        'location_out' => $lokasi
                    ];

                    DB::table('presences')->where('date',$date)->where('employee_id', $nik)->update($data_pulang);
                    echo "success|Terimakasih, Anda Berhail Absen Pulang|out";
                    Storage::disk('public')->put($file,$image_base64);
                }else{
                    Presence::create($data);
                    echo "success|Terimakasih Anda Berhasil Absen Masuk|in";
                    Storage::disk('public')->put($file,$image_base64);
                }
            }

        } catch (\Throwable $th) {
            echo "error|sorry something went wrong",$th;
        }
    }

    public function editProfile()
    {
        $user       = Auth::user();
        $employee   = $user->employee;
        // $employee=DB::table('employee')
        // ->where('user_id',$id)
        // ->first();
        return view('frontend.presensi.editProfile',compact('employee'));
    }

    public function updateProfile(Request $request, $id)
    {
        // return $request->all();
        $request->validate([
                    'first_name'    => 'required',
                    'last_name'     => 'nullable',
                    'mobile'        => 'nullable|string|max:15',
                    'address'       => 'nullable|string|max:255',
                    'photo'         => 'nullable',
                    'password'      => 'nullable|string|min:8',
                ]);
        try {

            // return $validate;
            $employee = Employee::findOrFail($id);
            if($request->hasFile('photo')){
                if($employee->avatar){
                    Storage::disk('public')->delete($employee->avatar);
                }
                $employee->avatar   = $request->file("photo")->store("avatar","public");
            }
            $employee->first_name   = $request->first_name;
            $employee->last_name    = $request->last_name;
            $employee->mobile       = $request->mobile;
            $employee->address      = $request->address;
            $employee->save();

            $user = User::findOrFail($employee->user_id);
            // dd($user);

            if(!empty($request->password)){
                $user->password = Hash::make($request->password);
            }else{
                unset($request->password);
            }

            flash()->option('zIndex', 9999)->success('Profile updated successfully');
            return redirect()->route('frontend.dashboards');
        } catch (\Exception $e) {
            flash()->error('Please fix the errors in the form' . $e->getMessage());
            return back();
        }



    }
    public function history()
    {
        $nama_bulan = ["", "Januari","Februari","Maret","April","Mei","Juni","July","Agustus","September","Oktober","November","Desember"];

        return view('frontend.presensi.history',compact('nama_bulan'));
    }

    public function getHistory(Request $request)
    {
        $bulan= $request->bulan;
        $tahun = $request->tahun;
        $nik= Auth::user()->employee->id;
        $history=DB::table('presences')
        ->whereRaw("MONTH(date)='$bulan'")
        ->whereRaw("YEAR(date)='$tahun'")
        ->where('employee_id',$nik)
        ->orderBy('date')
        ->get();
        return view('frontend.presensi.getHistory',compact('history'));
    }

    public  function izin()
    {
        $nik = Auth::user()->employee->id;
        // $dataizin = Submission::where('nik', $nik)->get();
        $submissions = DB::table('submissions')->where('employee_id',$nik)->get();

        // dd($dataizin);
        return view('frontend.presensi.izin', compact('submissions'));
    }
    public function pengajuan()
    {
        return view('frontend.presensi.pengajuan');
    }
    /*
    public function storeizin(Request $request)
    {
        // $id = Auth::user()->employee->id;
        $employee_id = Auth::user()->employee->id;
        $tgl_izin=\Carbon\Carbon::parse($request->tgl_izin)->format('Y-m-d');
        $status=$request->status;
        $ket=$request->ket;
        if($request->hasFile('photo')){
            $photo = $request->file("photo")->store("submissions","public");
            Submission::create([
                    'employee_id'   => $employee_id,
                    'date'          => $tgl_izin,
                    'condition'     => $status,
                    'information'   => $ket,
                    'photo'         => $photo,
                ]);
            flash()->success('Submission created succsessfully');
            return redirect()->route('presensi.izin');
        }else{
            Submission::create([
                    'employee_id'   => $employee_id,
                    'date'          => $tgl_izin,
                    'condition'     => $status,
                    'information'   => $ket,
                ]);
            flash()->success('Submission created succsessfully');
            return redirect()->route('presensi.izin');
        }

    }
    */


    public function storeizin(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'tgl_izin'  => 'required|date',
            'status'    => 'required|string|max:50', // condition
            'ket'       => 'required|string|max:255', // information
            'photo'     => 'nullable|image|mimes:jpg,png,jpeg|max:2048', // Tambahkan validasi file
        ]);

        // 2. Ambil Employee ID & Format Tanggal
        // Menggunakan Eloquent Relasi lebih baik, tapi kode Anda sudah berfungsi:
        $employee_id = Auth::user()->employee->id;

        // Carbon::parse sudah otomatis mengembalikan objek Carbon.
        // Jika tgl_izin dari form sudah berupa Y-m-d, format ini opsional.
        $tgl_izin = \Carbon\carbon::parse($request->tgl_izin)->toDateString();

        // 3. Siapkan Data Dasar
        $data = [
            'employee_id'   => $employee_id,
            'date'          => $tgl_izin,
            'condition'     => $request->status, // Pastikan nama kolom database sesuai
            'information'   => $request->ket,    // Pastikan nama kolom database sesuai
        ];

        // 4. Penanganan File Kondisional
        if ($request->hasFile('photo')) {
            // Simpan file dan tambahkan path ke array $data
            $data['photo'] = $request->file("photo")->store("submissions", "public");
        }

        try {
            // 5. Simpan Data ke Database (Hanya satu kali)
            Submission::create($data);

            flash()->success('Pengajuan izin berhasil dibuat.');
            return redirect()->route('presensi.izin');

        } catch (\Exception $e) {
            // Log Error dan beri feedback
            // \Log::error("Gagal menyimpan pengajuan: " . $e->getMessage());

            // Hapus file yang sudah terlanjur diunggah jika terjadi error database
            if (isset($data['photo'])) {
                Storage::disk('public')->delete($data['photo']);
            }

            flash()->error('Gagal membuat pengajuan. Silakan coba lagi.' .$e->getMessage());
            return redirect()->back()->withInput();
        }

    }

    public function monitoring(Request $request)
    {
        // $date = $request->tanggal;
        // $presences = Presence::with('employee.position.departement')->get();
        return view('frontend.presensi.monitoring');
    }



    public function getpresensi(Request $request)
    {
        // 1. Proses Tanggal
        $tanggalRange = explode(' to ', $request->tanggal);
        $tanggalAwal = $tanggalRange[0];
        $tanggalAkhir = $tanggalRange[1] ?? $tanggalRange[0];

        // Gunakan try-catch untuk penanganan error format tanggal
        try {
            $tanggalAwalDB = \Carbon\carbon::createFromFormat('d-m-Y', $tanggalAwal)->toDateString();
            $tanggalAkhirDB = \Carbon\carbon::createFromFormat('d-m-Y', $tanggalAkhir)->toDateString();

        } catch (\Exception $e) {
            // Jika format tanggal tidak sesuai (jarang terjadi jika menggunakan flatpickr),
            // bisa dikembalikan error atau default ke hari ini.
            return response()->json(['error' => 'Format tanggal tidak valid.'], 422);
        }

        // 2. Query Data dengan Eager Loading

        $presences = Presence::with('employee.position.departement')
            ->whereBetween('date', [$tanggalAwalDB, $tanggalAkhirDB])
            ->get();

        // 3. Kembalikan View (HTML yang akan dimasukkan ke #loadpresensi)
        return view('frontend.presensi.getpresensi', compact('presences'));
    }

    public function showmap(Request $request)
    {
        // dd($request->id);
        $id = $request->id;
        $presence = Presence::where('id', $id)->first();

        return view('frontend.presensi.showmap', compact('presence'));
    }

    public function reportPresence()
    {
        $employees = Employee::all();
        $nama_bulan = ["", "Januari","Februari","Maret","April","Mei","Juni","July","Agustus","September","Oktober","November","Desember"];
        return view('frontend.presensi.reportpresence',compact('nama_bulan','employees'));
    }

    public function cetakreport(Request $request)
    {
        $id    = $request->id;
        // dd("ID yang dicari: " . $id);
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        // Array untuk mapping nama bulan (Sudah benar)
        $nama_bulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

        // 1. Ambil Detail Karyawan (Sudah benar)
        $employee = Employee::where('id', $id)->first();
        // dd($employee);
        // 2. Ambil Data Presensi (Wajib ditambahkan)
        // Gunakan whereMonth dan whereYear untuk memfilter presensi
        // 'date' adalah nama kolom tanggal di tabel presences.
        $presences = Presence::where('employee_id', $id)
            ->whereMonth('date', $bulan)
            ->whereYear('date', $tahun)
            // Urutkan berdasarkan tanggal untuk laporan
            ->orderBy('date', 'asc')
            ->get();

        // 3. Penanganan Jika Karyawan Tidak Ditemukan
        // if (!$employee) {
        //     // Misalnya, kembalikan response error atau redirect
        //     return redirect()->back()->with('error', 'Karyawan tidak ditemukan.');
        // }

        // 4. Kirim semua data ke View
        return view('frontend.presensi.cetakReport', compact(
            'bulan',
            'tahun',
            'nama_bulan',
            'employee',
            'presences' // Kirim data presensi
        ));
    }

    public function rekapPresence()
    {
        // $employees = Employee::all();
        $nama_bulan = ["", "Januari","Februari","Maret","April","Mei","Juni","July","Agustus","September","Oktober","November","Desember"];
        return view('frontend.presensi.rekappresence',compact('nama_bulan'));
    }

    public function cetakrekap(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $nama_bulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

        $select = [
            'p.employee_id',
            'e.nik',
            "e.first_name + ' ' + e.last_name AS name",
        ];

        for ($i = 1; $i <= 31; $i++) {
            $select[] = "
                MAX(
                    IIF(DATEPART(day, p.date) = $i,
                        -- ✅ PERBAIKAN: Konversi p.time_in ke VARCHAR
                        CONVERT(VARCHAR, p.time_in, 108) + ' - ' + ISNULL(CONVERT(VARCHAR, p.time_out, 108), '00:00:00'),
                        ''
                    )
                ) AS tgl_$i
            ";
        }

        $presences = Presence::selectRaw(implode(', ', $select))
            ->from('presences as p')
            ->join('employees as e', 'p.employee_id', '=', 'e.id')
            ->whereRaw('DATEPART(month, p.date) = ? AND DATEPART(year, p.date) = ?', [$bulan, $tahun])
            ->groupBy('p.employee_id', 'e.nik', 'e.first_name', 'e.last_name')
            ->get();

        return view('frontend.presensi.cetakRekap', compact(
            'bulan',
            'tahun',
            'nama_bulan',
            'presences'   // Kirim semua data presensi mentah
        ));
    }

    public function submission()
    {
        $submissions=Submission::orderBy('id','desc')->with('employee')->get();
        return view('frontend.presensi.submission',compact('submissions'));

    }
    public function updateStatus(Request $request, $id)
    {
        $submission = Submission::findOrFail($id);

        // Ambil status dari input hidden form
        $new_status = $request->input('status');

        $submission->update(['status' => $new_status]);

        return redirect()->back()->with('success', 'Status pengajuan berhasil diperbarui!');
    }
}
