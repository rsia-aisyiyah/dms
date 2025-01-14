<?php

use App\Http\Controllers\BorController;
use App\Http\Controllers\LosController;
use App\Http\Controllers\RsiaLogJumlahKamarController;
use App\Http\Controllers\ToiController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('indikator-ranap', function () {
        return view('dashboard.content.rekammedis.indikator_ranap', [
            'bigTitle' => 'Indikator Rawat Inap',
            'title' => 'Indikator Rawat Inap',
        ]);
    });
    Route::get('indikator-ranap/bor/{specialist}/{year?}', [BorController::class, 'index']);
    Route::get('indikator-ranap/los/{specialist}/{year?}', [LosController::class, 'index']);
    Route::get('indikator-ranap/toi/{specialist}/{year?}', [ToiController::class, 'index']);
    Route::post('log/kamar/create', [RsiaLogJumlahKamarController::class, 'create']);

});
