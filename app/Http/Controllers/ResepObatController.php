<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ResepObat;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

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
    public function waktu()
    {
        return view('dashboard.content.farmasi.waktu', [
            'title' => 'Waktu Tunggu Resep',
            'bigTitle' => 'Laporan Waktu Tunggu Resep',
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
    function ambilTabel(Request $request)
    {

        $resep = $this->resep->where([
            'status' => 'ralan'
        ])->with(['regPeriksa.pasien', 'dokter', 'resepDokter', 'resepDokterRacikan', 'regPeriksa.poli']);
        if ($request->tgl_pertama || $request->tgl_kedua) {
            $resep->whereBetween('tgl_peresepan', [$request->tgl_pertama, $request->tgl_kedua])->get();
        } else {
            $resep->where('tgl_peresepan', date('Y-m-d'))->get();
        }

        return DataTables::of($resep)->make(true);
    }
}
