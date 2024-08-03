<?php

namespace App\Http\Controllers\Collection;

use App\Http\Controllers\Controller;
use App\Http\Controllers\RegPeriksaController;
use App\Models\RegPeriksa;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class RegPeriksaCollection extends Controller
{

	protected $regPeriksaController;

	public function __construct()
	{
		$this->regPeriksaController = new RegPeriksaController();
	}

	function getAll(Request $request)
	{
		return collect($this->regPeriksaController->getAll($request))
			->where('stts', 'Sudah');
	}

	function getRegByStatusLanjut()
	{
		$regCollection = $this->getAll(new Request());
		$kunjungan = $regCollection->groupBy('status_lanjut')->mapWithKeys(function ($item, $key) {
			return [$key => $item->count()];
		})->toArray();
		$totalCount = array_sum($kunjungan);
		$igd= $regCollection->where('kd_poli', 'IGDK')->count();
		return array_merge($kunjungan, ['Total' => $totalCount, 'UGD' => $igd]);
	}

}
