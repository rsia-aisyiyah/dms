<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class FarmasiController extends Controller
{
    public function umum()
    {
        return view('dashboard.content.farmasi.dashboard-umum', [
            'bigTitle' => 'Dashboard Farmasi',
        ]);
    }

    public function persediaan()
    {
        return view('dashboard.content.farmasi.dashboard-persediaan', [
            'bigTitle' => 'Persediaan Obat',
        ]);
    }

    public function metricsGudang(Request $request)
    {
        $data = \App\Models\DetailPemberianObat::select('status')
            ->selectRaw("SUM(total) AS total");

        if ($request->tgl_perawatan) {
            $data = $data->whereMonth('tgl_perawatan', $request->tgl_perawatan['bulan'])->whereYear('tgl_perawatan', $request->tgl_perawatan['tahun']);
        } else {
            $data = $data->whereMonth('tgl_perawatan', date('m'))->whereYear('tgl_perawatan', date('Y'));
        }

        $data = $data->groupBy('status')->get();

        return response()->json([
            'success' => true,
            'data'    => $data
        ], 200);
    }

    public function pesananGudang(Request $request)
    {
        $data = \App\Models\Pemesanan::select('status')->selectRaw('SUM(tagihan) AS total_tagihan');

        if ($request->tgl) {
            $data = $data->whereMonth($request->tgl['type'] ?? 'tgl_pesan', $request->tgl['bulan'])->whereYear($request->tgl['type'] ?? 'tgl_pesan', $request->tgl['tahun']);
        } else {
            $data = $data->whereMonth($request->tgl['type'] ?? 'tgl_pesan', date('m'))->whereYear($request->tgl['type'] ?? 'tgl_pesan', date('Y'));
        }

        $data = $data->groupBy('status')->get();
        
        return response()->json([
            'success' => true,
            'data'    => $data
        ], 200);
    }

    public function metricsGudangTopObat(Request $request)
    {
        $data = \App\Models\DetailPemberianObat::select('kode_brng')
        ->selectRaw('SUM(jml) AS total')
        ->with(['obat' => function ($q) {
            $q->select('nama_brng', 'kode_brng', 'kdjns');
        }])->whereHas('obat', function ($q) {
            $q->whereIn('kdjns', ['J035', 'J036', 'J037', 'J038']);
        });

        if ($request->tgl_perawatan) {
            $data = $data->whereMonth('tgl_perawatan', $request->tgl_perawatan['bulan'])->whereYear('tgl_perawatan', $request->tgl_perawatan['tahun']);
        } else {
            $data = $data->whereMonth('tgl_perawatan', date('m'))->whereYear('tgl_perawatan', date('Y'));
        }

        $data = $data->groupBy('kode_brng')->orderBy('total', 'DESC')->limit(10)->get();

        if ($request->datatables) {
            if ($request->datatables == 1 || $request->datatables == true || $request->datatables == 'true') {
                $data = $data->map(function ($item) {
                    return [
                        'kode_obat' => $item->kode_brng,
                        'nama_obat' => $item->obat->nama_brng,
                        'jenis_obat' => $item->obat->kdjns,
                        'total' => $item->total,
                    ];
                });
                
                return DataTables::of($data)->make(true);
            } else {
                return response()->json([
                    'success' => true,
                    'data'    => $data
                ], 200);
            }
        } else {
            return response()->json([
                'success' => true,
                'data'    => $data
            ], 200);
        }
    }

    public function metricsGudangBottomObat(Request $request)
    {
        $data = \App\Models\DetailPemberianObat::select('kode_brng')
        ->selectRaw('SUM(jml) AS total')
        ->with(['obat' => function ($q) {
            $q->select('nama_brng', 'kode_brng', 'kdjns');
        }])->whereHas('obat', function ($q) {
            $q->whereIn('kdjns', ['J035', 'J036', 'J037', 'J038']);
        });

        if ($request->tgl_perawatan) {
            $data = $data->whereMonth('tgl_perawatan', $request->tgl_perawatan['bulan'])->whereYear('tgl_perawatan', $request->tgl_perawatan['tahun']);
        } else {
            $data = $data->whereMonth('tgl_perawatan', date('m'))->whereYear('tgl_perawatan', date('Y'));
        }

        $data = $data->groupBy('kode_brng')->orderBy('total', 'ASC')->limit(50)->get();

        if ($request->datatables) {
            if ($request->datatables == 1 || $request->datatables == true || $request->datatables == 'true') {
                $data = $data->map(function ($item) {
                    return [
                        'kode_obat' => $item->kode_brng,
                        'nama_obat' => $item->obat->nama_brng,
                        'jenis_obat' => $item->obat->kdjns,
                        'total' => $item->total,
                    ];
                });
                
                return DataTables::of($data)->make(true);
            } else {
                return response()->json([
                    'success' => true,
                    'data'    => $data
                ], 200);
            }
        } else {
            return response()->json([
                'success' => true,
                'data'    => $data
            ], 200);
        }
    }

    public function metricsGudangDetail(Request $request)
    {
        $data = \App\Models\DetailPemberianObat::select('tgl_perawatan')
            ->selectRaw('COUNT(CASE WHEN status = "ralan" THEN no_rawat END) AS count_no_rawat_ralan')
            ->selectRaw('COUNT(CASE WHEN status = "ranap" THEN no_rawat END) AS count_no_rawat_ranap');

        if ($request->tgl_perawatan) {
            $data = $data->whereMonth('tgl_perawatan', $request->tgl_perawatan['bulan'])->whereYear('tgl_perawatan', $request->tgl_perawatan['tahun']);
        } else {
            $data = $data->whereMonth('tgl_perawatan', date('m'))->whereYear('tgl_perawatan', date('Y'));
        }

        $data = $data->groupBy('tgl_perawatan')->get();

        return response()->json([
            'success' => true,
            'data'    => $data
        ], 200);
    }
}
