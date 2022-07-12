<?php

namespace App\Http\Controllers;

use App\Models\KamarInap;
use Carbon\Carbon;
use App\Models\RegPeriksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class LaporanIGDController extends Controller
{

    private $tanggal;
    public function __construct()
    {
        // parent::__construct();
        //Do your magic here
        $this->tanggal = new Carbon('this month');
    }

    public function index()
    {
        $tanggal = new Carbon('this month');
        $sekarang = $tanggal->now();
        $awalBulan = $tanggal->startOfMonth();
        return view('dashboard.content.igd.laporan_igd', [
            'title' => 'Laporan IGD',
            'bigTitle' => 'Laporan IGD',
            'month' => $awalBulan->translatedFormat('d F Y') . ' s/d ' . $sekarang->translatedFormat('d F Y'),
            'dateNow' => $sekarang->translatedFormat('d F Y'),
            'dateStart' => $awalBulan->toDateString()

        ]);
    }

    public function json(Request $request)
    {
        $tanggal = new Carbon('this month');

        $tgl_pertama = $request->tgl_pertama;
        $tgl_kedua = $request->tgl_kedua;

        $igd = RegPeriksa::select(DB::raw('count(*) as jumlah'))
            ->where('kd_poli', 'IGDK')

            ->groupBy('status_lanjut');

        $hcu = KamarInap::where('kd_kamar', 'like', '%HCU%')
            ->where('stts_pulang', '!=', 'Pindah Kamar');


        if ($request->ajax()) {
            if ($tgl_pertama && $tgl_kedua) {
                $igd->whereBetween('tgl_registrasi', [$tgl_pertama, $tgl_kedua]);
                $hcu->whereBetween('tgl_masuk', [$tgl_pertama, $tgl_kedua]);
            } else {
                $igd->whereYear('tgl_registrasi', $tanggal->year)
                    ->whereMonth('tgl_registrasi', $tanggal->month);
                $hcu->whereYear('tgl_masuk', $tanggal->year)->whereMonth('tgl_masuk', $tanggal->month);
            }
        }


        $dataIgd = $igd->get()->pluck('jumlah');
        $data = [
            'ralan' => $dataIgd[0],
            'ranap' => $dataIgd[1],
            'hcu' => $hcu->count(),
        ];

        return json_encode($data);
    }

    // IGD ke HCU
    public function jsonPasienIgd(Request $request)
    {
        $tgl_pertama = $request->tgl_pertama;
        $tgl_kedua = $request->tgl_kedua;
        $spesialis = $request->spesialis;
        $status_lanjut = $request->status_lanjut;
        $pembiayaan = $request->pembiayaan;

        $tanggal = new Carbon('this month');

        $data = RegPeriksa::select('*')
            ->where('kd_poli', 'IGDK')
            ->groupBy('no_rawat')
            ->orderBy('tgl_registrasi', 'ASC');

        if ($request->ajax()) {
            $tgl_pertama && $tgl_kedua ?
                $data->whereBetween('tgl_registrasi', [$tgl_pertama, $tgl_kedua])
                ->with('bridgingSep')
                ->get()
                :
                $data->whereMonth('tgl_registrasi', '04')
                ->whereYear('tgl_registrasi', '2022')->with('bridgingSep')->get();
            if ($spesialis) {
                $data->whereHas('dokter.spesialis', function ($query) use ($spesialis) {
                    $query->where('kd_sps', 'like', "%$spesialis%");
                })->get();
            }
            if ($status_lanjut) {
                $data->where('status_lanjut', $status_lanjut)->get();
            }
            if ($pembiayaan) {
                $data->whereHas('penjab', function ($query) use ($pembiayaan) {
                    $query->where('png_jawab', 'like', '%' . $pembiayaan . '%');
                })->get();
            }
        }

        return DataTables::of($data)
            ->filter(function ($query) use ($request) {
                if ($request->has('search') && $request->get('search')['value']) {
                    return $query->whereHas('pasien', function ($query) use ($request) {
                        $query->where('nm_pasien', 'like', '%' . $request->get('search')['value'] . '%');
                    });
                }
            })
            ->editColumn('tgl_registrasi', function ($data) use ($tanggal) {
                return $tanggal->parse($data->tgl_registrasi)->translatedFormat('d F Y') . " ($data->jam_reg)";
            })
            ->editColumn('nm_pasien', function ($data) {
                return $data->pasien->nm_pasien . ' ( ' . $data->pasien->no_rkm_medis . ')';
            })
            ->editColumn('nm_dokter', function ($data) {
                return $data->dokter->nm_dokter;
            })
            ->editColumn('alamat', function ($data) {
                return $data->pasien->alamat . ", "
                    . $data->pasien->kelurahan->nm_kel . ", "
                    . $data->pasien->kecamatan->nm_kec . ", "
                    . $data->pasien->kabupaten->nm_kab;
            })
            ->editColumn('nm_sps', function ($data) {
                return $data->dokter->spesialis->nm_sps;
            })
            ->editColumn('pembiayaan', function ($data) {
                return $data->penjab->png_jawab;
            })
            ->editColumn('bridging_sep', function ($data) use ($tanggal) {
                $sep = '';
                if ($data->bridgingSep == null) {
                    $sep = "Tidak Ada SEP";
                    return "<button class='btn btn-danger'>$sep</button>";
                } else {
                    $sep = $tanggal->parse($data->bridgingSep->tglsep)->translatedFormat('d F Y');
                    return "<button class='btn btn-success'>$sep</button>";
                }
            })
            ->rawColumns(['bridging_sep'])
            ->make(true);
    }
    // hitung kunjungan igd bulan ini

}
