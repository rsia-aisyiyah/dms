<?php

namespace App\Http\Controllers;

use App\Models\Penjab;
use Illuminate\Http\Request;

class PenjabController extends Controller
{
    public function getAllPenjab()
    {
        return $penjab = Penjab::active()
	        ->orderBy('png_jawab', 'ASC')->get();
    }
}
