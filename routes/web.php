<?php

use Carbon\Carbon;
use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\Operasi;
use App\Models\Persalinan;
use App\Models\RegPeriksa;
use Illuminate\Support\Arr;
use App\Models\DiagnosaPasien;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RalanController;
use App\Http\Controllers\RanapController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\OperasiController;
use App\Http\Controllers\LaporanIGDController;
use App\Http\Controllers\PasienBayiController;
use App\Http\Controllers\PersalinanController;
use App\Http\Controllers\DiagnosaPasienController;
use App\Http\Controllers\DiagramOperasiController;
use App\Http\Controllers\KunjunganRalanController;
use App\Http\Controllers\LaporanDiagnosaDinkesController;
use App\Http\Controllers\LaporanDiagnosaPenyakitController;
use App\Http\Controllers\SepController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::get('/logout', [LoginController::class, 'logout']);

Route::middleware('auth')->group(function () {
    Route::get('/', [BerandaController::class, 'index']);
    Route::get('/beranda', [BerandaController::class, 'dataPembayaran']);
    Route::get('/beranda/dokter/{tahun}/{bulan}', [BerandaController::class, 'jsonKunjunganDokter']);
    Route::get('/operasi', [OperasiController::class, 'index']);
    Route::get('/operasi/json', [OperasiController::class, 'json']);
    Route::get('/diagram/operasi/{tahun}', [OperasiController::class, 'diagram']);

    Route::get('/rekammedis', [DiagnosaPasienController::class, 'index']);
    Route::get('/rekammedis/json', [DiagnosaPasienController::class, 'json']);

    Route::get('/rekammedis/dinkes', [LaporanDiagnosaDinkesController::class, 'index']);
    Route::get('/rekammedis/dinkes/json', [LaporanDiagnosaDinkesController::class, 'json']);

    Route::get('/rekammedis/penyakit', [LaporanDiagnosaPenyakitController::class, 'index']);
    Route::get('/rekammedis/penyakit/json', [LaporanDiagnosaPenyakitController::class, 'json']);
    Route::get('/rekammedis/cari', [LaporanDiagnosaPenyakitController::class, 'cariDiagnosa']);

    Route::get('/rekammedis/pasientb', [DiagnosaPasienController::class, 'pasienTb']);
    Route::get('/rekammedis/pasientb/json', [DiagnosaPasienController::class, 'jsonPasienTb']);

    Route::get('/igd', [LaporanIGDController::class, 'index']);
    Route::get('/igd/json', [LaporanIGDController::class, 'json']);
    Route::get('/igd/pasien/json', [LaporanIGDController::class, 'jsonPasienIgd']);

    Route::get('/ralan', [RalanController::class, 'index']);
    Route::get('/ralan/json', [RalanController::class, 'json']);
    Route::get('/ralan/laporan', [RalanController::class, 'viewLaporanBpjs']);
    Route::get('/ralan/laporan/json', [RalanController::class, 'jsonLaporanBpjs']);
    Route::get('/ralan/bayar/json', [RalanController::class, 'jsonStatusBayar']);
    Route::get('/ralan/daftar/json', [RalanController::class, 'jsonStatusDaftar']);
    Route::get('/ralan/poli/json', [RalanController::class, 'jsonPoli']);
    Route::get('/ralan/anak/json', [RalanController::class, 'jsonDokterAnak']);
    Route::get('/ralan/obgyn/json', [RalanController::class, 'jsonDokterObgyn']);
    Route::get('/ralan/diagram/poli/{tahun?}', [RalanController::class, 'diagramRalanPoli']);
    Route::get('/ralan/test', [RalanController::class, 'jsonKunjunganKelurahan']);
    Route::get('/ralan/sep', [RalanController::class, 'sepRalan']);
    Route::get('/ralan/sep/json', [RalanController::class, 'jsonSepRalan']);
    Route::get('/ralan/sep/jumlah', [SepController::class, 'jumlahSepRalan']);

    Route::get('/ranap', [RanapController::class, 'index']);
    Route::get('/ranap/json', [RanapController::class, 'json']);
    Route::get('/ranap/laporan', [RanapController::class, 'laporanBpjs']);
    Route::get('/ranap/laporan/json', [RanapController::class, 'jsonRanap']);
    Route::get('/ranap/bayi', [PasienBayiController::class, 'index']);
    Route::get('/ranap/bayi/json', [PasienBayiController::class, 'json']);
    Route::get('/ranap/bayis', [PasienBayiController::class, 'getTahun']);
    Route::get('/ranap/visit/json', [RanapController::class, 'jsonVisitDokter']);
    Route::get('/ranap/visit', [RanapController::class, 'viewVisitDokter']);
    Route::get('/ranap/bayar/json', [RanapController::class, 'jsonStatusBayar']);
    Route::get('/ranap/transfusi', [RanapController::class, 'viewTransfusi']);
    Route::get('/ranap/transfusi/json', [RanapController::class, 'jsonTransfusi']);
    Route::get('/ranap/transfusi/rekap/json', [RanapController::class, 'jsonRekapTransfusi']);


    Route::get('/persalinan', [PersalinanController::class, 'index']);
    Route::get('/persalinan/json', [PersalinanController::class, 'json']);

    Route::get('/poli/{kd_sps}', function ($kd_sps) {
        $dokter = Dokter::all()
            ->where('kd_sps',  $kd_sps)
            ->where('status', 1);
        return response()->json($dokter);
    });
});




Route::get('/test', [SepController::class, 'getSep']);





// Route::get('/test/bayi', function () {
//     $tanggal = new Carbon();
//     $data = RegPeriksa::select('reg_periksa.no_rawat', 'tgl_registrasi', 'kd_dokter', 'no_rkm_medis', 'stts_daftar', 'kd_pj', 'bridging_sep.tglsep')
//         ->leftJoin('bridging_sep', function ($join) {
//             $join->on('reg_periksa.no_rawat', '=', 'bridging_sep.no_rawat')
//                 ->whereNotNull('bridging_sep.no_rawat');
//         })
//         ->where('status_lanjut', 'Ranap')
//         ->whereHas('kamarInap', function ($query) {
//             $query->where('stts_pulang', '!=', 'Pindah Kamar');
//         })
//         ->whereHas('dokter.spesialis', function ($query) {
//             $query->whereIn('kd_sps', ['S0001', 'S0003']);
//         })
//         ->whereHas('pasien', function ($query) {
//             $query->where('nm_pasien', 'like', '%BY%');
//         })
//         ->groupBy('reg_periksa.no_rawat')
//         ->orderBy('tgl_registrasi', 'ASC')
//         ->whereBetween('tgl_registrasi', [$tanggal->startOfMonth()->toDateString(), $tanggal->lastOfMonth()->toDateString()])->get();


//     return DataTables::of($data)->make(true);
// });
