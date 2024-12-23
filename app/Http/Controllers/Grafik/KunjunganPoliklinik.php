<?php

namespace App\Http\Controllers\Grafik;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\PoliklinikController;
use App\Http\Controllers\RegPeriksaController;

class KunjunganPoliklinik extends Controller
{
    protected $dokter;
    protected $registrasi;
    protected $poliklinik;

    public function __construct(DokterController $dokter, RegPeriksaController $registrasi, PoliklinikController $poliklinik)
    {
        $this->dokter = $dokter;
        $this->registrasi = $registrasi;
        $this->poliklinik = $poliklinik;
    }

    public function index()
    {
        $dokterSpesialis = $this->dokter->getDokterSpesialis();

        return $dokterSpesialis->map(function ($item) {
            return $item;
        });
    }
}
