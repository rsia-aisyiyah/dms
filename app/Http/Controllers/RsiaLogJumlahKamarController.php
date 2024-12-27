<?php

namespace App\Http\Controllers;

use App\Models\RsiaLogJumlahKamar;
use App\Services\RsiaMappingKamarInapService;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class RsiaLogJumlahKamarController extends Controller
{
    protected $track;

    public function create(Request $request)
    {
        $validated = $request->validate([
            'kategori' => 'required',
            'bulan' => 'required',
            'tahun' => 'required',
        ]);
       RsiaMappingKamarInapService::create($validated);
       return response()->json(['message' => 'Success'], 200);

    }
}
