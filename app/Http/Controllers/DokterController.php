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
        $data = Dokter::where('kd_sps', '!=', 'S0007')
            ->orderBy('kd_sps', 'asc')
            ->where('nm_dokter', '!=', '-')
            ->where('status', '1')->get();
        return $data;
    }

    function getDokterById($kd_dokter)
    {

        return Dokter::where('kd_dokter', $kd_dokter)->first();
    }
}
