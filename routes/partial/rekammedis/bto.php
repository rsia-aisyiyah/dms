<?php

use App\Http\Controllers\BorController;
use App\Http\Controllers\RsiaLogJumlahKamarController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('bed-turn-over/bor', function () {
        return view('rekammedis.bto');
    });
    Route::get('bed-turn-over/bor/{specialist}/{year?}', [BorController::class, 'index']);
    Route::post('log/kamar', [RsiaLogJumlahKamarController::class, 'index']);

});
