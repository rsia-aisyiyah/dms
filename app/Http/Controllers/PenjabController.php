<?php

namespace App\Http\Controllers;

use App\Models\Penjab;
use Illuminate\Http\Request;

class PenjabController extends Controller
{
    public function getAllPenjab(Request $request)
    {
        if($request->ajax()){
            return $penjab = Penjab::where('status', '1')->orderBy('png_jawab', 'ASC')->get();
        }else{
            return redirect()->intended('/');
        }
    }
}
