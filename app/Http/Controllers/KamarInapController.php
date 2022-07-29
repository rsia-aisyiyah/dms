<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Kamar;
use App\Models\KamarInap;
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
        $i = 0;

        foreach ($kelas as $k) {
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

            $i++;
        }
        $hcu[] = [
            'kelas' => 'HCU',
            'jumlahKelas' => '2',
            'data' => $kamarHcu,
        ];
        $dataMerge = array_merge($data, $hcu);
        // return $data;
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
    public function rekapHcu()
    {
        # code...
    }
}
