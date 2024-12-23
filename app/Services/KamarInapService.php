<?php

namespace App\Services;

use App\Models\KamarInap;

/**
 * Class KamarInapService.
 */
class KamarInapService
{
    public function getKamarInap($specialist, $month, $year)
    {
        $kamarInap = KamarInap::whereMonth('tgl_keluar', $month)
            ->whereYear('tgl_keluar', $year)
            ->where('kd_kamar', 'like', '%' . $specialist . '%');

        return $kamarInap;

    }
}
