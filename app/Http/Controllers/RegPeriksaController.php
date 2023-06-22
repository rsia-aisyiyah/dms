<?php

namespace App\Http\Controllers;

use App\Models\BookingRegistrasi;
use App\Models\RegPeriksa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Contracts\DataTable;
use Yajra\DataTables\DataTables;

use function PHPSTORM_META\map;

class RegPeriksaController extends Controller
{
    private $tanggal;
    public function __construct()
    {
        $this->tanggal = new Carbon();
    }

    public function caraBooking(Request $request)
    {
        $tgl_pertama = $request->tgl_pertama;
        $tgl_kedua = $request->tgl_kedua;
        $bulan = $this->tanggal->month;
        $reg = BookingRegistrasi::select(DB::raw('count(*) as jumlah'))
            ->whereIn('kd_poli', ['P001', 'P003', 'P005', 'P007', 'P008', 'P009'])
            ->groupBy('limit_reg');
        if ($tgl_pertama && $tgl_kedua) {
            $reg->whereBetween('tanggal_periksa', [$tgl_pertama, $tgl_kedua])
                ->whereHas('regPeriksa', function ($q) use ($tgl_pertama, $tgl_kedua) {
                    $q->whereBetween('tgl_registrasi', [$tgl_pertama, $tgl_kedua])
                        ->where('stts', 'Sudah')
                        ->where('status_lanjut', 'Ralan');
                });
        } else {
            $reg->whereMonth('tanggal_periksa', $bulan)
                ->whereYear('tanggal_periksa', date('Y'))
                ->whereHas('regPeriksa', function ($q) use ($bulan) {
                    $q->whereMonth('tgl_registrasi', $bulan)
                        ->whereYear('tgl_registrasi', date('Y'))
                        ->where('stts', 'Sudah')
                        ->where('status_lanjut', 'Ralan');
                })->with('regPeriksa');
        }

        $booking = $reg->get()->pluck('jumlah');
        return $booking = [
            'offline' => @$booking[0],
            'online' => @$booking[1],
        ];
    }
    public function caraRegistrasi(Request $request)
    {
        $awal = $this->tanggal->startOfMonth()->day;
        $akhir = $this->tanggal->lastOfMonth()->day;

        $request->bulan ? $bulan = $request->bulan : $bulan = $this->tanggal->month;
        $request->tahun ? $tahun = $request->tahun : $tahun = date('Y');

        $jumlahHari = $this->tanggal->day(1)->month($bulan)->daysInMonth;

        $reg = RegPeriksa::regLangsung()
            ->groupBy('tgl_registrasi')
            ->whereMonth('tgl_registrasi', $bulan)
            ->whereYear('tgl_registrasi', $tahun)
            ->get();

        $regBooking = RegPeriksa::regBooking()
            ->whereMonth('tgl_registrasi', $bulan)
            ->whereYear('tgl_registrasi', $tahun)
            ->groupBy('tgl_registrasi')
            ->get();

        foreach ($regBooking as $rb) {
            $jumlahB[] = $rb->jumlah;
        }
        foreach ($reg as $r) {
            $jumlah[] = $r->jumlah;
            $tgl[] = $this->tanggal->parse($r->tgl_registrasi)->translatedFormat('d F');
        }
        return [
            'regLangsung' => @$jumlah,
            'regBooking' => @$jumlahB,
            'tanggal' => @$tgl,
        ];
    }
    public function statusRegistrasi(Request $request)
    {
        $tgl_pertama = $request->tgl_pertama;
        $tgl_kedua = $request->tgl_kedua;
        $bulan = $this->tanggal->month;

        $reg = RegPeriksa::select('stts', DB::raw('count(*) as jumlah'))
            ->whereIn('kd_poli', ['P001', 'P003', 'P005', 'P007', 'P008', 'P009'])
            ->where('status_lanjut', 'Ralan')
            ->whereIn('stts', ['Batal', 'Sudah'])
            ->groupBy('stts');

        if ($tgl_pertama && $tgl_kedua) {
            $reg->whereBetween('tgl_registrasi', [$tgl_pertama, $tgl_kedua]);
        } else {
            $reg->whereMonth('tgl_registrasi', $bulan)
                ->whereYear('tgl_registrasi', date('Y'));
        }

        $statusReg = $reg->get();

        @$sudah = $statusReg[0]['jumlah'] == 0 ? 0 : $statusReg[0]['jumlah'];
        @$batal = $statusReg[1]['jumlah'] == 0 ? 0 : $statusReg[1]['jumlah'];

        return [
            'sudah' => $sudah,
            'batal' => $batal,
        ];
    }

