<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RekamMedisController extends Controller
{
    public function monitoringUgd()
    {
        return view('dashboard.content.rekammedis.monitoring-ugd', [
            'bigTitle' => 'Monitoring Berkas RM UGD',
        ]);
    }

    public function monitoringRanap()
    {
        return view('dashboard.content.rekammedis.monitoring-ranap', [
            'bigTitle' => 'Monitoring Berkas RM Rawat Inap',
        ]);
    }

    public function pengisianErm() {
        return view('dashboard.content.monitoring.pengisian-erm', [
            'bigTitle' => 'Pengisian ERM Dokter Spesialis',
        ]);
    }
}
