<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FarmasiController extends Controller
{
    public function umum()
    {
        return view('dashboard.content.farmasi.dashboard-umum', [
            'bigTitle' => 'Dashboard Farmasi',
        ]);
    }
