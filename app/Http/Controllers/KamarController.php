<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use Illuminate\Http\Request;

class KamarController extends Controller
{
    //
    public function getTarif()
    {
        return $kamar = Kamar::where('statusdata', '1')->get();
    }
}
