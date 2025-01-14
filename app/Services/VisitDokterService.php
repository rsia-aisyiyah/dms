<?php

namespace App\Services;

use App\Models\PemeriksaanRanap;
use Illuminate\Http\Request;

class VisitDokterService
{
    function getVisitData(Request $request)
    {

        if ($request->month && $request->year) {
            $month = $request->month;
            $year = $request->year;

        } else {
            $month = date('m');
            $year = date('Y');
        }

        $pemeriksaan = PemeriksaanRanap::whereHas('petugas', function ($query) {
            $query->whereHas('dokter', function ($q) {
                $q->whereIn('kd_sps', ['S0001', 'S0003']);
            });
        })
            ->with(['petugas', 'kamarInap.kamar.bangsal', 'regPeriksa' => function ($q) {
                $q->with(['pasien', 'ranapGabung.kamarInap.kamar.bangsal']);
            }])
            ->whereMonth('tgl_perawatan', $month)
            ->whereYear('tgl_perawatan', $year)->get();

        return $pemeriksaan;

    }
}
