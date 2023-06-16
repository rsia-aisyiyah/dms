<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\AskepKandunganRalan;
use Carbon\Carbon;

class AskepKandunganRalanController extends Controller
{
    protected $askep;
    protected $carbon;
    public function __construct()
    {
        $this->askep = new AskepKandunganRalan;
        $this->carbon = new Carbon();
    }
    public function ambil(Request $request)
    {
        $askep = $this->askep->with('regPeriksa.pasien', 'regPeriksa.dokter', 'regPeriksa.penjab', 'regPeriksa.poli')->whereHas('regPeriksa', function ($query) {
            $query->where('status_lanjut', 'ralan');
        });

        if ($request->ajax()) {
            if ($request->tgl_pertama || $request->tgl_kedua) {
                $askep->whereBetween('tanggal', [$request->tgl_pertama, $request->tgl_kedua])
                    ->whereHas('regPeriksa', function ($query) use ($request) {
                        $query->whereHas('penjab', function ($query) use ($request) {
                            $query->where('png_jawab', 'like', '%' . $request->pembiayaan . '%');
                        });
                    })->whereHas('regPeriksa.dokter', function ($query) use ($request) {
                        if ($request->dokter) {
                            $query->where('kd_dokter', $request->dokter);
                        }
                    });
            } else {
                $askep->whereBetween('tanggal', [$this->carbon->startOfMonth()->toDateString(), $this->carbon->endOfMonth()->toDateString()]);
            }
        }
        // $askep->whereBetween('tanggal', [$this->carbon->startOfMonth()->toDateString(), $this->carbon->endOfMonth()->toDateString()]);

        return DataTables::of($askep)->make(true);
    }
}
