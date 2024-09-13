<?php

namespace App\Http\Actions;

use App\Models\RegPeriksa;
use Illuminate\Http\Request;


class CounterKunjunganByStatusLanjut
{


	public function __invoke(RegPeriksa $regPeriksa, Request $request)
	{
		// TODO: Implement __invoke() method.

		 $query = $regPeriksa->month($request->month, $request->year)
			->status()
			->with('poliklinik')
			->get();

		 $collection = collect($query);

		 $arrayStatus = $collection->groupBy('status_lanjut')->map(function ($item){
			 return $item->count();
		 })->toArray();

		 $total = array_sum($arrayStatus);
		 $igd = $collection->where('kd_poli', 'IGDK')->count();
		 $arrayStatus = array_merge($arrayStatus, ['Total' => $total, 'UGD' => $igd]);

		 return response()->json($arrayStatus);
	}
}