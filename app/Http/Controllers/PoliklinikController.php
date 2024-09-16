<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Poliklinik;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PoliklinikController extends Controller
{
	public function getAllPoliklinik(Request $request)
	{
		if ($request->ajax()) {
			return $poli = Poliklinik::where('status', '1')->get();
		}
	}

	public function show(Poliklinik $poli, $kd_poli) : JsonResponse
	{
		return response()->json($poli->where('kd_poli', $kd_poli)->first());
	}
}
