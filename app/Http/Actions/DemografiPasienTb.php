<?php

namespace App\Http\Actions;

use App\Models\DiagnosaPasien;
use Illuminate\Http\JsonResponse;

class DemografiPasienTb
{
	public function __invoke($year='', $month='') : JsonResponse
	{
		$dataCollection = collect($this->groupingByKecamatan(new DiagnosaPasien, $year, $month));
		$collection =  $dataCollection->map(function ($item) {
			return $item->kecamatan;
		})->groupBy('kd_kec')->mapWithKeys(function ($group) {
			$nm_kec = $group->first()->nm_kec; // Assuming `nm_kec` is the name of the kecamatan
			return [$nm_kec => $group->count()];
		})->sortDesc();

		return response()->json($collection);
	}

	protected function groupingByKecamatan(DiagnosaPasien $diagnosaPasien, $year, $month){
		return $diagnosaPasien->whereHas('regPeriksa', function ($query) use ($year, $month) {
			$query->month($month, $year);
		})->tuberkulosis()->with(['pasien','kecamatan', 'regPeriksa' => function ($query) {
			$query->select(['no_rawat', 'no_reg', 'tgl_registrasi', 'umurdaftar', 'sttsumur', 'status_lanjut']);
		}])->get();
	}
}