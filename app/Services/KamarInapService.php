<?php

namespace App\Services;

use App\Models\KamarInap;

/**
 * Class KamarInapService.
 */
class KamarInapService
{
    public static function getKamarInap($specialist, $month, $year)
    {
        $kamarInap = KamarInap::whereMonth('tgl_keluar', $month)
            ->whereYear('tgl_keluar', $year)
            ->where('kd_kamar', 'like', '%' . $specialist . '%');

        return $kamarInap;

    }

    public static function getPasienPulang($specialist, $month, $year):int{
        $kamarInap = KamarInap::whereMonth('tgl_keluar', $month)
            ->whereHas('regPeriksa', function ($q) use ($specialist) {
                $q->whereHas('dokter', function ($q) use ($specialist) {
                    $q->whereHas('spesialis', function ($q) use ($specialist) {
                        $q->where('nm_sps', 'like', '%' . $specialist . '%');
                    });
                });
            })
            ->whereYear('tgl_keluar', $year)
            ->where('kd_kamar', 'like', '%' . $specialist . '%')
            ->where('lama', '>', 0)
            ->groupBy('no_rawat')->get()->count();
        return $kamarInap;
    }
    
     public static function getLamaInap($specialist, $month, $year)
    {
        $kamarInap = KamarInap::whereMonth('tgl_keluar', $month)
            ->whereYear('tgl_keluar', $year)
            ->select('lama');

        if ($specialist === 'all') {
            $kamarInap->where(function ($query) {
                $query->where('kd_kamar', 'like', '%' . 'Kandungan' . '%')
                    ->orWhere('kd_kamar', 'like', '%' . 'Anak' . '%');
            });
        } else {
            $kamarInap->where('kd_kamar', 'like', '%' . $specialist . '%');
        }

        $kamarInap = $kamarInap->get()->sum('lama');

        return $kamarInap;
    }


}
