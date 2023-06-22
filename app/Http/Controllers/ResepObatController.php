<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ResepObat;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ResepObatController extends Controller
{

    protected $resep;
    protected $tanggal;

    public function __construct()
    {
        $this->resep = new ResepObat;
        $this->tanggal = new Carbon();
    }

    public function index()
    {
        return view('dashboard.content.farmasi.list_resep', [
            'title' => 'Laporan Resep',
            'bigTitle' => 'Laporan Resep',
            'month' => $this->tanggal->startOfMonth()->translatedFormat('d F Y') . ' s/d ' . $this->tanggal->now()->translatedFormat('d F Y'),
            'dateNow' => $this->tanggal->now()->translatedFormat('d F Y'),
            'dateStart' => $this->tanggal->startOfMonth()->toDateString()
        ]);
    }
    public function ambil(Request $request)
    {

        $resep = $this->resep->whereBetween('tgl_peresepan', [$request->tgl_pertama, $request->tgl_kedua])->with('dokter')->get();
        return response()->json($resep);
    }
}
