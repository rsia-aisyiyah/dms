<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class KamarController extends Controller
{
    //
    public function getTarif(Request $request)
    {
       $status = $request->status;
       $kelas = $request->kelas;
        $kamar = Kamar::where('statusdata', '1');

        if($request->ajax()){
            if($status){
                $kamar->where('status', $status)->get();
            }
            if($kelas){
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
        ->editColumn('nm_bangsal', function($kamar){
            return $kamar->bangsal->nm_bangsal;
        })
        ->editColumn('trf_kamar', function($kamar){
            return 'Rp. '.number_format($kamar->trf_kamar, 0, ',', '.');
        })
        ->make(true);
    }
    public function index()
    {
        return view('dashboard.content.kamar.list_tarifkamar', [
            'title' => 'Tarif Kamar',
            'bigTitle' => 'Tarif Kamar'
        ]);
    }
}
