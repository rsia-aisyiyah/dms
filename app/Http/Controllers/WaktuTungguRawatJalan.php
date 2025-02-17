<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WaktuTungguRawatJalan extends Controller
{
    function index(){
        return view('content.ralan.table_waktu_tunggu');        
    }
}
