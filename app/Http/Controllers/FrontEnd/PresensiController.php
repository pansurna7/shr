<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use Carbon\CarbonPeriod;
use Illuminate\Support\Carbon;
use App\Models\Branch;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Presence;
use App\Models\Submission;
use App\Models\WorkingDay;
use App\Models\WorkingHoursDeptDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

use function Flasher\Prime\flash;

class PresensiController extends Controller
{
    public function gethari()
    {
        $hari = date('D');
        switch ($hari) {
            case 'Sun':
                $hari_ini = 'Minggu';
                break;
            case 'Mon':
                $hari_ini = 'Senin';
                break;
            case 'Tue':
                $hari_ini = 'Selasa';
                break;
            case 'Wed':
                $hari_ini = 'Rabu';
                break;
            case 'Thu':
                $hari_ini = 'Kamis';
                break;
            case 'Fri':
                $hari_ini = 'Jumat';
                break;
            case 'Sat':
                $hari_ini = 'Sabtu';
                break;
        }
        return $hari_ini;
    }

    
    public function create()
    {
        $today = date('Y-m-d');
        $nama_hari = $this->gethari();
        $employee = Auth::user()->employee;
        $nik = $employee->id;
        $branch_id = $employee->branch_id;

        // 1. Ambil data departemen
        $dept_id = DB::table('departements as dpt')->join('positions as ps', 'dpt.id', '=', 'ps.departement_id')->join('employees as emp', 'ps.id', '=', 'emp.position_id')->where('emp.id', $nik)->value('dpt.id');

        // 2. Ambil Jam Kerja Berdasarkan Departemen
        $cekjamkerjadept = DB::table('working_hour_dept_detail as whdd')->join('working_hour_dept as whd', 'whdd.whd_id', '=', 'whd.id')->join('working_hours as wh', 'whdd.workinghour_id', '=', 'wh.id')->select('wh.name as shift_name', 'wh.start_time', 'wh.entry_time', 'wh.end_time', 'wh.out_time')->where('whd.dept_code', $dept_id)->where('whd.branch_code', $branch_id)->where('whdd.days', $nama_hari)->first();

        if (!$cekjamkerjadept) {
            flash()
                ->options(['zIndex' => 9999, 'timeout' => 10000])
                ->warning('Jam Kerja Anda Belum Disetting, Hubungi HRD');
            return redirect()->route('frontend.dashboards');
        }

        // 3. Cek Status Presensi Hari Ini
        $cek = DB::table('presences')->where('date', $today)->where('employee_id', $nik)->count();

        // 4. LOGIKA LOKASI (Join ke tabel location)
        $is_free_absen = $employee->is_free_absent;

        // Ambil data detail dari tabel location melalui tabel pivot employee_location
        $employee_locations = DB::table('employee_location')
            ->join('locations', 'employee_location.location_id', '=', 'locations.id')
            ->where('employee_location.employee_id', $nik)
            ->where('locations.is_active', 1) // Hanya ambil lokasi yang aktif
            ->select('locations.id', 'locations.name', 'locations.latitude', 'locations.longitude', 'locations.radius')
            ->get();

        return view('frontend.presensi.create', compact('cek', 'employee_locations', 'cekjamkerjadept', 'is_free_absen'));
    }

