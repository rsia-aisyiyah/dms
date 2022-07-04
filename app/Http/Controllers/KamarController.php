<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class KamarController extends Controller
{
    //
    public function getTarif(Request $request)
    {
        $status = $request->status;
        $kelas = $request->kelas;

        if ($request->ajax()) {
            $kamar = Kamar::where('statusdata', '1');
            if ($status) {
                $kamar->where('status', $status)->get();
            }
            if ($kelas) {
                $kamar->where('kelas', $kelas)->get();
            }
        }

        return DataTables::of($kamar)
            ->editColumn('nm_bangsal', function ($kamar) {
                return $kamar->bangsal->nm_bangsal;
            })
            ->make(true);
    }
    public function index()
    {
        return view('dashboard.content.kamar.list_tarifkamar', [
            'title' => 'Tarif Kamar',
            'bigTitle' => 'Tarif Kamar',
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
        $status = $request->status;
        $tarif = $request->tarif;
        DB::table('kamar')->where('kd_bangsal', $kd_bangsal)->update(
            [
                'trf_kamar' => $tarif,
                'status' => $status,
            ]
        );
    }
}
