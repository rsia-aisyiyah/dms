<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class LaboratController extends Controller
{
    //transfusi pasien rawat inap
    public function transfusiPasienRanap(Request $request)
    {
        $tanggal = new Carbon('this month');
    }
}
