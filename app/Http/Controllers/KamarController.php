<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class KamarController extends Controller
{
    //
    public function getTarif(Request $request)
    {
        $status = $request->status;
        $kelas = $request->kelas;
        $kamar = Kamar::where('statusdata', '1');

        if ($request->ajax()) {
            if ($status) {
                $kamar->where('status', $status)->get();
            }
            if ($kelas) {
                $kamar->where('kelas', $kelas)->get();
            }
        }

        return DataTables::of($kamar)
            ->filter(function ($query) use ($request) {
                if ($request->has('search') && $request->get('search')['value']) {
                    return $query->whereHas('bangsal', function ($query) use ($request) {
                        $query->where('nm_bangsal', 'like', '%' . $request->get('search')['value'] . '%');
                    });
                }
            })
            ->editColumn('nm_bangsal', function ($kamar) {
                return $kamar->bangsal->nm_bangsal;
            })
            ->editColumn('trf_kamar', function ($kamar) {
                return $kamar->trf_kamar;
            })
            ->editColumn('action', function ($kamar) {
                $kd_kamar = $kamar->kd_bangsal;
                return '<button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-default" onclick=' . "ubahtarif('$kd_kamar')" . '>Ubah Tarif</button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public function index()
    {
        return view('dashboard.content.kamar.list_tarifkamar', [
            'title' => 'Tarif Kamar',
            'bigTitle' => 'Tarif Kamar'
        ]);
    }
    public function getTarifById($kd_bangsal)
    {
        $kamar = Kamar::where('kd_bangsal', $kd_bangsal)
            ->where('statusdata', '1')
            ->with('bangsal')->get();

        return json_encode($kamar);
    }
    public function setTarifKamar(Request $request)
    {
        $kd_bangsal = $request->kd_bangsal;
        $tarif = $request->tarif;
        DB::table('kamar')->where('kd_bangsal', $kd_bangsal)->update(
            [
                'trf_kamar' => $tarif
            ]
        );
    }
}
