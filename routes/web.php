<?php

use App\Http\Controllers\Actions\SkriningTbDataTableAction;
use App\Http\Controllers\AskepKandunganRalanController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\Collection\KunjunganPoliklinikDokterCollection;
use App\Http\Controllers\DiagnosaPasienController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\FarmasiController;
use App\Http\Controllers\KamarController;
use App\Http\Controllers\KamarInapController;
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
use App\Http\Controllers\RegPeriksaController;
use App\Http\Controllers\RekamMedisController;
use App\Http\Controllers\ResepObatController;
use App\Http\Controllers\ResumePasienRanap;
use App\Http\Controllers\SepController;
use App\Http\Controllers\SpesialisController;
use App\Http\Controllers\TarifLaboratorium;
use App\Http\Controllers\TarifRalanController;
use App\Http\Controllers\TarifRanapController;
use App\Http\Controllers\TindakanController;
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

Route::middleware('auth')->group(function () {
    Route::get('/logout', [LoginController::class, 'logout']);
    Route::get('/', [BerandaController::class, 'index'])->name('index');

    Route::get('/operasi', [OperasiController::class, 'index']);
    Route::get('/operasi/json', [OperasiController::class, 'json']);
    Route::get('/operasi/sectio', [OperasiController::class, 'viewSectio']);
    Route::get('/operasi/sectio/json', [OperasiController::class, 'ambilSectio']);
    Route::get('/diagram/operasi/{tahun}', [OperasiController::class, 'diagram']);

    // monitoring rekam medis
    Route::get('/monitoring/rm/ugd', [RekamMedisController::class, 'monitoringUgd']);
    Route::get('/monitoring/rm/ranap', [RekamMedisController::class, 'monitoringRanap']);

    // SHK
    Route::get('/monitoring/shk', [ResumePasienRanap::class, 'shk']);

    // Report Pengisian ERM
    Route::prefix('report')->group(function ($route) {
        $route->get('erm', [RekamMedisController::class, 'pengisianErm']);
    });

    Route::middleware('rm')->group(function () {
        Route::get('/rekammedis', [DiagnosaPasienController::class, 'index']);
        Route::get('/rekammedis/json', [DiagnosaPasienController::class, 'json']);

        Route::get('/rekammedis/dinkes', [LaporanDiagnosaDinkesController::class, 'index']);
        Route::get('/rekammedis/dinkes/json', [LaporanDiagnosaDinkesController::class, 'json']);

        Route::get('/rekammedis/penyakit', [LaporanDiagnosaPenyakitController::class, 'index']);
        Route::get('/rekammedis/penyakit/json', [LaporanDiagnosaPenyakitController::class, 'json']);
        Route::get('/rekammedis/cari', [LaporanDiagnosaPenyakitController::class, 'cariDiagnosa']);
        Route::get('/penyakit/cari', [LaporanDiagnosaPenyakitController::class, 'get']);

        Route::get('/rekammedis/pasientb', [DiagnosaPasienController::class, 'pasienTb']);
        Route::get('/rekammedis/pasientb/json', [DiagnosaPasienController::class, 'jsonPasienTb']);
    });

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
    Route::get('/ralan/jk/json', [RalanController::class, 'jsonPoliJk']);
    Route::get('/ralan/test', [RalanController::class, 'jsonKunjunganKelurahan']);
    Route::get('/ralan/sep', [RalanController::class, 'sepRalan']);
    Route::get('/ralan/sep/json', [RalanController::class, 'jsonSepRalan']);
    Route::get('/ralan/sep/jumlah', [SepController::class, 'jumlahSepRalan']);
    Route::get('/ralan/kandungan', [RalanController::class, 'ambilKandungan']);

    Route::get('/ralan/kandungan/json', [AskepKandunganRalanController::class, 'ambil']);

    Route::get('/ranap', [RanapController::class, 'index']);
    Route::get('/ranap/hitung', [BerandaController::class, 'countRanap']);
    Route::get('/ranap/json', [RanapController::class, 'json']);
    Route::get('/ranap/laporan', [RanapController::class, 'laporanBpjs']);
    Route::get('/ranap/laporan/json', [RanapController::class, 'jsonRanap']);
    Route::get('/ranap/bayi', [PasienBayiController::class, 'index']);
    Route::get('/ranap/bayi/json', [PasienBayiController::class, 'json']);
    Route::get('/ranap/bayis', [PasienBayiController::class, 'getTahun']);
    Route::get('/ranap/visit/json', [RanapController::class, 'jsonVisitDokter']);
    Route::get('/ranap/visit/cppt/json', [RanapController::class, 'getCpptVisitDokter']);
    Route::get('/ranap/visit', [RanapController::class, 'viewVisitDokter']);
    Route::get('/ranap/bayar/json', [RanapController::class, 'jsonStatusBayar']);
    Route::get('/ranap/transfusi', [RanapController::class, 'viewTransfusi']);
    Route::get('/ranap/transfusi/json', [RanapController::class, 'jsonTransfusi']);
    Route::get('/ranap/jk/json', [RanapController::class, 'jsonGenderRanap']);
    Route::get('/ranap/transfusi/rekap/json', [RanapController::class, 'jsonRekapTransfusi']);
    Route::get('/ranap/pembiayaan/json', [RanapController::class, 'jsonpPembiayaan']);

    Route::get('/kamar', [KamarInapController::class, 'jumlahKamar']);
    Route::get('/kamar/rekap', [KamarInapController::class, 'rekapKunjungan']);

    Route::middleware('admin')->group(function () {
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
    });

    Route::get('/persalinan', [PersalinanController::class, 'index']);
    Route::get('/persalinan/json', [PersalinanController::class, 'json']);

    Route::get('/poli', [PoliklinikController::class, 'getAllPoliklinik']);
    Route::get('/poli/show/{kd_poli}', [PoliklinikController::class, 'show']);

    Route::get('/penjab', [PenjabController::class, 'getAllPenjab']);

    Route::get('/tindakan', [TindakanController::class, 'index']);
    Route::get('/tindakan/json', [TindakanController::class, 'rekapTindakan']);

    Route::get('/dokter', [DokterController::class, 'semuaDokter']);

    Route::get('/poli/{kd_sps}', function ($kd_sps) {
        $dokter = Dokter::all()
            ->where('kd_sps', $kd_sps)
            ->where('status', 1);
        return response()->json($dokter);
    });

    Route::get('farmasi/resep', [ResepObatController::class, 'index']);
    Route::get('farmasi/resep/waktu', [ResepObatController::class, 'waktu']);
    Route::get('farmasi/resep/waktu/json', [ResepObatController::class, 'ambilTabel']);
    Route::get('farmasi/resep/ambil', [RegPeriksaController::class, 'ambilResepTabel']);
    Route::get('farmasi/resep/hitung', [RegPeriksaController::class, 'hitungStatusResep']);

    Route::get('farmasi/dashboard', [FarmasiController::class, 'umum']);
    Route::get('farmasi/dashboard/persediaan', [FarmasiController::class, 'persediaan']);

    Route::prefix('grafik')->group(function ($route) {
        $route->prefix('kunjungan')->group(function ($route) {
            $route->get('poliklinik/{year?}/{month?}/{dokter?}', [KunjunganPoliklinikDokterCollection::class, 'getByDokter']);
            $route->get('tahun/{year?}', [\App\Http\Controllers\Collection\RegPeriksaCollection::class, 'getByYear']);

        });
        $route->prefix('penjab')->group(function ($route) {
            $route->get('bpjs/{year?}/{month?}', [\App\Http\Controllers\Collection\PembiayaanPasienCollection::class, 'getPenjabBpjs']);
            $route->get('{year?}/{month?}', [\App\Http\Controllers\Collection\PembiayaanPasienCollection::class, 'getPembiayaan']);

        });
    });

    Route::prefix('datatable')->group(function ($route) {
        $route->get('tb/skrining/{year?}/{month?}', SkriningTbDataTableAction::class);
    });

});
Route::get('spesialis', [SpesialisController::class, 'all']);
Route::get('spesialis/dokter', [SpesialisController::class, 'getSpesialisDokter']);

require __DIR__ . '/partial/rekammedis/tb.php';
require __DIR__ . '/partial/rekammedis/bto.php';
require __DIR__ . '/partial/beranda.php';
