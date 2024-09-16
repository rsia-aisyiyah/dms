<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class LaboratController extends Controller
{

    private $sekarang;
    public function __construct()
    {
        // parent::__construct();
        //Do your magic here
        $tanggal = new Carbon('this month');
    }


    //transfusi pasien rawat inap
    public function transfusiPasienRanap(Request $request)
    {
        $tanggal = new Carbon('this month');
    }

    public function getTafir(Request $request)
    {
    }
}
