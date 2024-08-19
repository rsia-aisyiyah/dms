<?php

namespace App\Services;

use App\Models\RegPeriksa as ModelRegistrasi;
use App\Models\RsiaSkriningTb as ModelsRsiaSkriningTb;
use Carbon\Carbon;

class RsiaSkriningTb
{
    protected ModelsRsiaSkriningTb $rsiaSkriningTb;
    protected ModelRegistrasi $regPeriksa;

    public function __construct(ModelsRsiaSkriningTb $rsiaSkriningTb)
    {
        $this->rsiaSkriningTb = $rsiaSkriningTb;
        $this->regPeriksa = new ModelRegistrasi();
    }

    public function getCountByYear($year = null)
    {
        $totalRegistrasi = $this->regPeriksa->year($year)
            ->where('stts', 'Sudah')->get();

        $data = $this->rsiaSkriningTb->getByYear($year)->map(function ($item) use ($totalRegistrasi) {
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
