<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DiagnosaPasien;
use App\Models\RegPeriksa;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class DiagnosaPasienController extends Controller
{
    public function index()
    {
        $date = new Carbon('this month');
        return view(
            'dashboard.content.rekammedis.list_diagnosa',
            [
                'title' => 'Data Rekam Medis',
                'bigTitle' => 'Rekam Medis',
                'month' => $date->startOfMonth()->translatedFormat('d F Y') . ' s/d ' . $date->now()->translatedFormat('d F Y'),
                'dateStart' => $date->startOfMonth()->toDateString(),
                'dateNow' => $date->now()->toDateString()
            ]
        );
    }

    public function json(Request $request)
    {

        $data = '';
        $start = new Carbon('this month');
        if ($request->ajax()) {
            if ($request->tgl_pertama || $request->tgl_kedua) {
                $data = DiagnosaPasien::select('*', DB::raw('count(kd_penyakit) as jumlah'))
                    ->where('prioritas', 1)
                    ->where('status', 'like', '%' . $request->status . '%')
                    ->whereHas('regPeriksa', function ($query) use ($request) {
                        $query->whereBetween('tgl_registrasi', [$request->tgl_pertama, $request->tgl_kedua]);
                    })
                    ->whereHas('regPeriksa.dokter.spesialis', function ($query) use ($request) {
                        $query->where('nm_sps', 'like', '%' . $request->spesialis . '%');
                    })
                    ->whereHas('regPeriksa.penjab', function ($query) use ($request) {
                        $query->where('png_jawab', 'like', '%' . $request->pembiayaan . '%');
                    })
                    ->groupBy('kd_penyakit')
                    ->orderBy('jumlah', 'desc')
                    ->limit(10)
                    ->get();
            } else {
                $data = DiagnosaPasien::select('*', DB::raw('count(kd_penyakit) as jumlah'))
                    ->where('prioritas', 1)
                    ->whereHas('regPeriksa', function ($query) use ($start) {
                        $query->whereBetween('tgl_registrasi', [$start->startOfMonth()->toDateString(), $start->now()->toDateString()]);
                    })
                    ->groupBy('kd_penyakit')
                    ->orderBy('jumlah', 'desc')
                    ->limit(10)
                    ->get();
            }
        }
        return DataTables::of($data)
            ->editColumn('nm_penyakit', function ($data) {
                return $data->penyakit->nm_penyakit;
            })
            ->editColumn('pembiayaan', function ($data) use ($request) {
                if ($request->pembiayaan) {
                    return $data->regPeriksa->penjab->png_jawab;
                } else {
                    return 'Semua Pembiayaan';
                }
            })
            ->editColumn('status', function ($data) use ($request) {
                if ($request->status) {
                    return ($data->status == 'Ralan' ? 'Rawat Jalan' : 'Rawat Inap');
                } else {
                    return 'Ralan & Ranap';
                }
            })
            ->make(true);
    }
    public function pasienTb()
    {
        $tanggal = new Carbon('this month');
        return view(
            'dashboard.content.rekammedis.list_pasien_tb',
            [
                'title' => 'Data Rekam Medis',
                'bigTitle' => 'Rekam Medis',
                'month' => $tanggal->startOfMonth()->translatedFormat('d F Y') . ' s/d ' . $tanggal->now()->translatedFormat('d F Y'),
                'dateStart' => $tanggal->startOfMonth()->toDateString(),
                'dateNow' => $tanggal->now()->toDateString()
            ]
        );
    }
    public function jsonPasienTb(Request $request)
    {
        $data = RegPeriksa::select('*')
            ->whereHas('diagnosaPasien', function ($query) {
                $query->where('prioritas', 1)
                    ->where('kd_penyakit', 'like', '%A15%');
            })
            ->groupBy('no_rkm_medis')
            ->orderBy('tgl_registrasi', 'ASC');

        $tanggal = new Carbon('this month');
        if ($request->ajax()) {
            if ($request->tgl_pertama || $request->tgl_kedua) {
                $data->whereBetween('tgl_registrasi', [$request->tgl_pertama, $request->tgl_kedua])->get();
            } else {
                $data->whereBetween('tgl_registrasi', [$tanggal->startOfMonth()->toDateString(), $tanggal->now()->toDateString()])->get();
            }
        }

        return DataTables::of($data)
            ->editColumn('tgl_registrasi', function ($data) use ($tanggal) {
                return $tanggal->parse($data->tgl_registrasi)->translatedFormat('d F Y');
            })
            ->editColumn('nm_pasien', function ($data) {
                return $data->pasien->nm_pasien;
            })
            ->editColumn('umurdaftar', function ($data) {
                return $data->umurdaftar . ' ' . $data->sttsumur;
            })
            ->editColumn('kd_penyakit', function ($data) {
                return $data->diagnosaPasien->kd_penyakit;
            })
            ->editColumn('nm_penyakit', function ($data) {
                return $data->diagnosaPasien->penyakit->nm_penyakit;
            })
            ->editColumn('alamat', function ($data) {
                return $data->pasien->alamat . ", "
                    . $data->pasien->kelurahan->nm_kel . ", "
                    . $data->pasien->kecamatan->nm_kec . ", "
                    . $data->pasien->kabupaten->nm_kab;
            })
            ->editColumn('nm_poli', function ($data) {
                return $data->poli->nm_poli;
            })
            ->editColumn('jk', function ($data) {
                return ($data->pasien->jk == 'L' ? 'Laki-Laki' : 'Perempuan');
            })
            ->make(true);
    }
}
