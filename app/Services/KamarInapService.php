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
            ->whereYear('tgl_keluar', $year)
            ->where('stts_pulang', '!=', 'Pindah Kamar');
            if($specialist !=='all'){
                if ($specialist !== 'icu') {
                    $kamarInap->whereHas('regPeriksa', function ($q) use ($specialist) {
                        $q->whereHas('dokter', function ($q) use ($specialist) {
                            $q->whereHas('spesialis', function ($q) use ($specialist) {
                                $q->where('nm_sps', 'like', '%' . $specialist . '%');
                            });
                        });
                    });
                 }
                    $kamarInap->where('kd_kamar', 'like', '%' . $specialist . '%');
            }else{
                $kamarInap->whereHas('regPeriksa', function ($q) use ($specialist) {
                    $q->whereHas('dokter', function ($q) use ($specialist) {
                        $q->whereHas('spesialis', function ($q) use ($specialist) {
                            $q->where(function ($query) {
                                $query->where('nm_sps', 'like', '%anak%')->orWhere('nm_sps', 'like', '%kandungan%');
                            });
                        });
                    });
                })->where(function($q){
                    $q->where(function ($query) {
                        $query->where('kd_kamar', 'like', '%anak%')
                        ->orWhere('kd_kamar', 'like', '%icu%')
                        ->orWhere('kd_kamar', 'like', '%kandungan%')
                        ->orWhere('kd_kamar', 'like', '%byc%')
                        ->orWhere('kd_kamar', 'like', '%iso%');
                    });
                });
            }
        return $kamarInap->groupBy('no_rawat')->get()->count();;
    }
    
     public static function getLamaInap($specialist, $month, $year)
    {
        $kamarInap = KamarInap::whereMonth('tgl_keluar', $month)
            ->whereYear('tgl_keluar', $year)
            ->select('lama');

        if ($specialist === 'all') {
            $kamarInap->where(function ($query) {
                $query->where('kd_kamar', 'like', '%' . 'Kandungan' . '%')
                    ->orWhere('kd_kamar', 'like', '%' . 'Anak' . '%')
                    ->orWhere('kd_kamar', 'like', '%' . 'ICU' . '%')
                    ->orWhere('kd_kamar', 'like', '%' . 'ISO' . '%')
                    ->orWhere('kd_kamar', 'like', '%' . 'BYC' . '%');
            });
        } else {
            $kamarInap->where('kd_kamar', 'like', '%' . $specialist . '%');
        }

        $kamarInap = $kamarInap->get()->sum('lama');

        return $kamarInap;
    }


}
