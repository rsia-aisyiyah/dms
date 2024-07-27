<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FarmasiController;
use App\Http\Controllers\ResumePasienRanap;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// ========== DATA DASHBOARD FARMASI
Route::get('farmasi/gudang/pesanan', [FarmasiController::class, 'pesananGudang']);
Route::post('farmasi/gudang/metrics', [FarmasiController::class, 'metricsGudang']);
Route::post('farmasi/gudang/metrics/top/obat', [FarmasiController::class, 'metricsGudangTopObat']);
Route::post('farmasi/gudang/metrics/bottom/obat', [FarmasiController::class, 'metricsGudangBottomObat']);
Route::post('farmasi/gudang/metrics/detail', [FarmasiController::class, 'metricsGudangDetail']);


// MONITORING RESUME
Route::get('monitor/resume/ranap', [ResumePasienRanap::class, 'monitorResumeRanap']);

Route::get('monitor/rme/ugd', [ResumePasienRanap::class, 'monitorRmeUgd']);
Route::get('monitor/rme/ranap', [ResumePasienRanap::class, 'monitorRmeRanap']);

Route::post('monitor/rme/ugd', [ResumePasienRanap::class, 'monitorRmeUgd']);
Route::post('monitor/rme/ranap', [ResumePasienRanap::class, 'monitorRmeRanap']);

Route::get('monitor/pengisian-erm/spesialis/ranap', [ResumePasienRanap::class, 'ermSpesialistRanap']);
Route::get('monitor/pengisian-erm/spesialis/ralan', [ResumePasienRanap::class, 'ermSpesialistRalan']);
Route::get('monitor/pengisian-erm/spesialis/ralan/debug', [ResumePasienRanap::class, 'ermSpesialistRalanDebug']);