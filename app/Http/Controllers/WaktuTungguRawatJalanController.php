<?php

namespace App\Http\Controllers;

use App\Services\WaktuTungguRawatJalan as ServicesWaktuTungguRawatJalan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class WaktuTungguRawatJalanController extends Controller
{
    protected $service;
     public function __construct() {
        $this->service = new ServicesWaktuTungguRawatJalan();
     }
    public function index(){
        return view(
            'dashboard.content.ralan.table_waktu_tunggu',
            [
                'title' => 'Waktu Tunggu Rawat Jalan',
                'bigTitle' => 'Waktu Tunggu Rawat Jalan',
            ]
        );
    }

    public function get(Request $request){
        $data = $this->service->get($request)->get();
        return DataTables::of($data)
            ->setRowClass(function ($data) {
                return $data->estimasi ? '' : 'text-danger';
            })
        ->make(true);
    }

    public function getByYear($tahun = null){
        $tahun = $tahun ?? date('Y');
        $data = $this->service->groupByMonth($tahun);
        return DataTables::of($data)->make(true);
    }
}
