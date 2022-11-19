<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use Illuminate\Http\Request;

class DokterController extends Controller
{
    public function semuaDokter()
    {
        $dokter = Dokter::where('status', '1')
            ->whereHas('pegawai', function ($q) {
                $q->where('jbtn', '!=', '-')
                    ->whereIn('jnj_jabatan', ['RS10', 'DIRU']);
            })
            ->get();

        return response()->json($dokter);
    }
    public function getDokterSpesialis()
    {
        $data = Dokter::whereIn('kd_sps', ['S0001', 'S0003'])
            ->where('status', '1')->get();
        return $data;
    }
}
