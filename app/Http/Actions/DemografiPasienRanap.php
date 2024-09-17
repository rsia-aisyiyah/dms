<?php

namespace App\Http\Actions;

use App\Models\RegPeriksa;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class DemografiPasienRanap
{

	public function __invoke(RegPeriksa $regPeriksa, $year = '', $month = '') : JsonResponse
	{

		$data = $this->groupingByKelurahan($regPeriksa, $year, $month);

		$map = $data->map(function ($item) {
			return $item->kelurahan;
		})->groupBy('nm_kel')->map(function ($item) {
			return $item->count();
		})->sortDesc();

		$limited = $map->take(20);
		$othersSum = $map->slice(20)->sum();

		if ($othersSum > 0) {
			$limited = $limited->put('LAINYA', $othersSum);
		}

		return response()->json($limited->sortDesc());

	}

	protected function groupingByKelurahan(RegPeriksa $regPeriksa, $year, $month) : Collection{

		$query = $regPeriksa->with(['kelurahan'])->month($month, $year);
		return collect($query->get());
	}
}