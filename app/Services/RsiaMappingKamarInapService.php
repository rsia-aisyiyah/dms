<?php

namespace App\Services;

/**
 * Class RsiaMappingKamarInapService.
 */
use App\Models\RsiaMappingKamarInap;

class RsiaMappingKamarInapService
{

    public static function getKamarInap($specialist)
    {
        $kamar = new RsiaMappingKamarInap();
        return $kamar->where('kategori', $specialist)->get()->count();

    }
}
