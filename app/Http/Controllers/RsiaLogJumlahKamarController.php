<?php

namespace App\Http\Controllers;

use App\Services\KamarInapService;
use Illuminate\Http\Request;

class RsiaLogJumlahKamarController extends Controller
{
    protected $track;

    public function index(Request $request)
    {
        $data = [
            'tahun' => $request->tahun,
            'bulan' => $request->bulan,
            'kategori' => $request->kategori,
            'jumlah' => $request->jumlah,
        ];

        $jmlKamar = new KamarInapService();
        $kamarInap = $jmlKamar->getKamarInap($request->kategori, $request->bulan, $request->tahun);
        // count();
        // $kamarInap->count();

        return $kamarInap->count();
        // try {
        //     $create = RsiaLogJumlahKamar::create($data);

        // } catch (QueryException $e) {
        //     return response()->json($e->errorInfo);
        // }
        // return response()->json([
        //     'status' => true,
        // ], 200);

    }
}
