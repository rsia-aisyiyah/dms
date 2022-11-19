<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Dokter;
use App\Models\RawatInapDr;
use App\Models\RawatJalanDr;
use Illuminate\Http\Request;
use App\Models\RawatInapDrpr;
use App\Models\RawatJalanDrpr;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class TindakanController extends Controller
{
    private $bulan = '';
    private $tahun = '';
    private $date;
    public function __construct()
    {
        $this->date = new Carbon();
        $date = $this->date;
        $this->bulan = $date->month;
        $this->tahun = $date->year;
    }

    public function index()
    {
        return view(
            'dashboard.content.tindakan.list_tindakan',
            [
                'title' => 'Tindakan Dokter',
                'bigTitle' => 'Rekap Tindakan Dokter',
                'month' => $this->date->now()->monthName,
                'tglAwal' => $this->date->startOfMonth()->toDateString(),
                'tglSekarang' => $this->date->now()->toDateString(),
            ]
        );
    }

    public function rekapTindakan(Request $request)
    {
        if ($request->ajax()) {
            if ($request->tahun && $request->dokter) {
                $tahun = $request->tahun;
                $dokter = $request->dokter;
            } else {
                $tahun = $this->tahun;
                $dokter = "";
            }

            $rawatInapDrPr = 0;
            $rawatJlDr = 0;
            $rawatJlDrpr = 0;
            for ($i = 1; $i <= 12; $i++) {
                $rawatInapDr = RawatInapDr::whereYear('tgl_perawatan', $tahun)->whereMonth('tgl_perawatan', $i)->where('kd_dokter', $dokter)->count();
                $rawatInapDrPr = RawatInapDrPr::whereYear('tgl_perawatan', $tahun)->whereMonth('tgl_perawatan', $i)->where('kd_dokter', $dokter)->count();
                $rawatJlDr = RawatJalanDr::whereYear('tgl_perawatan', $tahun)->whereMonth('tgl_perawatan', $i)->where('kd_dokter', $dokter)->count();
                $rawatJlDrpr = RawatJalanDrpr::whereYear('tgl_perawatan', $tahun)->whereMonth('tgl_perawatan', $i)->where('kd_dokter', $dokter)->count();
                $indexBulan = $this->date->month($i)->translatedFormat('F');
                $r[] = [
                    'bulan' => $indexBulan . " " . $tahun,
                    'jumlah' => $rawatInapDr + $rawatInapDrPr + $rawatJlDr + $rawatJlDrpr
                ];
            }

            // $data[] = $r;
            return DataTables::of($r)->make(true);
        }
    }
}
