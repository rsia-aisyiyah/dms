<?php

namespace App\Http\Controllers;

use App\Models\RegPeriksa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BerandaController extends Controller
{

    private $awal;
    private $akhir;
    private $tanggal;
    private $collectionDokter;

    public function __construct()
    {
        $this->tanggal = new Carbon();

        $dokter = app('App\Http\Controllers\DokterController')->getDokterSpesialis();
        $this->collectionDokter = collect($dokter);

    }

    public function index()
    {
        return view(
            'dashboard.content.beranda.beranda',
            [
                'bigTitle' => 'Beranda Informasi Layanan',
            ]
        );
    }
    public function jsonKunjunganDokter(Request $request, $tahun = '', $bulan = '')
    {
        $awal = $this->tanggal->startOfMonth()->day;
        $akhir = $this->tanggal->lastOfMonth()->day;

        // return $namaBulan = $this->tanggal->months(2);
        $jumlahHari = $this->tanggal->day(1)->month($bulan)->daysInMonth;
        if (empty($tahun) && empty($bulan)) {
            $tahun = date('Y');
            $bulan = $this->tanggal->now()->month;
            $namaBulan = $this->tanggal->now()->monthName;
            $jumlahHari = $this->tanggal->now()->day;
        } else {
            $namaBulan = $this->tanggal->month($bulan)->monthName;
        }

        foreach ($this->collectionDokter as $dokter) {

            $jumlah = array();
            $arrHari = array();
            $query = array();

            for ($i = $awal; $i <= $jumlahHari; $i++) {

                $query[] = RegPeriksa::
                    where('status_lanjut', 'Ralan')
                    ->where('stts', 'Sudah')
                    ->whereYear('tgl_registrasi', $tahun)
                    ->whereMonth('tgl_registrasi', $bulan)
                    ->whereDay('tgl_registrasi', $i)
                    ->whereHas('dokter', function ($q) use ($dokter) {
                        $q->where('kd_dokter', $dokter->kd_dokter);
                    })->get()->count();

                // $jumlah[] = $query->get();
                $arrHari[] = $i . ' ' . $namaBulan;
            };
            $jumlahDokter[] = $query;
            $nm_dokter[] = $dokter->nm_dokter;
        }

        // return $jumlah;
        return collect([
            "dokter1" => $jumlahDokter[0],
            "dokter2" => $jumlahDokter[1],
            "dokter3" => $jumlahDokter[2],
            "dokter4" => $jumlahDokter[3],
            "hari" => $arrHari,
            "nm_dokter" => $nm_dokter,
        ]);
    }
    public function countRanap(Request $request)
    {

        $tgl_pertama = $request->tgl_pertama;
        $tgl_kedua = $request->tgl_kedua;
        if ($request->ajax()) {
            $sekarang = $this->tanggal;
            $dataRanap = RegPeriksa::where('status_lanjut', 'Ranap')
                ->whereHas('kamarInap', function ($query) {
                    $query->where('stts_pulang', '!=', 'Pindah Kamar');
                });
            if ($tgl_pertama && $tgl_kedua) {
                $dataRanap->whereBetween('tgl_registrasi', [$tgl_pertama, $tgl_kedua]);
            } else {
                $dataRanap->whereYear('tgl_registrasi', date('Y'))
                    ->whereMonth('tgl_registrasi', $sekarang->month);
            }
            return $dataRanap->get()->count();
        } else {
            redirect('/');
        }
    }
    public function countRalan(Request $request)
    {
        $tgl_pertama = $request->tgl_pertama;
        $tgl_kedua = $request->tgl_kedua;
        if ($request->ajax()) {
            $sekarang = $this->tanggal;
            $statusRalan = RegPeriksa::whereIn('kd_poli', ['P001', 'P003', 'P007', 'P008', 'P009'])
                ->where('status_lanjut', 'Ralan')
                ->where('stts', 'Sudah');
            if ($tgl_pertama && $tgl_kedua) {
                $statusRalan->whereBetween('tgl_registrasi', [$tgl_pertama, $tgl_kedua]);
            } else {
                $statusRalan->whereYear('tgl_registrasi', date('Y'))
                    ->whereMonth('tgl_registrasi', $sekarang->month);
            }
            return $statusRalan->get()->count();
        } else {
            return redirect('/');
        }
    }
    public function countIGD(Request $request)
    {
        $tgl_pertama = $request->tgl_pertama;
        $tgl_kedua = $request->tgl_kedua;
        if ($request->ajax()) {
            $sekarang = $this->tanggal;
            $statusIGD = RegPeriksa::where('kd_poli', 'IGDK')
                ->where('stts', 'Sudah');
            if ($tgl_pertama && $tgl_kedua) {
                $statusIGD->whereBetween('tgl_registrasi', [$tgl_pertama, $tgl_kedua]);
            } else {
                $statusIGD->whereYear('tgl_registrasi', date('Y'))
                    ->whereMonth('tgl_registrasi', $sekarang->month);
            }
            return $statusIGD->get()->count();
        } else {
            return redirect('/');
        }
    }
    public function countTotal(Request $request)
    {
        if ($request->ajax()) {

            $igd = $this->countIGD($request);
            $ralan = $this->countRalan($request);
            $ranap = $this->countRanap($request);

            return $igd + $ralan + $ranap;
        }
    }
    public function countPembiayaanRalan(Request $request)
    {
        $tgl_pertama = $request->tgl_pertama;
        $tgl_kedua = $request->tgl_kedua;
        $query = RegPeriksa::select(DB::raw('count(*) as jumlah'), 'kd_pj')
            ->whereIn('kd_poli', ['P001', 'P003', 'P007', 'P008', 'P009'])
            ->where('status_lanjut', 'Ralan')
            ->where('stts', 'Sudah');
        // if ($request->ajax()) {
        if ($tgl_pertama && $tgl_kedua) {
            $query->whereBetween('tgl_registrasi', [$tgl_pertama, $tgl_kedua]);

        } else {
            $query->whereYear('tgl_registrasi', date('Y'))
                ->whereMonth('tgl_registrasi', $this->tanggal->month);

        }
        $dataRalan = $query->groupBy('kd_pj')->get()->pluck('jumlah');
        // }
        return $pembiayaanRalan = [
            'mandiri' => $dataRalan[0],
            'pbi' => $dataRalan[1],
            'umum' => $dataRalan[2],
        ];
    }
    public function countPembiayaanRanap(Request $request)
    {
        $tgl_pertama = $request->tgl_pertama;
        $tgl_kedua = $request->tgl_kedua;
        $query = RegPeriksa::select(DB::raw('count(*) as jumlah'), 'kd_pj')
            ->where('status_lanjut', 'Ranap')
            ->whereHas('kamarInap', function ($query) {
                $query->where('stts_pulang', '!=', 'Pindah Kamar');
            });
        if ($tgl_pertama && $tgl_kedua) {
            $query->whereBetween('tgl_registrasi', [$tgl_pertama, $tgl_kedua]);
        } else {
            $query->whereYear('tgl_registrasi', date('Y'))
                ->whereMonth('tgl_registrasi', $this->tanggal->month);
        }

        $dataRanap = $query->groupBy('kd_pj')->get()->pluck('jumlah');
        return $pembiayaanRanap = [
            'mandiri' => $dataRanap[0],
            'pbi' => $dataRanap[1],
            'umum' => $dataRanap[2],
        ];

    }
    public function pembiayaan(Request $request)
    {
        return $data = [
            'ralan' => $this->countPembiayaanRalan($request),
            'ranap' => $this->countPembiayaanRanap($request),
        ];
    }
    public function statusPasien(Request $request)
    {
        $tgl_pertama = $request->tgl_pertama;
        $tgl_kedua = $request->tgl_kedua;

        $query = RegPeriksa::select(DB::raw('count(*) as jumlah'), 'stts_daftar')
            ->where('stts', 'Sudah');

        if ($tgl_pertama && $tgl_kedua) {
            $query->whereBetween('tgl_registrasi', [$tgl_pertama, $tgl_kedua]);
        } else {
            $query->whereYear('tgl_registrasi', date('Y'))
                ->whereMonth('tgl_registrasi', $this->tanggal->month);
        }

        $status = $query->groupBy('stts_daftar')
            ->get()->pluck('jumlah');
        return $status = [
            'lama' => $status[0],
            'baru' => $status[1],
        ];

    }

}
