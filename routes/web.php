<?php

use App\Http\Controllers\BerandaController;
use App\Http\Controllers\DiagnosaPasienController;
use App\Http\Controllers\KamarController;
use App\Http\Controllers\KategoriPerawatanController;
use App\Http\Controllers\LaporanDiagnosaDinkesController;
use App\Http\Controllers\LaporanDiagnosaPenyakitController;
use App\Http\Controllers\LaporanIGDController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OperasiController;
use App\Http\Controllers\PaketOperasiController;
use App\Http\Controllers\PasienBayiController;
use App\Http\Controllers\PenjabController;
use App\Http\Controllers\PersalinanController;
use App\Http\Controllers\PoliklinikController;
use App\Http\Controllers\RalanController;
use App\Http\Controllers\RanapController;
use App\Http\Controllers\SepController;
use App\Http\Controllers\TarifLaboratorium;
use App\Http\Controllers\TarifRalanController;
use App\Http\Controllers\TarifRanapController;
use App\Models\Dokter;
use Illuminate\Support\Facades\Route;

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
    Route::get('/', [BerandaController::class, 'index'])->name('index');
    Route::get('/beranda', [BerandaController::class, 'dataPembayaran']);
    Route::get('/beranda/kunjungan', [BerandaController::class, 'countTotal']);
    Route::get('/beranda/pembiayaan', [BerandaController::class, 'pembiayaan']);
    Route::get('/beranda/pembiayaan/ranap', [BerandaController::class, 'countPembiayaanRanap']);
    Route::get('/beranda/pembiayaan/ralan', [BerandaController::class, 'countPembiayaanRalan']);
    Route::get('/beranda/status', [BerandaController::class, 'statusPasien']);
    Route::get('/beranda/dokter/{tahun?}/{bulan?}', [BerandaController::class, 'jsonKunjunganDokter']);
    Route::get('/beranda/ralan', [RalanController::class, 'diagramRalanPoli']);
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
    Route::get('/igd/hitung', [BerandaController::class, 'countIGD']);
    Route::get('/igd/json', [LaporanIGDController::class, 'json']);
    Route::get('/igd/pasien/json', [LaporanIGDController::class, 'jsonPasienIgd']);

    Route::get('/ralan', [RalanController::class, 'index']);
    Route::get('/ralan/hitung', [BerandaController::class, 'countRalan']);
    Route::get('/ralan/json', [RalanController::class, 'json']);
    Route::get('/ralan/laporan', [RalanController::class, 'viewLaporanBpjs']);
    Route::get('/ralan/laporan/json', [RalanController::class, 'jsonLaporanBpjs']);
    Route::get('/ralan/bayar/json', [RalanController::class, 'jsonStatusBayar']);
    Route::get('/ralan/daftar/json', [RalanController::class, 'jsonStatusDaftar']);
    Route::get('/ralan/poli/json', [RalanController::class, 'jsonPoli']);
    Route::get('/ralan/anak/json', [RalanController::class, 'jsonDokterAnak']);
    Route::get('/ralan/obgyn/json', [RalanController::class, 'jsonDokterObgyn']);
    Route::get('/ralan/test', [RalanController::class, 'jsonKunjunganKelurahan']);
    Route::get('/ralan/sep', [RalanController::class, 'sepRalan']);
    Route::get('/ralan/sep/json', [RalanController::class, 'jsonSepRalan']);
    Route::get('/ralan/sep/jumlah', [SepController::class, 'jumlahSepRalan']);

    Route::get('/ranap', [RanapController::class, 'index']);
    Route::get('/ranap/hitung', [BerandaController::class, 'countRanap']);
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

    Route::get('/tarif/kamar', [KamarController::class, 'index']);
    Route::get('/tarif/kamar/json', [KamarController::class, 'getTarif']);
    Route::get('/tarif/kamar/{kd_bagsal?}', [KamarController::class, 'getTarifById']);
    Route::post('/tarif/kamar/simpantarif', [KamarController::class, 'setTarifKamar']);

    Route::get('/tarif/kategori', [KategoriPerawatanController::class, 'getAllKategori']);
    Route::get('/tarif/kategori/{kategori?}/{attr?}', [KategoriPerawatanController::class, 'getKategoryByName']);

    Route::get('tarif/ralan', [TarifRalanController::class, 'index']);
    Route::get('tarif/ralan/json', [TarifRalanController::class, 'getTarif']);
    Route::get('tarif/ralan/{id?}', [TarifRalanController::class, 'getTarifById']);
    Route::get('tarif/akhir', [TarifRalanController::class, 'getTarifAkhir']);
    Route::post('tarif/ralan/simpantarif', [TarifRalanController::class, 'setTarifRalan']);
    Route::post('tarif/ralan/tambahtarif', [TarifRalanController::class, 'addTarifRalan']);

    Route::get('tarif/ranap', [TarifRanapController::class, 'index']);
    Route::get('tarif/ranap/json', [TarifRanapController::class, 'getTarif']);
    Route::get('tarif/ranap/akhir', [TarifRanapController::class, 'getLastTarif']);
    Route::get('tarif/ranap/{id?}', [TarifRanapController::class, 'getTarifById']);
    Route::post('tarif/ranap/ubah', [TarifRanapController::class, 'setTarif']);
    Route::post('tarif/ranap/tambah', [TarifRanapController::class, 'addTarif']);

    Route::get('tarif/lab', [TarifLaboratorium::class, 'index']);
    Route::get('tarif/lab/akhir', [TarifLaboratorium::class, 'getLastTarif']);
    Route::get('tarif/lab/json', [TarifLaboratorium::class, 'getTarif']);
    Route::get('tarif/lab/{id?}', [TarifLaboratorium::class, 'getTarifById']);
    Route::post('tarif/lab/ubah', [TarifLaboratorium::class, 'setTarif']);
    Route::post('tarif/lab/tambah', [TarifLaboratorium::class, 'addTarif']);

    Route::get('tarif/operasi', [PaketOperasiController::class, 'index']);
    Route::get('tarif/operasi/akhir', [PaketOperasiController::class, 'getLastTarif']);
    Route::get('tarif/operasi/json', [PaketOperasiController::class, 'getTarif']);
    Route::get('tarif/operasi/{id?}', [PaketOperasiController::class, 'getTarifById']);
    Route::post('tarif/operasi/tambah', [PaketOperasiController::class, 'addTarif']);
    Route::post('tarif/operasi/ubah', [PaketOperasiController::class, 'setTarif']);

    Route::get('/persalinan', [PersalinanController::class, 'index']);
    Route::get('/persalinan/json', [PersalinanController::class, 'json']);

    Route::get('/poli', [PoliklinikController::class, 'getAllPoliklinik']);

    Route::get('/penjab', [PenjabController::class, 'getAllPenjab']);

    Route::get('/poli/{kd_sps}', function ($kd_sps) {
        $dokter = Dokter::all()
            ->where('kd_sps', $kd_sps)
            ->where('status', 1);
        return response()->json($dokter);
    });
});

// Route::get('/test', [SepController::class, 'getSep']);
