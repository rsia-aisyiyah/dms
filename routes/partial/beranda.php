<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\RegPeriksaController;
use App\Http\Controllers\RalanController;
use App\Http\Actions\CounterKunjunganByStatusLanjut;
use App\Http\Actions\DemografiKecamatanPasienRajal;
use App\Http\Actions\DemografiKelurahanPasienRajal;
use App\Http\Actions\DemografiKecamatanPasienRanap;

Route::middleware('auth')->group(function ($route) {
	$route->prefix('beranda')->group(function ($route) {
		$route->get('/kunjungan', [BerandaController::class, 'countTotal']);
		$route->get('/kunjungan/total', CounterKunjunganByStatusLanjut::class);
		$route->get('/pembiayaan', [BerandaController::class, 'pembiayaan']);
		$route->get('/pembiayaan/ranap', [BerandaController::class, 'countPembiayaanRanap']);
		$route->get('/pembiayaan/ralan', [BerandaController::class, 'countPembiayaanRalan']);
		$route->get('/status', [BerandaController::class, 'statusPasien']);
		$route->get('/dokter/{tahun?}/{bulan?}', [BerandaController::class, 'jsonKunjunganDokter']);
		$route->get('/registrasi', [RegPeriksaController::class, 'caraRegistrasi']);
		$route->get('/periksa', [RegPeriksaController::class, 'statusRegistrasi']);
		$route->get('/booking', [RegPeriksaController::class, 'caraBooking']);
		$route->get('/dokter', [BerandaController::class, 'jsonKunjunganDokter']);
		$route->get('/ralan', [RalanController::class, 'diagramRalanPoli']);
		$route->prefix('demografi')->group(function ($route) {
			$route->prefix('ralan')->group(function ($route) {
				$route->get('kecamatan/{year?}/{month?}', DemografiKecamatanPasienRajal::class);
				$route->get('kelurahan/{year?}/{month?}', DemografiKelurahanPasienRajal::class);
			});
			$route->prefix('ranap')->group(function ($route) {
				$route->get('kecamatan/{year?}/{month?}/{limit?}', DemografiKecamatanPasienRanap::class);
			});

		});
	});
});