    public function ambilResep(Request $request)
    {
        $tgl_pertama = $request->tgl_pertama;
        $tgl_kedua = $request->tgl_kedua;

        $regPeriksa = RegPeriksa::where('status_lanjut', 'Ralan')
            ->where('stts', 'Sudah')->with(['pasien', 'resepObat.resepDokter', 'resepObat.resepDokterRacikan', 'pemberianObat', 'dokter', 'poli']);

        if ($tgl_pertama && $tgl_kedua) {

            $regPeriksa->whereBetween('tgl_registrasi', [$tgl_pertama, $tgl_kedua])->whereHas('dokter.spesialis', function ($query) use ($request) {
                $query->where('nm_sps', 'like', '%' . $request->poli . '%');
            })->get();
        } else {
            $regPeriksa->whereMonth('tgl_registrasi', date('m'))
                ->whereYear('tgl_registrasi', date('Y'))->get();
        }

        return $regPeriksa;
    }

    public function ambilResepTabel(Request $request)
    {
        return DataTables::of($this->ambilResep($request))
            ->editColumn('nm_pasien', function ($data) {
                return $data->pasien->nm_pasien;
            })
            ->editColumn('status', function ($data) {
                return $this->statusResep($data->resepObat, $data->pemberianObat);
            })
            ->editColumn('poliklinik', function ($data) {
                return $data->poli->nm_poli;
            })
            ->editColumn('dokter', function ($data) {
                return $data->dokter->nm_dokter;
            })
            ->make(true);
    }

    public function statusResep($resep, $pemberian)
    {
        $countResep = count($resep);
        $countObat = count($pemberian);

        $status = '';

        if ($countResep > 0 && $countObat > 0) {
            $status = 'LENGKAP';
        } else if ($countResep == 0 && $countObat > 0) {
            $status = 'TIDAK ADA RESEP';
        } else if ($countResep > 0 && $countObat == 0) {
            $status = 'TIDAK DIAMBIL';
        } else {
            $status =  '-';
        }

        return $status;
    }

    public function hitungStatusResep(Request $request)
    {
        $resep =  $this->ambilResep($request)->get();
        $total =  $this->ambilResep($request)->count();
        $lengkap = 0;
        $tanpaResep = 0;
        $tidakAmbil = 0;
        $kosong = 0;

        foreach ($resep as $val) {
            $status = $this->statusResep($val->resepObat, $val->pemberianObat);
            if ($status == 'LENGKAP') {
                $lengkap += 1;
            } else if ($status == 'TIDAK ADA RESEP') {
                $tanpaResep += 1;
            } else if ($status == 'TIDAK DIAMBIL') {
                $tidakAmbil += 1;
            } else if ($status == '-') {
                $kosong += 1;
            }

            // print_r($status);
        }

        return response()->json([
            'total' => $total,
            'lengkap' => $lengkap,
            'tanpaResep' => $tanpaResep,
            'tidakAmbil' => $tidakAmbil,
            'kosong' => $kosong,
            // 'resep' => $resep,

        ]);
        // return $resep->map(function ($result) use ($lengkap) {
        //     if ($status == 'LENGKAP') {
        //         $lengkap += 1;
        //     }
        //     return [
        //         'lengkap' => $lengkap
        //     ];
        //     // return $this->statusResep($result->resepObat, $result->pemberianObat);
        //     // return $result->resepObat;
        // });
        // return [
        //     'pasien' => $resep->count(),
        //     'resep' => $resep->get(),
        //     // 'obat' => count($pemberian),
        // ];
    }
}
