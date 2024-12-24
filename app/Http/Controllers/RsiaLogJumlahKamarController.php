<?php

namespace App\Http\Controllers;

use App\Models\RsiaLogJumlahKamar;
use App\Services\RsiaMappingKamarInapService;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class RsiaLogJumlahKamarController extends Controller
{
    protected $track;

    public function index(RsiaMappingKamarInapService $mappingKamar, Request $request)
    {
        $data = [
            'tahun' => $request->tahun,
            'bulan' => $request->bulan,
            'kategori' => $request->kategori,
            'jumlah' => $mappingKamar->getKamarInap($request->kategori),
        ];

        try {
            $create = RsiaLogJumlahKamar::create($data);
        } catch (QueryException $e) {
            return response()->json($e->errorInfo);
        }
        return response()->json([
            'status' => true,
        ], 200);

    }
}
