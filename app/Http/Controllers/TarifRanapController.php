<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JnsPerawatanInap;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class TarifRanapController extends Controller
{
    public function index()
    {
        return view('dashboard.content.tarif.tarif_ranap', [
            'title' => 'Tarif Layanan Rawat Inap',
            'bigTitle' => 'Tarif Layanan Rawat Inap'
        ]);
    }
    public function getLastTarif(Request $request)
    {
        if($request->ajax()){
            $tarif = JnsPerawatanInap::get()->last();
            $akhir = explode('RI', $tarif->kd_jenis_prw);
            return 'RI'.sprintf('%05d',(int)$akhir[1]+1);
        }
    }
    public function getTarif(Request $request)
    {
        $kategori = $request->kategori;
        $kamar   = $request->bangsal;
        $kelas   = $request->kelas;
        $pembiayaan   = $request->pembiayaan;
        $tarif = JnsPerawatanInap::query()->where('status', '1');

        if($request->ajax()){
            if($kategori){
                $tarif->whereHas('kategoriPerawatan', function($query) use ($kategori){
                    $query->where('kd_kategori', $kategori);
                });
            }
            if($kamar){
                $tarif->where('kd_bangsal', $kamar);
            }
            if($kelas){
                $tarif->where('kelas', $kelas);
            }
            if($pembiayaan){
                $tarif->whereHas('penjab', function($query) use ($pembiayaan){
                    $query->where('png_jawab','like','%'.$pembiayaan.'%');
                });
            }
        }
        return DataTables::of($tarif)
        ->filter(function ($query) use ($request) {
            if ($request->has('search') && $request->get('search')['value']) {
                $query->where('nm_perawatan', 'like', '%'.$request->get('search')['value'].'%');
            }
        })
        ->editColumn('kd_kategori', function ($tarif){
            return $tarif->kategoriPerawatan->nm_kategori;
        })
        ->editColumn('kd_bangsal', function($tarif){
            return $tarif->bangsal->nm_bangsal;
        })
        ->editColumn('kd_pj', function($tarif){
            return $tarif->penjab->png_jawab;
        })
        ->make(true);
    }
    public function getTarifById($id)
    {
        $tarif = JnsPerawatanInap::where('status', '1')->where('kd_jenis_prw', $id)
        ->with(['bangsal', 'kategoriPerawatan', 'penjab'])->get();

        return json_encode($tarif);
    }
    public function setTarif(Request $request)
    {
        $kd_jenis_prw = $request->kd_jenis_prw;
        $nm_perawatan = $request->nm_perawatan;
        $kategori= $request->kd_kategori;
        $bangsal= $request->kd_bangsal;
        $kelas= $request->kelas;
        $kd_pj= $request->kd_pj;
        $material = $request->material;
        $bhp = $request->bhp;
        $tarif_tindakandr = $request->tarif_tindakandr;
        $tarif_tindakanpr= $request->tarif_tindakanpr;
        $kso= $request->kso;
        $menejemen= $request->menejemen;
        $total_byrdr= $request->total_byrdr;
        $total_byrpr= $request->total_byrpr;
        $total_byrdrpr= $request->total_byrdrpr;
        DB::table('jns_perawatan_inap')->where('kd_jenis_prw', $kd_jenis_prw)->update(
            [
                'nm_perawatan' => strtoupper($nm_perawatan),
                'kd_kategori' => $kategori,
                'kd_bangsal' => $bangsal,
                'kelas' => $kelas,
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
    public function addTarif(Request $request)
    {
        $kd_jenis_prw = $request->kd_jenis_prw;
        $nm_perawatan = $request->nm_perawatan;
        $kategori= $request->kd_kategori;
        $bangsal= $request->kd_bangsal;
        $kelas= $request->kelas;
        $kd_pj= $request->kd_pj;
        $material = $request->material;
        $bhp = $request->bhp;
        $tarif_tindakandr = $request->tarif_tindakandr;
        $tarif_tindakanpr= $request->tarif_tindakanpr;
        $kso= $request->kso;
        $menejemen= $request->menejemen;
        $total_byrdr= $request->total_byrdr;
        $total_byrpr= $request->total_byrpr;
        $total_byrdrpr= $request->total_byrdrpr;
        DB::table('jns_perawatan_inap')->insert(
            [
                'kd_jenis_prw' => $kd_jenis_prw,
                'nm_perawatan' => strtoupper($nm_perawatan),
                'kd_kategori' => $kategori,
                'kelas' => $kelas,
                'kd_bangsal' => $bangsal,
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
