<?php

namespace App\Http\Controllers;

use App\Models\RegPeriksa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegPeriksaController extends Controller
{
    private $tanggal;
    public function __construct()
    {
        $this->tanggal = new Carbon();
    }
    public function caraRegistrasi(Request $request)
    {
        $awal = $this->tanggal->startOfMonth()->day;
        $akhir = $this->tanggal->lastOfMonth()->day;

        $request->bulan ? $bulan = $request->bulan : $bulan = $this->tanggal->month;
        $request->tahun ? $tahun = $request->tahun : $tahun = date('Y');

        $jumlahHari = $this->tanggal->day(1)->month($bulan)->daysInMonth;

        $reg = RegPeriksa::select(DB::raw('count(*) as jumlah'), 'tgl_registrasi')
            ->whereMonth('tgl_registrasi', $bulan)
            ->whereYear('tgl_registrasi', $tahun)
            ->whereIn('kd_poli', ['P001', 'P003', 'P005', 'P007', 'P008', 'P009'])
            ->groupBy('tgl_registrasi')
            ->doesntHave('bookingRegistrasi')
            ->where('status_lanjut', 'Ralan')
            ->where('stts', 'Sudah')
            ->get();

        $regBooking = RegPeriksa::select(DB::raw('count(*) as jumlah'), 'tgl_registrasi')
            ->whereMonth('tgl_registrasi', $bulan)
            ->whereYear('tgl_registrasi', $tahun)
            ->whereIn('kd_poli', ['P001', 'P003', 'P005', 'P007', 'P008', 'P009'])
            ->groupBy('tgl_registrasi')
            ->whereHas('bookingRegistrasi')
            ->where('status_lanjut', 'Ralan')
            ->where('stts', 'Sudah')
            ->get();
        foreach ($regBooking as $rb) {
            $jumlahB[] = $rb->jumlah;
        }
        foreach ($reg as $r) {
            $jumlah[] = $r->jumlah;
            $tgl[] = $this->tanggal->parse($r->tgl_registrasi)->translatedFormat('d F');
        }
        return [
            'regLangsung' => $jumlah,
            'regBooking' => $jumlahB,
            'tanggal' => $tgl,
        ];
    }
}
