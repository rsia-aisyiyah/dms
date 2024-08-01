<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JnsPerawatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class TarifRalanController extends Controller
{
    public function getTarifAkhir()
    {
        $tarif = JnsPerawatan::get()->last();
        $akhir = explode('RJ', $tarif->kd_jenis_prw);
        return 'RJ' . sprintf('%05d', (int) $akhir[1] + 1);

    }
    public function getTarif(Request $request)
    {
        $kategori = $request->kategori;
        $poli = $request->poli;
        $pembiayaan = $request->pembiayaan;
        $tarif = JnsPerawatan::query()->where('status', '1');

        if ($request->ajax()) {
            if ($kategori) {
                $tarif->whereHas('kategoriPerawatan', function ($query) use ($kategori) {
                    $query->where('kd_kategori', $kategori);
                });
            }
            if ($poli) {
                $tarif->whereHas('poliklinik', function ($query) use ($poli) {
                    $query->where('kd_poli', $poli);
                });
            }
            if ($pembiayaan) {
                $tarif->whereHas('penjab', function ($query) use ($pembiayaan) {
                    $query->where('png_jawab', 'like', '%' . $pembiayaan . '%');
                });
            }
        }
        return DataTables::of($tarif)
            ->filter(function ($query) use ($request) {
                if ($request->has('search') && $request->get('search')['value']) {
                    $query->where('nm_perawatan', 'like', '%' . $request->get('search')['value'] . '%');
                }
            })
            ->editColumn('kd_kategori', function ($tarif) {
                return $tarif->kategoriPerawatan->nm_kategori;
            })
            ->editColumn('kd_poli', function ($tarif) {
                return $tarif->poliklinik->nm_poli;
            })
            ->editColumn('kd_pj', function ($tarif) {
                return $tarif->penjab->png_jawab;
            })
            ->make(true);
    }

    public function getTarifById($id)
    {
        $tarif = JnsPerawatan::where('status', '1')->where('kd_jenis_prw', $id)
            ->with(['poliklinik', 'kategoriPerawatan', 'penjab'])->get();

        return json_encode($tarif);
    }

    public function index()
    {

        return view('dashboard.content.tarif.tarifralan', [
            'title' => 'Tarif Layanan Rawat Jalan',
            'bigTitle' => 'Tarif Layanan Rawat Jalan',
        ]);
    }
    public function setTarifRalan(Request $request)
    {
        $kd_jenis_prw = $request->kd_jenis_prw;
        $nm_perawatan = $request->nm_perawatan;
        $kategori = $request->kd_kategori;
        $poli = $request->kd_poli;
        $kd_pj = $request->kd_pj;
        $material = $request->material;
        $bhp = $request->bhp;
        $tarif_tindakandr = $request->tarif_tindakandr;
        $tarif_tindakanpr = $request->tarif_tindakanpr;
        $kso = $request->kso;
        $menejemen = $request->menejemen;
        $total_byrdr = $request->total_byrdr;
        $total_byrpr = $request->total_byrpr;
        $total_byrdrpr = $request->total_byrdrpr;
        DB::table('jns_perawatan')->where('kd_jenis_prw', $kd_jenis_prw)->update(
            [
                'nm_perawatan' => strtoupper($nm_perawatan),
                'kd_kategori' => $kategori,
                'kd_poli' => $poli,
                'kd_pj' => $kd_pj,
                'material' => $material,
                'bhp' => $bhp,
                'tarif_tindakandr' => $tarif_tindakandr,
                'tarif_tindakanpr' => $tarif_tindakanpr,
                'kso' => $kso,
                'menejemen' => $menejemen,
                'total_byrdr' => $total_byrdr,
                'total_byrpr' => $total_byrpr,
                'total_byrdrpr' => $total_byrdrpr,

            ]
        );
    }
    public function addTarifRalan(Request $request)
    {
        $kd_jenis_prw = $request->kd_jenis_prw;
        $nm_perawatan = $request->nm_perawatan;
        $kategori = $request->kd_kategori;
        $poli = $request->kd_poli;
        $kd_pj = $request->kd_pj;
        $material = $request->material;
        $bhp = $request->bhp;
        $tarif_tindakandr = $request->tarif_tindakandr;
        $tarif_tindakanpr = $request->tarif_tindakanpr;
        $kso = $request->kso;
        $menejemen = $request->menejemen;
        $total_byrdr = $request->total_byrdr;
        $total_byrpr = $request->total_byrpr;
        $total_byrdrpr = $request->total_byrdrpr;
        if ($request->ajax()) {
            DB::table('jns_perawatan')->insert(
                [
                    'kd_jenis_prw' => $kd_jenis_prw,
                    'nm_perawatan' => strtoupper($nm_perawatan),
                    'kd_kategori' => $kategori,
                    'kd_poli' => $poli,
                    'kd_pj' => $kd_pj,
                    'material' => $material,
                    'bhp' => $bhp,
                    'tarif_tindakandr' => $tarif_tindakandr,
                    'tarif_tindakanpr' => $tarif_tindakanpr,
                    'kso' => $kso,
                    'menejemen' => $menejemen,
                    'total_byrdr' => $total_byrdr,
                    'total_byrpr' => $total_byrpr,
                    'total_byrdrpr' => $total_byrdrpr,
                    'total_byrdrpr' => $total_byrdrpr,
                    'status' => '1',
                ]
            );
        }
    }
}
