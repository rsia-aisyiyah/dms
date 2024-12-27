<?php

namespace App\Services;

use App\Models\RsiaLogJumlahKamar;

/**
 * Class RsiaLogJumlahKamarService.
 */
class RsiaLogJumlahKamarService
{

    public static function getJumlahKamar($specialist, $month, $year){
        $kamarLog = RsiaLogJumlahKamar::where('tahun', $year)
            ->where('bulan', $month);
        if ($specialist === 'all') {
            return $kamarLog->where(function ($query) {
                $query->where('kategori', 'like', '%' . 'Kandungan' . '%')
                    ->orWhere('kategori', 'like', '%' . 'Anak' . '%');
            })->get()->sum('jumlah');
        } else {
            $kamar = $kamarLog->where('kategori', 'like', '%' . $specialist . '%')->first();
            return $kamar ? $kamar->jumlah : 0;
        }
    }
}
