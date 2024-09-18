<?php

namespace App\Http\Actions;

use App\Models\RegPeriksa;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DemografiKecamatanPasienRajal
{

	public function __invoke(RegPeriksa $regPeriksa, $year = '', $month = '')
	{

		$data = $this->groupingByKecamatan($regPeriksa, $year,$month);

		$map = $data->map(function ($item) {
			return $item->kecamatan;
		})->groupBy('nm_kec')->map(function ($item) {
			return $item->count();
		})->sortDesc();

		$limited = $map->take(20);
		$othersSum = $map->slice(20)->sum();

		if ($othersSum > 0) {
			$limited = $limited->put('LAINYA', $othersSum);
		}
		return response()->json($limited->sortDesc());

	}

	protected function groupingByKecamatan(RegPeriksa $regPeriksa, $year, $month) : Collection
	{
		$query = $regPeriksa
			->where('status_lanjut', 'Ralan')
			->where('stts', 'Sudah')
			->with(['kecamatan'])->month($month, $year);

		return collect($query->get());
	}
}