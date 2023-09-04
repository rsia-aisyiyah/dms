<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResumePasienRanap extends Controller
{
    function shk() {
        return view('dashboard.content.monitoring.monitoring-shk', [
            'bigTitle' => 'Monitoring Resume Pasien Rawat Inap',
        ]);
    }
}
