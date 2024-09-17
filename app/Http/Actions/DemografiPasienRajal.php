<?php

namespace App\Http\Actions;

use App\Models\RegPeriksa;
use Illuminate\Http\Request;

class DemografiPasienRajal
{

	public function __invoke(RegPeriksa $regPeriksa, $year = '', $month = '')
	{

		$data = $this->groupingByKecamatan($regPeriksa, $year,$month);

		$map = $data->map(function ($item) {
			return $item->kecamatan;
		})->groupBy('nm_kec')->map(function ($item) {
			return $item->count();
		})->sortDesc();

		if(isset($month)){
			$map = $map->slice(0,10);
		}

		return response()->json($map);

	}

	protected function groupingByKecamatan(RegPeriksa $regPeriksa, $year, $month)
	{
		$query = $regPeriksa->with(['kecamatan']);
		if($month){
			$query = $query->month($month, $year);
		}else{
			$query = $query->year($year);
		}
		return collect($query->get());
	}
}