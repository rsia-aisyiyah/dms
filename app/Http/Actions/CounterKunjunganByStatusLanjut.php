<?php

namespace App\Http\Actions;

use App\Http\Controllers\Collection\RegPeriksaCollection;
use App\Models\RegPeriksa;
use Illuminate\Http\Request;


class CounterKunjunganByStatusLanjut
{


	public function __invoke(RegPeriksaCollection $regPeriksa, Request $request)
	{
		$data = $regPeriksa->getRegByStatusLanjut($request);
		$dataUgd = $regPeriksa->getRegPeriksaOnUgd($request);

		return response()->json([
			'data' => $data,
			'dataUgd' => $dataUgd,
		]);
	}
}