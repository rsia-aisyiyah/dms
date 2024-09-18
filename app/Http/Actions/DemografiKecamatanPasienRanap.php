<?php

namespace App\Http\Actions;

use App\Models\RegPeriksa;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use function PHPUnit\Framework\isNull;

class DemografiKecamatanPasienRanap
{

	public function __invoke(RegPeriksa $regPeriksa, $year = '', $month = '', $limit=10) : JsonResponse{

		$query = $this->grouping($regPeriksa, $year, $month);
		$mapping = $query->map(function ($item) {
			return $item->kecamatan;
		})->groupBy('nm_kec')->map(function ($item) {
			return $item->count();
		})->sortDesc();

		if($limit > 0){
			return response()->json($mapping->take($limit)->sortDesc());
		}
		return response()->json($mapping->sortDesc());
	}

	protected function grouping(RegPeriksa $regPeriksa, $year, $month) : Collection
	{
		$query = $regPeriksa
			->where('status_lanjut', 'ranap')
			->where('stts', 'Sudah')
			->with(['kelurahan'])->month($month, $year);
		return collect($query->get());
	}
}