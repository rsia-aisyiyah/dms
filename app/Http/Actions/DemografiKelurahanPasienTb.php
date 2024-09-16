<?php

namespace App\Http\Actions;

use App\Models\DiagnosaPasien;
use Illuminate\Http\JsonResponse;

class DemografiKelurahanPasienTb
{
	public function __invoke($year='', $month='') : JsonResponse
	{
		$dataCollection = collect($this->groupingByKelurahan(new DiagnosaPasien, $year, $month));
		$collection =  $dataCollection->map(function ($item) {
			return $item->kelurahan;
		})->groupBy('kd_kel')->mapWithKeys(function ($group) {
			$nm_kec = $group->first()->nm_kel; // Assuming `nm_kec` is the name of the kecamatan
			return [$nm_kec => $group->count()];
		})->sortDesc();

		return response()->json($collection);
	}

	protected function groupingByKelurahan(DiagnosaPasien $diagnosaPasien, $year, $month){
		return $diagnosaPasien->whereHas('regPeriksa', function ($query) use ($year, $month) {
			$query->month($month, $year);
		})->tuberkulosis()->with(['pasien','kelurahan', 'regPeriksa' => function ($query) {
			$query->select(['no_rawat', 'no_reg', 'tgl_registrasi', 'umurdaftar', 'sttsumur', 'status_lanjut']);
		}])->get();
	}
}