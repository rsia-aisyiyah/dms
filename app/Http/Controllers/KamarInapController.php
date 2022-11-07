<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Kamar;
use App\Models\KamarInap;
use App\Models\RegPeriksa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class KamarInapController extends Controller
{
    private $kelas;
    private $jumlah;
    private $tanggal;
    public function __construct()
    {
        $this->jumlahKamar();
        $this->tanggal = new Carbon();
    }
    public function rekapKunjungan(Request $request)
    {
        $kelas = $this->kelas;
        $jumlahKelas = $this->jumlah;
        $tahun = $request->tahun ? $request->tahun : date('Y');
        foreach ($kelas as $i => $k) {
            $kamarInap = KamarInap::where('stts_pulang', '!=', 'Pindah Kamar')->whereYear('tgl_keluar', $tahun)->whereHas('kamar', function ($q) use ($k) {
                $q->where('kelas', $k);
            })->get()->count();
            $kamarHcu = KamarInap::where('stts_pulang', '!=', 'Pindah Kamar')->whereYear('tgl_keluar', $tahun)->whereHas('kamar', function ($q) {
                $q->whereIn('kd_bangsal', ['HCU1', 'HCU2']);
            })->get()->count();

            $data[] = [
                'kelas' => $k,
                'jumlahKelas' => $jumlahKelas[$i],
                'data' => $kamarInap,
            ];
        }

        $hcu[] = [
            'kelas' => 'HCU',
            'jumlahKelas' => '2',
            'data' => $kamarHcu,
        ];
        $dataMerge = array_merge($data, $hcu);
        return DataTables::of($dataMerge)->make(true);
    }
    public function jumlahKamar()
    {
        $data = Kamar::select(DB::raw('count(*) as jumlah'), 'kelas')
            ->where('trf_kamar', '!=', 0)
            ->where('statusdata', '1')
            ->whereNotIn('kd_kamar', ['HCU1', 'HCU2'])
            ->where('kelas', '!=', 'Kelas Utama')
            ->groupBy('kelas')->get();

        $this->kelas = $data->pluck('kelas')->toArray();
        $this->jumlah = $data->pluck('jumlah')->toArray();
    }
    public function rekapKamar(Request $request)
    {
        $tgl_pertama = $request->tgl_pertama;
        $tgl_kedua = $request->tgl_kedua;

        $kamarHcu = RegPeriksa::where('status_lanjut', 'Ranap');
        if ($tgl_pertama && $tgl_kedua) {
            $kamarHcu->whereHas('kamarInap', function ($q) use ($tgl_pertama, $tgl_kedua) {
                $q->whereBetween('tgl_keluar', [$tgl_pertama, $tgl_kedua]);
            })->with('kamarInap');
        } else {
            $kamarHcu->whereHas('kamarInap', function ($q) {
                $q->whereYear('tgl_keluar', date('Y'))
                    ->whereMonth('tgl_keluar', date('m'));
            })->with('kamarInap', function ($q) {
                $q->with('kamar');
            });
        }

        return $kamarHcu;

    }

    public function kamarHCU(Request $request)
    {
        return $this->rekapKamar($request)
            ->whereHas('kamarInap', function ($q) {
                $q->where('stts_pulang', '!=', 'Pindah Kamar');
                $q->whereHas('kamar', function ($q) {
                    $q->where('kd_kamar', 'like', '%HCU%');
                });
            })->get();

    }
    public function kamarVK(Request $request)
    {
        return $this->rekapKamar($request)
            ->whereHas('kamarInap', function ($q) {
                $q->where('stts_pulang', '!=', 'Pindah Kamar')
                    ->whereHas('kamar', function ($q) {
                        $q->where('kd_kamar', 'like', '%VK%');
                    });
            })->get();

    }

}
