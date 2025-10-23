<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Presence;
use App\Models\Submission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Stmt\TraitUseAdaptation\Precedence;

class PresensiController extends Controller
{
    public function create(){
        $today = date('Y-m-d');
        $nik = Auth::user()->nik;
        $cek = DB::table('presences')->where('date',$today)->where('nik',$nik)->count();
        return view('frontend.presensi.create',compact('cek'));
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

        $nik = Auth::user()->nik;
        $date = date('Y-m-d');
        $time_in = \Carbon\Carbon::now()->format('H:i:s');
        $time_out = \Carbon\Carbon::now()->format('H:i:s');

        $lokasi = $request->lokasi;
        $officeLocation=[-6.216866477653331, 106.67630338286085];
        $latOffice = -6.216866477653331;
        $longOffice = 106.67630338286085;
        $userLocation = explode(",",$lokasi);
        $latUser= $userLocation[0];
        $longUser = $userLocation[1];
        $jarak = $this->distance($latOffice, $longOffice , $latUser, $longUser);
        $radius = round($jarak["meters"]);

        $image = $request->image;
        $folderPath = "absensi/";
        $cek = DB::table('presences')->where('date',$date)->where('nik',$nik)->count();
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
            'nik'           => $nik,
            'date'          => $date,
            'time_in'       => $time_in,
            'photo_in'      => $fileName,
            'location_in'   => $lokasi,
        ];
        try {
            $cek = DB::table('presences')->where('date',$date)->where('nik',$nik)->count();
            // cek radius user dengan target
            if($radius > 20){
                echo "Error_radius|Maaf Anda Berada Diluar Radius, Jarak Anda " . $radius ." meter dari kantor";
            }else{
                if($cek > 0){
                    $data_pulang =[
                        'time_out' => $time_out,
                        'photo_out' => $fileName,
                        'location_out' => $lokasi
                    ];

                    DB::table('presences')->where('date',$date)->where('nik', $nik)->update($data_pulang);
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
        $nik = Auth::user()->nik;
        $employee=DB::table('users')
        ->where('nik',$nik)
        ->first();
        return view('frontend.presensi.editProfile',compact('employee'));
    }

    public function updateProfile(Request $request)
    {
        // return $request->all();
        try {
            $request->validate([
                        'full_name'     => 'required',
                        'password'      =>'nullable|string|min:8',
                        'mobile'        => 'nullable|string|max:15',
                        'address'        => 'nullable|string|max:255',
                        'photo'        => 'nullable'
                    ]);

            // return $validate;

            $user = User::find($request->id);
            $user->full_name = $request->full_name;
            $user->mobile = $request->mobile;
            $user->address = $request->address;
            if(!empty($request->password)){
                $user->password = Hash::make($request->password);
            }else{
                unset($request->password);
            }
            if($request->hasFile('photo')){
                if($user->avatar){
                    Storage::disk('public')->delete($user->avatar);
                }
                $user->avatar = $request->file("photo")->store("avatar","public");
            }

            $user->save();
            flash()->option('zIndex', 9999)->success('Profile updated successfully');
            return redirect()->route('frontend.dashboards');
        } catch (\Exception $e) {
            flash()->error('Please fix the errors in the form');
            return back()->withErrors($e)->withInput();
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
        $nik= Auth::user()->nik;
        $history=DB::table('presences')
        ->whereRaw("MONTH(date)='$bulan'")
        ->whereRaw("YEAR(date)='$tahun'")
        ->where('nik',$nik)
        ->orderBy('date')
        ->get();
        return view('frontend.presensi.getHistory',compact('history'));
    }

    public  function izin()
    {
        $nik = Auth::user()->nik;
        // $dataizin = Submission::where('nik', $nik)->get();
        $submissions = DB::table('submissions')->where('nik',$nik)->get();

        // dd($dataizin);
        return view('frontend.presensi.izin', compact('submissions'));
    }
    public function pengajuan()
    {
        return view('frontend.presensi.pengajuan');
    }

    public function storeizin(Request $request)
    {
        $nik= Auth::user()->nik;
        $employee = DB::table('users')->where('nik',$nik)->first();
        $tgl_izin=\Carbon\Carbon::parse($request->tgl_izin)->format('Y-m-d');
        $status=$request->status;
        $ket=$request->ket;
        if($request->hasFile('photo')){
            $photo = $request->file("photo")->store("submissions","public");
            Submission::create([
                    'nik'           => $nik,
                    'date'          => $tgl_izin,
                    'condition'     => $status,
                    'information'   => $ket,
                    'photo'         => $photo,
                ]);
            flash()->success('Submission created succsessfully');
            return redirect()->route('presensi.izin');
        }else{
            Submission::create([
                    'nik'           => $nik,
                    'date'          => $tgl_izin,
                    'condition'     => $status,
                    'information'   => $ket,
                ]);
            flash()->success('Submission created succsessfully');
            return redirect()->route('presensi.izin');
        }




    }
}
