<?php

namespace App\Http\Controllers\Collection;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use Illuminate\Http\Request;

class JadwalCollection extends Controller
{
    protected $jadwalModel;
    public function __construct()
    {
        $this->jadwalModel = new Jadwal();
    }

    public function getAll()
    {
        $jadwal = $this->jadwalModel
            ->withPoliDokter()
            ->getAll();
        return collect($jadwal);
    }
}
