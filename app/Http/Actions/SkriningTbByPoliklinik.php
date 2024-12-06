<?php

namespace App\Http\Actions;

use App\Models\RegPeriksa;
use App\Models\RsiaSkriningTb;

class SkriningTbByPoliklinik
{
    public function __invoke(RegPeriksa $registrasi, RsiaSkriningTb $skrining, $year = null, $month = null)
    {
        $skrining = collect($skrining->getCountByPoliklinik()
                ->year($year)->month($month)->get());
        // Group counts by `kd_poli`
        $grouped = $skrining
            ->map(function ($item) {
                return $item->poliklinik;
            })
            ->countBy('kd_poli');

// Transform to separate IGDK, BBL, and group others under 'ANOTHER'
        return $grouped->reduce(function ($result, $count, $kd_poli) {
            if (in_array($kd_poli, ['IGDK', 'BBL'])) {
                $result[$kd_poli] = $count;
            } else {
                $result['POLI'] = ($result['POLI'] ?? 0) + $count;
            }
            return $result;
        }, []);

    }
}
