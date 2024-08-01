<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JnsPerawatanLab;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class TarifLaboratorium extends Controller
{
    //
    private $tanggal;
    public function __construct()
    {
        // parent::__construct();
        //Do your magic here
        $this->tanggal = new Carbon('this month');
    }
    public function index()
    {
        return view('dashboard.content.tarif.tarif_laboratorium', [
            'title' => 'Tarif Layanan Lab',
            'bigTitle' => 'Tarif Layanan Lab',
        ]);
    }

    public function getTarif(Request $request)
    {
        $tanggal = $this->tanggal;
        $pembiayaan = $request->pembiayaan;
        $kategori = $request->kategori;
        $kelas = $request->kelas;
        if ($request->ajax()) {
            $tarif = JnsPerawatanLab::where('status', '1')->with('penjab');
            if ($pembiayaan) {
                $tarif->whereHas('penjab', function ($query) use ($pembiayaan) {
                    $query->where('png_jawab', 'like', '%' . $pembiayaan . '%');
                });
            }
            if ($kategori) {
                $tarif->where('kategori', $kategori);
            }
            if ($kelas) {
                $tarif->where('kelas', $kelas);
            }
        }

        return DataTables::of($tarif->get())
            ->filter(function ($query) use ($request) {
                if ($request->has('search') && $request->get('search')['value']) {
                    $query->where('nm_perawatan', 'like', '%' . $request->get('search')['value'] . '%');
                }
            })
            ->editColumn('penjab', function ($tarif) {
                return $tarif->penjab->png_jawab;
            })
            ->make(true);
    }
    public function getTarifById($id, Request $request)
    {
        if ($request->ajax()) {
            $tarif = JnsPerawatanLab::where('kd_jenis_prw', $id)->with('penjab')->get();
            return json_encode($tarif);
        }
    }
    public function setTarif(Request $request)
    {
        if ($request->ajax()) {
            DB::table('jns_perawatan_lab')->where('kd_jenis_prw', $request->kd_jenis_prw)->update([
                'nm_perawatan' => $request->nm_perawatan,
                'bagian_rs' => $request->bagian_rs,
                'bhp' => $request->bhp,
                'tarif_perujuk' => $request->tarif_perujuk,
                'tarif_tindakan_dokter' => $request->tarif_tindakan_dokter,
                'tarif_tindakan_petugas' => $request->tarif_tindakan_petugas,
                'kso' => $request->kso,
                'menejemen' => $request->menejemen,
                'total_byr' => $request->total_byr,
                'kd_pj' => $request->kd_pj,
                'kelas' => $request->kelas,
                'kategori' => $request->kategori,
            ]);

        }
    }
    public function getLastTarif(Request $request)
    {
        if ($request->ajax()) {
            $tarif = JnsPerawatanLab::get()->last();
            $akhir = explode('J', $tarif->kd_jenis_prw);
            return 'J' . sprintf('%06d', (int) $akhir[1] + 1);
        }

    }
    public function addTarif(Request $request)
    {
        if ($request->ajax()) {
            DB::table('jns_perawatan_lab')->insert([
                'kd_jenis_prw' => $request->kd_jenis_prw,
                'nm_perawatan' => $request->nm_perawatan,
                'bagian_rs' => $request->bagian_rs,
                'bhp' => $request->bhp,
                'tarif_perujuk' => $request->tarif_perujuk,
                'tarif_tindakan_dokter' => $request->tarif_tindakan_dokter,
                'tarif_tindakan_petugas' => $request->tarif_tindakan_petugas,
                'kso' => $request->kso,
                'menejemen' => $request->menejemen,
                'total_byr' => $request->total_byr,
                'kd_pj' => $request->kd_pj,
                'kelas' => $request->kelas,
                'kategori' => $request->kategori,
                'status' => '1',
            ]);

        }

    }
}
