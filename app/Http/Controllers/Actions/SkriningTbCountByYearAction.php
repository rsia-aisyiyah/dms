<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use App\Models\RegPeriksa;
use App\Models\RsiaSkriningTb;
use Carbon\Carbon;

class SkriningTbCountByYearAction extends Controller
{

    public function __invoke(RegPeriksa $registrasi, RsiaSkriningTb $skrining, $year = null)
    {

        $totalRegistrasi = $registrasi->year($year)
            ->where('stts', 'Sudah')->get();

        $data = $skrining->getCountByYear($year)->map(function ($item) use ($totalRegistrasi) {
            return [
                'count' => $item->count,
                'tanggal' => Carbon::parse($item->tanggal)->translatedFormat('F'),
                'capaian' => $totalRegistrasi ? number_format(($item->count / $totalRegistrasi->count()) * 100, 3) : 0,
            ];
        });

        return [
            'data' => $data->pluck('count'),
            'label' => $data->pluck('tanggal'),
            'capaian' => $data->pluck('capaian'),
        ];

    }
}