    //Menghitung Jarak titik koordinat
    function distance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $miles = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $feet = $miles * 5280;
        $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        return compact('meters');
    }



    // store berdasarkan multiple location atau free absent
    public function store(Request $request)
    {
        $nik = Auth::user()->employee->id;
        $employee = Employee::where('id', $nik)->first();

        // 1. Ambil Status Free Absent & Lokasi Kantor
        $is_free_absen = $employee->is_free_absen; // Pastikan kolom ini ada di database

        // Mendukung Multiple Location jika branch memiliki banyak titik atau relasi tertentu
        // $office_locations = Branch::where('id', $branch_id)->get();
        $employee_locations = DB::table('employee_location')->join('locations', 'employee_location.location_id', '=', 'locations.id')->where('employee_location.employee_id', $nik)->where('locations.is_active', 1)->select('locations.latitude', 'locations.longitude', 'locations.radius', 'locations.name')->get();

        $date = date('Y-m-d');
        $time_now = \Carbon\Carbon::now()->format('H:i:s');
        $nama_hari = $this->gethari();

        $lokasi = $request->lokasi;
        $userLocation = explode(',', $lokasi);
        $latUser = $userLocation[0];
        $longUser = $userLocation[1];

        // 2. Logika Pengecekan Radius (Bypass jika Free Absen)
        $in_radius = false;
        $min_distance = 0;
        $checked_locations = 0;

        if ($is_free_absen == 1) {
            $in_radius = true;
        } else {
            foreach ($employee_locations as $loc) {
                $checked_locations++; // Jangan lupa tambahkan increment ini agar proteksi di bawah jalan

                // GANTI LOGIKA EXPLODE DENGAN LANGSUNG MENGGUNAKAN LATITUDE & LONGITUDE
                // Karena di DB kolomnya terpisah, kita tidak perlu explode lagi
                $latOffice = $loc->latitude;
                $longOffice = $loc->longitude;

                // Masukkan langsung ke fungsi distance
                $jarak = $this->distance($latOffice, $longOffice, $latUser, $longUser);
                $radius_meters = round($jarak['meters']);

                if ($radius_meters <= $loc->radius) {
                    $in_radius = true;
                    break;
                }

                if ($min_distance == 0 || $radius_meters < $min_distance) {
                    $min_distance = $radius_meters;
                }
            }

            // Proteksi jika data di tabel employee_location kosong
            if ($checked_locations == 0 && $is_free_absen == 0) {
                echo 'error|Anda belum didaftarkan di lokasi manapun. Hubungi Admin.';
                return;
            }
        }

        $jam_kerja = WorkingDay::where('employee_id', $nik)->where('days', $nama_hari)->with('workinghours')->first();

        $image = $request->image;
        $folderPath = 'absensi/';
        $cek = DB::table('presences')->where('date', $date)->where('employee_id', $nik)->count();

        $ket = $cek > 0 ? 'out' : 'in';
        $formatName = $nik . '-' . $date . '-' . $ket;
        $image_parts = explode(';base64', $image);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = $formatName . '.png';
        $file = $folderPath . $fileName;

        try {
            // 3. Eksekusi Berdasarkan Hasil Pengecekan Radius
            if (!$in_radius) {
                echo 'Error_radius|Maaf Anda Berada Diluar Radius, Jarak Anda ' . $min_distance . ' meter dari kantor';
            } else {
                if ($cek > 0) {
                    // LOGIKA ABSEN PULANG
                    $data_pulang = [
                        'time_out' => $time_now,
                        'photo_out' => $fileName,
                        'location_out' => $lokasi,
                    ];
                    DB::table('presences')->where('date', $date)->where('employee_id', $nik)->update($data_pulang);

                    // Kirim WA Pulang
                    $isi_pesan = "✅ *PRESENSI PULANG BERHASIL*\n\n" . 'Nama: *' . $employee->first_name . ' ' . $employee->last_name . "*\n" . 'Jam: ' . $time_now . " WIB\n" . 'Status: ' . ($is_free_absen ? 'Free Absent' : 'In Radius') . "\n\n" . 'Hati-hati di jalan!';
                    $this->sendWA($employee->mobile, $isi_pesan);

                    Storage::disk('public')->put($file, $image_base64);
                    echo 'success|Terimakasih, Anda Berhasil Absen Pulang|out';
                } else {
                    // LOGIKA ABSEN MASUK
                    $data = [
                        'employee_id' => $nik,
                        'date' => $date,
                        'time_in' => $time_now,
                        'photo_in' => $fileName,
                        'location_in' => $lokasi,
                        'working_day_id' => $jam_kerja->id,
                        'status' => 'H',
                    ];
                    Presence::create($data);

                    // Kirim WA Masuk
                    $isi_pesan = "✅ *PRESENSI MASUK BERHASIL*\n\n" . 'Nama: *' . $employee->first_name . ' ' . $employee->last_name . "*\n" . 'Jam: ' . $time_now . " WIB\n" . 'Status: ' . ($is_free_absen ? 'Free Absent' : 'In Radius') . "\n\n" . 'Selamat bekerja!';
                    $this->sendWA($employee->mobile, $isi_pesan);

                    Storage::disk('public')->put($file, $image_base64);
                    echo 'success|Terimakasih Anda Berhasil Absen Masuk|in';
                }
            }
        } catch (\Throwable $th) {
            echo 'error|Maaf, terjadi kesalahan: ' . $th->getMessage();
        }
    }

    /**
     * Buat fungsi private agar kode lebih bersih
     */
    private function sendWA($nomor, $pesan)
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://wagateway.dinastikreatifindonesia.com/send-message',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => [
                'message' => $pesan,
                'number' => $nomor,
                'file_dikirim' => '',
            ],
        ]);
        $response = curl_exec($curl);
        // dd($response);
        curl_close($curl);
        return $response;
    }

    public function editProfile()
    {
        $user = Auth::user();
        $employee = $user->employee;
        // $employee=DB::table('employee')
        // ->where('user_id',$id)
        // ->first();
        return view('frontend.presensi.editProfile', compact('employee'));
    }

    public function updateProfile(Request $request, $id)
    {
        // return $request->all();
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'nullable',
            'mobile' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,png,jpeg',
            'password' => 'nullable|string|min:8',
        ]);
        try {
            // return $validate;
            $employee = Employee::findOrFail($id);
            if ($request->hasFile('photo')) {
                if ($employee->avatar) {
                    Storage::disk('public')->delete($employee->avatar);
                }
                $employee->avatar = $request->file('photo')->store('avatar', 'public');
            }
            $employee->first_name = $request->first_name;
            $employee->last_name = $request->last_name;
            $employee->mobile = $request->mobile;
            $employee->address = $request->address;
            $employee->save();

            $user = User::findOrFail($employee->user_id);
            // dd($user);

            if (!empty($request->password)) {
                $user->password = Hash::make($request->password);
            } else {
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
        $nama_bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'July', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        return view('frontend.presensi.history', compact('nama_bulan'));
    }


    public function getHistory(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $nik = Auth::user()->employee->id;

        $history = DB::table('presences')
            ->select('presences.*', 'working_hours.name as nama_jadwal', 'working_hours.entry_time as jam_masuk_seharusnya', 'working_hours.out_time as jam_pulang_seharusnya')
            // 1. Join ke working_days (Prioritas Utama untuk menghindari duplikat)
            // Ini menghubungkan ID jadwal yang tersimpan di baris absen tersebut
            ->leftJoin('working_days', 'presences.working_day_id', '=', 'working_days.id')

            // 2. Join ke working_hours melalui working_days (permintaan user)
            // Gunakan COALESCE atau Join bercabang jika workinghour_id bisa datang dari mana saja
            ->leftJoin('working_hours', function ($join) {
                $join->on('working_days.workinghour_id', '=', 'working_hours.id');
            })

            /* CATATAN:
           Jika Anda tetap butuh join ke Departemen sebagai cadangan,
           pastikan menggunakan DISTINCT atau pastikan kolom 'days' di database
           sama dengan output SQL Server.
        */

            ->whereRaw('MONTH(presences.date) = ?', [$bulan])
            ->whereRaw('YEAR(presences.date) = ?', [$tahun])
            ->where('presences.employee_id', $nik)
            ->orderBy('presences.date', 'asc')
            ->get();

        return view('frontend.presensi.getHistory', compact('history'));
    }
    public function izin(Request $request)
    {
        $employee_id = Auth::user()->employee->id;

        // 1. Cek apakah ada interaksi filter.
        // Jika tidak ada parameter 'bulan' di URL, kita gunakan bulan berjalan sebagai default awal.
        // Jika user memilih "Semua Bulan", maka $request->bulan akan berisi string kosong ("")
        $bulan = $request->has('bulan') ? $request->bulan : date('n');
        $tahun = $request->get('tahun', date('Y'));

        $query = DB::table('submissions')->where('employee_id', $employee_id)->whereYear('date', $tahun);

        // 2. Logika Filter: Hanya filter jika $bulan memiliki nilai (bukan null dan bukan "")
        if ($bulan !== '' && $bulan !== null) {
            $query->whereMonth('date', $bulan);
        }

        $submissions = $query->orderBy('date', 'desc')->get();
        // dd($dataizin);
        return view('frontend.presensi.izin', compact('submissions'));
    }
    public function pengajuan()
    {
        return view('frontend.presensi.pengajuan');
    }

    public function cektglpengajuan(Request $request)
    {
        $user_id = Auth::user()->employee->id;
        $tgl_input = $request->tgl_izin;

        // Pastikan input tidak kosong
        if (!$tgl_input) {
            return response()->json(['status' => 'success']);
        }

        // Pengecekan Submission
        $cekSubmission = \App\Models\Submission::where('employee_id', $user_id)
            ->where('status', '!=', 2) // Bukan ditolak
            ->where(function ($query) use ($tgl_input) {
                $query
                    ->where(function ($q) use ($tgl_input) {
                        // Skenario A: Tanggal input ada di rentang date s/d end_date
                        $q->whereNotNull('end_date')->where('date', '<=', $tgl_input)->where('end_date', '>=', $tgl_input);
                    })
                    ->orWhere(function ($q) use ($tgl_input) {
                        // Skenario B: end_date NULL, maka cek apakah kolom date sama dengan input
                        $q->whereNull('end_date')->where('date', $tgl_input);
                    })
                    ->orWhere(function ($q) use ($tgl_input) {
                        // Skenario C: Jaga-jaga jika input tepat sama dengan salah satu kolom
                        $q->where('date', $tgl_input)->orWhere('end_date', $tgl_input);
                    });
            })
            ->first();

        if ($cekSubmission) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sudah ada pengajuan pada tanggal tersebut.',
            ]);
        }

        // 2. Pengecekan Presensi (Pastikan nama kolom tgl_presensi benar)
        $cekAbsen = DB::table('presences')->where('employee_id', $user_id)->whereDate('date', $tgl_input)->whereNotNull('jam_in')->whereNotNull('jam_out')->exists();

        if ($cekAbsen) {
            return response()->json([
                'status' => 'error',
                'message' => 'Absensi sudah lengkap.',
            ]);
        }

        return response()->json(['status' => 'success']);
    }

    public function storeizin(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'tgl_izin' => 'required|date',
            'status' => 'required|string|max:50', // condition
            'ket' => 'required|string|max:255', // information
            'photo' => 'nullable|image|mimes:jpg,png,jpeg|max:2048', // Tambahkan validasi file
        ]);

        // 2. Ambil Employee ID & Format Tanggal
        // Menggunakan Eloquent Relasi lebih baik, tapi kode Anda sudah berfungsi:
        $employee_id = Auth::user()->employee->id;

        // Carbon::parse sudah otomatis mengembalikan objek Carbon.
        // Jika tgl_izin dari form sudah berupa Y-m-d, format ini opsional.
        $tgl_izin = \Carbon\carbon::parse($request->tgl_izin)->toDateString();

        // 3. Siapkan Data Dasar
        $data = [
            'employee_id' => $employee_id,
            'date' => $tgl_izin,
            'condition' => $request->status, // Pastikan nama kolom database sesuai
            'information' => $request->ket, // Pastikan nama kolom database sesuai
        ];

        // 4. Penanganan File Kondisional
        if ($request->hasFile('photo')) {
            // Simpan file dan tambahkan path ke array $data
            $data['photo'] = $request->file('photo')->store('submissions', 'public');
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

            flash()->error('Gagal membuat pengajuan. Silakan coba lagi.' . $e->getMessage());
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

        try {
            $tanggalAwalDB = \Carbon\Carbon::createFromFormat('d-m-Y', $tanggalAwal)->toDateString();
            $tanggalAkhirDB = \Carbon\Carbon::createFromFormat('d-m-Y', $tanggalAkhir)->toDateString();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Format tanggal tidak valid.'], 422);
        }

        // 2. Query Data dengan Eager Loading
        $presences = DB::table('presences')
            ->select('presences.*', 'employees.first_name', 'employees.last_name', 'positions.name as position_name', 'departements.name as departement_name', 'working_hours.entry_time', 'working_hours.name as tipe_jam_kerja')
            ->join('employees', 'presences.employee_id', '=', 'employees.id')
            ->join('positions', 'employees.position_id', '=', 'positions.id')
            ->join('departements', 'positions.departement_id', '=', 'departements.id')
            ->leftJoin('working_days', 'presences.working_day_id', '=', 'working_days.id')
            ->leftJoin('working_hours', 'working_days.workinghour_id', '=', 'working_hours.id')
            ->whereBetween('date', [$tanggalAwalDB, $tanggalAkhirDB])
            ->get();
        // dd($presences);
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
        $nama_bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'July', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        return view('frontend.presensi.reportpresence', compact('nama_bulan', 'employees'));
    }

    public function cetakreport(Request $request)
    {
        $id = $request->id;
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $nama_bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        $employee = Employee::find($id);
        $setting = DB::table('settings')->first(); // Ambil data setting perusahaan

        $presences = DB::table('presences')->leftJoin('working_days', 'presences.working_day_id', '=', 'working_days.id')->leftJoin('working_hours', 'working_days.workinghour_id', '=', 'working_hours.id')->where('presences.employee_id', $id)->whereMonth('date', $bulan)->whereYear('date', $tahun)->orderBy('date', 'asc')->get();

        $data = compact('bulan', 'tahun', 'nama_bulan', 'employee', 'presences', 'setting');

        if (isset($_POST['export-excel'])) {
            $time = date('dmY_Hi');
            header('Content-type: application/vnd.ms-excel');
            header("Content-Disposition: attachment; filename=Report_Presensi_{$employee->nik}_{$time}.xls");
            return view('frontend.presensi.cetakReportExcel', $data);
        }

        return view('frontend.presensi.cetakReport', $data);
    }


    public function rekapPresence(Request $request)
    {
        $nama_bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'July', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        // 1. Logika Range Tanggal Dinamis
        $bulan = $request->get('bulan', date('n'));
        $tahun = $request->get('tahun', date('Y'));

        // Cek apakah user mengirimkan input tanggal manual dari frontend
        if ($request->has('dari') && $request->has('sampai')) {
            $tgl_mulai = $request->dari;
            $tgl_selesai = $request->sampai;
        } else {
            // Default: Periode Tutup Buku 26 s/d 25
            $tgl_mulai = Carbon::create($tahun, $bulan, 26)->subMonth()->format('Y-m-d');
            $tgl_selesai = Carbon::create($tahun, $bulan, 25)->format('Y-m-d');
        }

        // 2. Tarik Data Master ke Memory (Tetap sama agar cepat)
        $holidays = DB::table('holidays')
            ->whereBetween('holiday_date', [$tgl_mulai, $tgl_selesai])
            ->pluck('holiday_date')
            ->toArray();

        $all_presences = DB::table('presences')
            ->whereBetween('date', [$tgl_mulai, $tgl_selesai])
            ->get()
            ->groupBy('employee_id');

        $all_submissions = DB::table('submissions')
            ->where('status', 1)
            ->where(function ($q) use ($tgl_mulai, $tgl_selesai) {
                $q->whereBetween('date', [$tgl_mulai, $tgl_selesai])->orWhereBetween('end_date', [$tgl_mulai, $tgl_selesai]);
            })
            ->get()
            ->groupBy('employee_id');

        $all_schedules = DB::table('working_hour_dept as whd')->join('working_hour_dept_detail as whdd', 'whd.id', '=', 'whdd.whd_id')->join('working_hours as wh', 'whdd.workinghour_id', '=', 'wh.id')->select('whd.dept_code', 'whdd.days', 'wh.name as shift_name', 'wh.id as working_hour_id')->get()->groupBy('dept_code');

        $hari_indo_map = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];

        $employees = DB::table('employees as emp')->join('positions as ps', 'emp.position_id', '=', 'ps.id')->join('departements as dept', 'ps.departement_id', '=', 'dept.id')->select('emp.id', 'emp.first_name', 'emp.last_name', 'emp.nik', 'dept.id as dept_id', 'ps.name as position_name')->get();

        // 3. Mapping Data (Looping berdasarkan range dinamis)
        $period = CarbonPeriod::create($tgl_mulai, $tgl_selesai);

        $rekap = $employees->map(function ($emp) use ($period, $holidays, $all_presences, $all_submissions, $all_schedules, $hari_indo_map) {
            $my_presences = $all_presences->get($emp->id) ?? collect([]);
            $my_subs = $all_submissions->get($emp->id) ?? collect([]);
            $my_schedule = $all_schedules->get($emp->dept_id) ?? collect([]);

            // Reset Counter per Karyawan
            $emp->hadir = $my_presences->count();
            $emp->izin = 0;
            $emp->sakit = 0;
            $emp->cuti = 0;
            $emp->alpa = 0;
            $emp->hari_kerja_efektif = 0;

            foreach ($period as $date) {
                $tgl = $date->format('Y-m-d');
                $hari_indo = $hari_indo_map[$date->format('l')];

                $shift = $my_schedule->where('days', $hari_indo)->first();
                $is_working_shift = $shift && trim(strtolower($shift->shift_name)) != 'libur';
                $is_national_holiday = in_array($tgl, $holidays);

                if ($is_working_shift && !$is_national_holiday) {
                    $emp->hari_kerja_efektif++;

                    $ada_presensi = $my_presences->where('date', $tgl)->first();
                    $ada_izin = $my_subs->first(fn($item) => $tgl >= $item->date && $tgl <= $item->end_date);

                    if (!$ada_presensi) {
                        if ($ada_izin) {
                            if ($ada_izin->condition == 1) {
                                $emp->izin++;
                            } elseif (in_array($ada_izin->condition, [2, 3])) {
                                $emp->sakit++;
                            } elseif ($ada_izin->condition == 4) {
                                $emp->cuti++;
                            }
                        } else {
                            // Jika hari ini <= hari ini, hitung alpa
                            if ($tgl <= date('Y-m-d')) {
                                $emp->alpa++;
                            }
                        }
                    }
                }
            }
            return $emp;
        });

        $periode_teks = Carbon::parse($tgl_mulai)->format('d M Y') . ' - ' . Carbon::parse($tgl_selesai)->format('d M Y');

        // LOGIKA EXPORT PDF (Matrix)
        if ($request->get('type') == 'pdf') {
            $setting = DB::table('settings')->first();
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('frontend.presensi.rekap_matrix_pdf', [
                'rekap' => $rekap,
                'periode_teks' => $periode_teks,
                'period' => $period,
                'holidays' => $holidays,
                'all_submissions' => $all_submissions,
                'all_schedules' => $all_schedules,
                'hari_indo_map' => $hari_indo_map,
                'all_presences' => $all_presences,
                'setting' => $setting,
            ])->setPaper('f4', 'landscape');

            return $pdf->stream('Rekap_Presensi.pdf');
        }

        if ($request->get('type') == 'excel') {
            $filename = 'Rekap_Presensi_' . date('dmY_His') . '.xls';

            header('Content-Type: application/vnd.ms-excel');
            header("Content-Disposition: attachment; filename=\"$filename\"");
            header('Pragma: no-cache');
            header('Expires: 0');

            return view('frontend.presensi.rekap_matrix_excel', [
                'rekap' => $rekap,
                'periode_teks' => $periode_teks,
                'period' => $period,
                'holidays' => $holidays,
                'all_submissions' => $all_submissions,
                'all_schedules' => $all_schedules,
                'hari_indo_map' => $hari_indo_map,
                'all_presences' => $all_presences,
            ]);
        }

        return view('frontend.presensi.rekappresence', [
            'rekap' => $rekap,
            'periode_teks' => $periode_teks,
            'bulan_aktif' => $bulan,
            'tahun_aktif' => $tahun,
            'tgl_mulai' => $tgl_mulai,
            'tgl_selesai' => $tgl_selesai,
            'period' => $period,
        ]);
    }
    public function cetakrekap(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $nama_bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        $select = ['p.employee_id', 'e.nik', "e.first_name + ' ' + e.last_name AS name", 'wh.entry_time', 'out_time'];

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
            ->leftJoin('working_days as wd', 'p.working_day_id', '=', 'wd.id')
            ->leftJoin('working_hours as wh', 'wd.workinghour_id', '=', 'wh.id')
            ->whereRaw('DATEPART(month, p.date) = ? AND DATEPART(year, p.date) = ?', [$bulan, $tahun])
            ->groupBy('p.employee_id', 'e.nik', 'e.first_name', 'e.last_name', 'wh.entry_time', 'wh.out_time')
            ->get();

        // untuk tombol export
        if (isset($_POST['export-excel'])) {
            $time = date('d-M-Y H:i:s');

            // 1. PERBAIKAN CONTENT-TYPE
            // Gunakan application/vnd.ms-excel (untuk format .xls) atau
            // application/vnd.openxmlformats-officedocument.spreadsheetml.sheet (untuk .xlsx)
            header('Content-type: application/vnd.ms-excel');

            // 2. PERBAIKAN FILENAME
            // Pastikan ekstensi file sesuai dengan Content-type
            header("Content-Disposition: attachment; filename=Rekap Presensi Karyawan $time.xls");

            // Tambahkan header berikut untuk kompatibilitas browser
            header('Pragma: no-cache');
            header('Expires: 0');

            // Di sini, Anda harus menambahkan kode untuk menampilkan (echo) data HTML tabel
            // yang ingin Anda export ke Excel.
        }

        return view(
            'frontend.presensi.cetakRekap',
            compact(
                'bulan',
                'tahun',
                'nama_bulan',
                'presences', // Kirim semua data presensi mentah
            ),
        );
    }

    public function submission()
    {
        $submissions = Submission::orderBy('id', 'desc')->with('employee')->get();
        return view('frontend.presensi.submission', compact('submissions'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:1,2', // 1: Approved, 2: Rejected
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                // 1. Ambil data pengajuan
                $submission = DB::table('submissions')->where('id', $id)->first();

                if (!$submission) {
                    throw new \Exception('Data pengajuan tidak ditemukan.');
                }

                if ($submission->status == '1') {
                    throw new \Exception('Pengajuan ini sudah disetujui sebelumnya.');
                }

                // 2. Jika Status diubah menjadi APPROVED (1)
                if ($request->status == '1') {
                    // --- LOGIKA A: KOREKSI PRESENSI (Condition 5) ---
                    if ($submission->condition == 5) {
                        $cekAbsen = DB::table('presences')->where('employee_id', $submission->employee_id)->where('date', $submission->date)->first();

                        $dataAbsen = [
                            'employee_id' => $submission->employee_id,
                            'date' => $submission->date,
                            'time_in' => $submission->jam_in_pengajuan ?? ($cekAbsen->time_in ?? null),
                            'time_out' => $submission->jam_out_pengajuan ?? ($cekAbsen->time_out ?? null),
                            'status' => 'P',
                            'updated_at' => now(),
                        ];

                        if ($cekAbsen) {
                            DB::table('presences')->where('id', $cekAbsen->id)->update($dataAbsen);
                        } else {
                            $dataAbsen['created_at'] = now();
                            DB::table('presences')->insert($dataAbsen);
                        }
                    }

                    // --- LOGIKA B: CUTI/IZIN/SAKIT (Potong Kuota jika tipe Cuti) ---
                    else {
                        $employee = DB::table('employees')->where('id', $submission->employee_id)->first();
                        $jml_hari = $submission->total_days;

                        // Potong kuota hanya jika jenisnya adalah CUTI (Condition 4)
                        if ($submission->condition == 4) {
                            $bulan_sekarang = date('n');
                            $sisa_tahun_lalu = $bulan_sekarang <= 3 ? $employee->kuota_tahun_lalu ?? 0 : 0;
                            $sisa_tahun_ini = $employee->kuota_tahun_ini ?? 0;

                            if ($jml_hari > $sisa_tahun_lalu + $sisa_tahun_ini) {
                                throw new \Exception('Kuota karyawan tidak mencukupi.');
                            }

                            if ($sisa_tahun_lalu >= $jml_hari) {
                                $new_lalu = $sisa_tahun_lalu - $jml_hari;
                                $new_ini = $sisa_tahun_ini;
                            } else {
                                $sisa_kurang = $jml_hari - $sisa_tahun_lalu;
                                $new_lalu = 0;
                                $new_ini = $sisa_tahun_ini - $sisa_kurang;
                            }

                            DB::table('employees')
                                ->where('id', $employee->id)
                                ->update([
                                    'kuota_tahun_lalu' => $new_lalu,
                                    'kuota_tahun_ini' => $new_ini,
                                    'updated_at' => now(),
                                ]);
                        }

                        // Tambahkan record ke tabel presences untuk Izin/Sakit/Cuti
                        $start = \Carbon\Carbon::parse($submission->date);
                        $end = $submission->end_date ? \Carbon\Carbon::parse($submission->end_date) : $start;

                        for ($date = $start; $date->lte($end); $date->addDay()) {
                            if ($date->isWeekend()) {
                                continue;
                            } // Skip akhir pekan

                            DB::table('presences')->updateOrInsert(
                                [
                                    'employee_id' => $submission->employee_id,
                                    'tgl_presensi' => $date->format('Y-m-d'),
                                ],
                                [
                                    'status' => $submission->condition,
                                    'jam_in' => null,
                                    'jam_out' => null,
                                    'keterangan' => $submission->information,
                                    'updated_at' => now(),
                                ],
                            );
                        }
                    }
                }

                // 3. Update Status di Tabel Submissions
                DB::table('submissions')
                    ->where('id', $id)
                    ->update([
                        'status' => $request->status,
                        'updated_at' => now(),
                    ]);
            });

            $msg = $request->status == '1' ? 'Pengajuan disetujui dan data absensi diperbarui.' : 'Pengajuan telah ditolak.';
            return redirect()
                ->back()
                ->with(['success' => $msg]);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with(['error' => 'Gagal: ' . $e->getMessage()]);
        }
    }
}
