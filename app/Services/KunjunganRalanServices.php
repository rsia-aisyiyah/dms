<?php

namespace App\Services;

use App\Models\RegPeriksa;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class KunjunganRalanServices
{
    protected $regPeriksa;

    public function __construct(RegPeriksa $regPeriksa)
    {
        $this->regPeriksa = $regPeriksa;
    }

    public function getRegPeriksaRalan( $year = null)
    {
        $year = $year ?? date('Y');
        return $this->regPeriksa->whereHas('dokter', function ($q) {
                $q->whereIn('kd_sps', ['S0001', 'S0003', 'S0005']);
            })
            ->with('dokter')
            ->whereYear('tgl_registrasi', $year)
            ->where('status_lanjut', 'Ralan')
            ->where('stts', 'Sudah')
            ->get();
    }

    public function gropingByMonthAndDokter($year = null){
        $year = $year ?? date('Y');
        $kunjungan =  self::getRegPeriksaRalan($year)
            ->groupBy(function ($item) {
                return Carbon::parse($item->tgl_registrasi)->format('F');
        })->mapWithKeys(function ($item, $key) {
            return [
                $key => $item->groupBy('dokter.nm_dokter')->map(function ($item) {
                    return $item->count();
                })
            ];
        });

        return response()->json($kunjungan);
    }


}
