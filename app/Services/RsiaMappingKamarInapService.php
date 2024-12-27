<?php

namespace App\Services;

/**
 * Class RsiaMappingKamarInapService.
 */

use App\Models\RsiaLogJumlahKamar;
use App\Models\RsiaMappingKamarInap;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class RsiaMappingKamarInapService
{

    public static function getKamarInap($specialist)
    {
        $kamar = new RsiaMappingKamarInap();
        return $kamar->where('kategori', $specialist)->get()->count();
    }

    public static function create(array $data)
    {
        
        if($data['kategori'] === 'all'){
            return true;
        }
        $data['jumlah'] = self::getKamarInap($data['kategori']) ?: 0;
        try {
            $create = RsiaLogJumlahKamar::create($data);
        } catch (QueryException $e) {
            return response()->json($e->errorInfo, 500);
        }
    }
}
