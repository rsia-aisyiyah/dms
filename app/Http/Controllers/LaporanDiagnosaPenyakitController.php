<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Penyakit;
use Illuminate\Http\Request;
use App\Models\DiagnosaPasien;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class LaporanDiagnosaPenyakitController extends Controller
{
    public function index()
    {
        $date = new Carbon('this month');
        return view(
            'dashboard.content.rekammedis.list_penyakit',
            [
                'bigTitle' => 'Laporan Pemeriksaan Diagnosa Penyakit',
                'title' => 'Laporan Diagnosa Penyakit',
                'month' => $date->monthName,
                'dateStart' => $date->startOfMonth()->toDateString(),
                'dateNow' => $date->now()->toDateString()
            ]
        );
    }
    public function json(Request $request)
    {
        $data = '';
        $tanggal = new Carbon('this month');

        $tgl_pertama = $request->tgl_pertama;
        $tgl_kedua = $request->tgl_kedua;
        $status = $request->status;
        $diagnosa = $request->diagnosa;
        $pembiayaan = $request->pembiayaan;

        $data = DiagnosaPasien::where('prioritas', 1);

        if ($request->ajax()) {
            if ($tgl_pertama && $tgl_kedua) {
                $data->whereHas('regPeriksa', function ($query) use ($tgl_pertama, $tgl_kedua) {
                    $query->whereBetween('tgl_registrasi', [$tgl_pertama, $tgl_kedua]);
                });
            } else {
                $data->whereHas('regPeriksa', function ($query) use ($tanggal) {
                    $query->whereBetween('tgl_registrasi', [
                        $tanggal->startOfMonth()->toDateString(),
                        $tanggal->lastOfMonth()->toDateString()
                    ]);
                });
            }
            if ($status) {
                $data->where('status', 'like', '%' . $request->status . '%');
            }
            if ($diagnosa) {
                $data->whereIn('kd_penyakit', $request->diagnosa);
            }
            if ($pembiayaan) {
                $data->whereHas('regPeriksa.penjab', function ($query) use ($pembiayaan) {
                    $query->where('png_jawab', 'like', "%$pembiayaan%");
                });
            }
        }

        return DataTables::of($data)
            ->filter(function ($query) use ($request) {
                if ($request->has('search') && $request->get('search')['value']) {
                    return $query->whereHas('regPeriksa.pasien', function ($query) use ($request) {
                        $query->where('nm_pasien', 'like', '%' . $request->get('search')['value'] . '%');
                    });
                }
            })
            ->editColumn('tanggal', function ($data) use ($tanggal) {
                return $tanggal->parse($data->regPeriksa->tgl_registrasi)->translatedFormat('d F Y');
            })
            ->editColumn('no_rkm_medis', function ($data) {
                return $data->regPeriksa->no_rkm_medis;
            })
            ->editColumn('no_rawat', function ($data) use ($tanggal) {
                return $data->regPeriksa->no_rawat;
            })
            ->editColumn('nm_pasien', function ($data) {
                return $data->regPeriksa->pasien->nm_pasien;
            })
            ->editColumn('jk', function ($data) {
                $jk = $data->regPeriksa->pasien->jk;
                return $jk == "L" ? "Laki-Laki" : "Perempuan";
            })
            ->editColumn('tgl_lahir', function ($data) {
                return $data->regPeriksa->pasien->tgl_lahir;
            })
            ->editColumn('no_ktp', function ($data) {
                return $data->regPeriksa->pasien->no_ktp;
            })
            ->editColumn('umur', function ($data) {
                return $data->regPeriksa->umurdaftar . ' ' . $data->regPeriksa->sttsumur;
            })
            ->editColumn('alamat', function ($data) {
                return $data->regPeriksa->pasien->alamat . ", "
                    . $data->regPeriksa->pasien->kelurahan->nm_kel . ", "
                    . $data->regPeriksa->pasien->kecamatan->nm_kec . ", "
                    . $data->regPeriksa->pasien->kabupaten->nm_kab;
            })
            ->editColumn('nm_penyakit', function ($data) {
                return $data->penyakit->nm_penyakit;
            })
            ->editColumn('pembiayaan', function ($data) use ($pembiayaan) {
                return $data->regPeriksa->penjab->png_jawab;
            })
            ->editColumn('status_daftar', function ($data) {
                return $data->regPeriksa->stts_daftar;
            })
            ->editColumn('status', function ($data) use ($status) {
                return $data->status;
            })
            ->make(true);
    }

    public function cariDiagnosa(Request $request)
    {
        if ($request->get('query')) {
            $query = $request->get('query');
            $data = Penyakit::where('nm_penyakit', 'LIKE', '%' . $query . '%')
                ->orWhere('kd_penyakit', 'LIKE', '%' . $query . '%')
                ->limit(10)
                ->get();
            $output = '<ul class="dropdown-menu" style="display:block; position:fix; border-radius:0">';
            foreach ($data as $row) {
                $output .= '
                <li ><a class="dropdown-item" href="#" style="width:auto;overflow:hidden">' . $row->kd_penyakit . ' - ' . $row->nm_penyakit . '</a></li>
                ';
            }

            $output .= '</ul>';
            return $output;
        }
    }

    public function get(Request $request)
    {
        // if ($request->get('query')) {
        $query = $request->get('query');
        $data = Penyakit::where('nm_penyakit', 'LIKE', '%' . $query . '%')
            ->orWhere('kd_penyakit', 'LIKE', '%' . $query . '%')
            ->limit(10)
            ->get();
        return response()->json($data);
        // }
    }
}
