<?php

use App\Http\Controllers\Actions\SkriningTbCountByYearAction;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DiagnosaPasienController;

Route::middleware('auth')->group(function () {
	Route::get('/rekammedis/tuberkulosis', [DiagnosaPasienController::class, 'pasienTb']);
	Route::get('/rekammedis/pasientb/json', [DiagnosaPasienController::class, 'jsonPasienTb']);

	Route::prefix('grafik')->group(function ($route) {
		$route->prefix('tb')->group(function ($route) {
			$route->get('demografi/{year?}/{month?}', \App\Http\Actions\DemografiPasienTb::class);
			$route->get('skrining/poli/{year?}/{month?}', \App\Http\Actions\SkriningTbByPoliklinik::class);
			$route->get('skrining/{year?}', SkriningTbCountByYearAction::class);
		});
	});
});
