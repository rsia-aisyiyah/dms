<?php

namespace App\Http\Controllers\Collection;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DokterController;
use Illuminate\Http\Request;

class DokterCollection extends Controller
{
	protected $dokterController;
	public function __construct()
	{
		$this->dokterController = new DokterController();
	}

	function getDokter()
	{
		$dokterCollection = collect($this->dokterController->getDokterSpesialis());
		$dokter = $dokterCollection->map(function ($item) {
			return [
				'kd_dokter' => $item->kd_dokter,
				'nm_dokter' => $item->nm_dokter,
			];
		});

		return response()->json($dokter);
	}

	function getDokterById($kd_dokter)
	{

		$dokter = $this->dokterController->getDokterById($kd_dokter);
		return collect($dokter);
	}
}
