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

    //kunjungan pasien
    // public function index()
    // {

    //     $tanggal = new Carbon('this month');
    //     $sekarang = $tanggal->now();
    //     $awalBulan = $tanggal->startOfMonth();

    //     $dataRalan = RegPeriksa::whereIn('kd_poli', ['P001', 'P003', 'P007', 'P008', 'P009'])
    //         ->where('status_lanjut', 'Ralan')
    //         ->whereYear('tgl_registrasi', date('Y'))
    //         ->whereMonth('tgl_registrasi', $sekarang->month)
    //         ->where('stts', '!=', 'Batal');

    //     $dataIGD = RegPeriksa::where('kd_poli', 'IGDK')
    //         ->whereYear('tgl_registrasi', date('Y'))
    //         ->whereMonth('tgl_registrasi', $sekarang->month)
    //         ->where('stts', '!=', 'Batal');

    //     $dataRanap = RegPeriksa::where('status_lanjut', 'Ranap')
    //         ->whereYear('tgl_registrasi', date('Y'))
    //         ->whereMonth('tgl_registrasi', $sekarang->month)
    //         ->whereHas('kamarInap', function ($query) {
    //             $query->where('stts_pulang', '!=', 'Pindah Kamar');
    //         });

    //     $jumlRalan = $dataRalan->count();
    //     $jumlRanap = $dataRanap->count();
    //     $jumlIGD = $dataIGD->count();

    //     $ralan = $dataRalan->select(DB::raw('count(*) as jumlah'), 'kd_pj', 'stts_daftar')
    //         ->groupBy('kd_pj')
    //         ->get()
    //         ->pluck('jumlah');
    //     $igd = $dataIGD->select(DB::raw('count(*) as jumlah'), 'kd_pj', 'stts_daftar')
    //         ->groupBy('kd_pj')
    //         ->get()
    //         ->pluck('jumlah');
    //     $ranap = $dataRanap->select(DB::raw('count(*) as jumlah'), 'kd_pj', 'stts_daftar')
    //         ->groupBy('kd_pj')
    //         ->get()
    //         ->pluck('jumlah');

    //     $statusRalan = RegPeriksa::select(DB::raw('count(*) as jumlah'))
    //         ->whereIn('kd_poli', ['P001', 'P003', 'P007', 'P008', 'P009'])
    //         ->where('status_lanjut', 'Ralan')
    //         ->whereYear('tgl_registrasi', date('Y'))
    //         ->whereMonth('tgl_registrasi', $sekarang->month)
    //         ->where('stts', '!=', 'Batal')
    //         ->groupBy('stts_daftar')
    //         ->get()
    //         ->pluck('jumlah');

    //     $statusIGD = RegPeriksa::select(DB::raw('count(*) as jumlah'))
    //         ->where('kd_poli', 'IGDK')
    //         ->whereYear('tgl_registrasi', date('Y'))
    //         ->whereMonth('tgl_registrasi', $sekarang->month)
    //         ->where('stts', '!=', 'Batal')
    //         ->groupBy('stts_daftar')
    //         ->get()
    //         ->pluck('jumlah');

    //     $statusRanap = RegPeriksa::select(DB::raw('count(*) as jumlah'))
    //         ->where('status_lanjut', 'Ranap')
    //         ->whereYear('tgl_registrasi', date('Y'))
    //         ->whereMonth('tgl_registrasi', $sekarang->month)
    //         ->whereHas('kamarInap', function ($query) {
    //             $query->where('stts_pulang', '!=', 'Pindah Kamar');
    //         })
    //         ->groupBy('stts_daftar')
    //         ->get()
    //         ->pluck('jumlah');

    //     @$pembiayaan = [
    //         'umum' => $ralan[1] + $igd[1] + $ranap[1],
    //         'bpjs' => $ralan[0] + $ralan[2] + $igd[0] + $igd[2] + $ranap[0] + $ranap[2],
    //     ];
    //     @$status = [
    //         'baru' => $statusRalan[0] + $statusIGD[0] + $statusRanap[0],
    //         'lama' => $statusRalan[1] + $statusIGD[1] + $statusRanap[1],
    //     ];

    //     for ($i = 1; $i <= 12; $i++) {
    //         $ralanTahunan = RegPeriksa::whereIn('kd_poli', ['P001', 'P003', 'P007', 'P008', 'P009'])
    //             ->where('status_lanjut', 'Ralan')
    //             ->whereYear('tgl_registrasi', 2022)
    //             ->whereMonth('tgl_registrasi', $i)
    //             // ->where('stts', 'Sudah')
    //             ->where('stts', '!=', 'Batal')->count();

    //         $ranapTahunan = RegPeriksa::where('status_lanjut', 'Ranap')
    //             ->whereYear('tgl_registrasi', 2021)
    //             ->whereMonth('tgl_registrasi', $i)
    //             ->whereHas('kamarInap', function ($query) {
    //                 $query->where('stts_pulang', '!=', 'Pindah Kamar');
    //             })->count();
    //         $igdTahunan = RegPeriksa::where('kd_poli', 'IGDK')
    //             ->whereYear('tgl_registrasi', 2021)
    //             ->whereMonth('tgl_registrasi', $i)
    //             ->where('stts', '!=', 'Batal')
    //             ->count();

    //         $arrRalanTahunan[] = $ralanTahunan;
    //         $arrRanapTahunan[] = $ranapTahunan;
    //         $arrIgdTahunan[] = $igdTahunan;
    //     }

    //     $dataDiagram = [
    //         'ranap' => $arrRanapTahunan,
    //         'ralan' => $arrRalanTahunan,
    //         'igd' => $arrIgdTahunan
    //     ];

    //     $totalUmum = $pembiayaan['umum'];
    //     $totalBPJS = $pembiayaan['bpjs'];
    //     $totalLama = $status['lama'];
    //     $totalBaru = $status['baru'];

    //     // looping tanggal
    //     $awal = $this->tanggal->startOfMonth()->day;
    //     $akhir = $this->tanggal->lastOfMonth()->day;

    //     // dd($arrHari);

    //     $total = $jumlRalan + $jumlRanap + $jumlIGD;

    //     $poli = app('App\Http\Controllers\RalanController')->diagramRalanPoli();

    //     $contentPoli = $poli->getContent();
    //     $arrayPoli = json_decode($contentPoli, true);

    //     $diagramDokter = $this->jsonKunjunganDokter(date('Y'), $tanggal->month);
    //     $contentDiagramDr = $diagramDokter->getContent();
    //     $arrayDiagramDr = json_decode($contentDiagramDr, true);

    //     return view(
    //         'dashboard.content.beranda.beranda',
    //         [
    //             'bigTitle' => 'Beranda Informasi Layanan',
    //             'jumlRalan' => number_format($jumlRalan, 0, ',', '.'),
    //             'jumlIGD' => number_format($jumlIGD, 0, ',', '.'),
    //             'jumlRanap' => number_format($jumlRanap, 0, ',', '.'),
    //             'total' => number_format($total, 0, ',', '.'),
    //             'jumlBPJS' => $totalBPJS,
    //             'jumlUmum' => $totalUmum,
    //             'baru' => $totalBaru,
    //             'lama' => $totalLama,
    //             'ranap' => json_encode($dataDiagram['ranap']),
    //             'ralan' => json_encode($dataDiagram['ralan']),
    //             'igd' => json_encode($dataDiagram['igd']),
    //             'dataPoliAnak' => $arrayPoli['anak'],
    //             'dataPoliObgyn' => $arrayPoli['obgyn'],
    //             'labelHari' => $arrayDiagramDr["bulan"],
    //             'dokter1' => $arrayDiagramDr["dokter1"],
    //             'dokter2' => $arrayDiagramDr["dokter2"],
    //             'dokter3' => $arrayDiagramDr["dokter3"],
    //             'dokter4' => $arrayDiagramDr["dokter4"]
    //         ]
    //     );
    // }

    public function index()
    {
        return view(
            'dashboard.content.beranda.beranda',
            [
                'bigTitle' => 'Beranda Informasi Layanan',
                // 'jumlRalan' => number_format($jumlRalan, 0, ',', '.'),
                // 'jumlIGD' => number_format($jumlIGD, 0, ',', '.'),
                // 'jumlRanap' => number_format($jumlRanap, 0, ',', '.'),
                // 'total' => number_format($total, 0, ',', '.'),
                // 'jumlBPJS' => $totalBPJS,
                // 'jumlUmum' => $totalUmum,
                // 'baru' => $totalBaru,
                // 'lama' => $totalLama,
                // 'ranap' => json_encode($dataDiagram['ranap']),
                // 'ralan' => json_encode($dataDiagram['ralan']),
                // 'igd' => json_encode($dataDiagram['igd']),
                // 'dataPoliAnak' => $arrayPoli['anak'],
                // 'dataPoliObgyn' => $arrayPoli['obgyn'],
                // 'labelHari' => $arrayDiagramDr["bulan"],
                // 'dokter1' => $arrayDiagramDr["dokter1"],
                // 'dokter2' => $arrayDiagramDr["dokter2"],
                // 'dokter3' => $arrayDiagramDr["dokter3"],
                // 'dokter4' => $arrayDiagramDr["dokter4"]
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
