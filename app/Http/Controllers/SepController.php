<?php

namespace App\Http\Controllers;

use App\Models\BridgeSep;
use App\Models\BridgingSep;
use App\Models\RegPeriksa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

use function PHPUnit\Framework\isNull;

class SepController extends Controller
{
    private $tanggal;

    public function __construct()
    {
        //Do your magic here
        $this->tanggal = new Carbon('this month');
    }

    public function jumlahSepRalan(Request $request)
    {

        $tgl_pertama = $request->tgl_pertama;
        $tgl_kedua = $request->tgl_kedua;
        $poli = $request->poli;

        $data = RegPeriksa::getSepRalan()->orderBy('stts', 'DESC');

        $dataRanap = RegPeriksa::getSepRanap();
        $row = [];


        if ($request->ajax()) {

            //filter tangal

            if ($tgl_pertama && $tgl_kedua) {
                $data->whereBetween('tgl_registrasi', [$tgl_pertama, $tgl_kedua])
                    ->with('bridgingSep');
                $dataRanap->whereBetween('tgl_registrasi', [$tgl_pertama, $tgl_kedua])
                    ->with('bridgingSep');
            } else {
                $data->where('tgl_registrasi', $this->tanggal->now()->format('Y-m-d'))
                    ->with('bridgingSep');
                $dataRanap->where('tgl_registrasi', $this->tanggal->now()->format('Y-m-d'))
                    ->with('bridgingSep');
            }

            if ($poli) {
                $data->whereHas('poli', function ($query) use ($poli) {
                    $query->where('nm_poli', 'like', '%' . $poli . '%');
                });
            }


            if ($request->has('search') && $request->get('search')['value']) {
                $data->whereHas('pasien', function ($query) use ($request) {
                    $query->where('nm_pasien', 'like', '%' . $request->get('search')['value'] . '%');
                });
            }

            $collection2 = collect($dataRanap->get());
            foreach ($data->get() as $d) {

                if ($d->bridgingSep == null) {
                    if ($collection2->where('no_rkm_medis', $d->no_rkm_medis)->first() == null) {
                        $d->bridgingSep = null;
                    } else {
                        continue;
                    }
                } else {
                    $d->bridgingSep = $this->tanggal->parse($d->bridgingSep->tglsep)->translatedFormat('d F Y');
                }

                $row[] = [
                    'no_rawat' => $d->no_rawat,
                    'tgl_registrasi' => $this->tanggal->parse($d->tgl_registrasi)->translatedFormat('d F Y'),
                    'nm_pasien' => $d->pasien->nm_pasien,
                    'poliklinik' => $d->poli->nm_poli,
                    'bridging_sep' => $d->bridgingSep,
                ];
            }
        }

        $dataCollect = collect($row);

        return response()->json(
            [
                'sudah' => $dataCollect->whereNotNull('bridging_sep')->count(),
                'belum' => $dataCollect->whereNull('bridging_sep')->count(),
                'semua' => $dataCollect->count(),
            ]
        );

        return redirect('/ralan/sep');
    }

    public function getSepRanap(Request $request)
    {

        $tanggal = $this->tanggal;
        $tgl_pertama = $request->tgl_pertama;
        $tgl_kedua = $request->tgl_kedua;
        $poli = $request->poli;
        $sekarang = $tanggal->now()->format('Y-m-d');

        $data = RegPeriksa::getSepRanap();

        if ($request->ajax()) {
            if ($tgl_pertama && $tgl_kedua) {
                $data->whereBetween('tgl_registrasi', [$tgl_pertama, $tgl_kedua])
                    ->with('bridgingSep');
            } else {
                $data->where('tgl_registrasi', $sekarang)
                    ->with('bridgingSep');
            }

            if ($poli) {
                $data->whereHas('poliklinik', function ($q) use ($poli) {
                    $q->where('nm_poli', '%' . $poli . '%');
                });
            }

            $collection2 = collect($data->get());

            return DataTables::of($collection2)

                ->make(true);
        }
    }
}
