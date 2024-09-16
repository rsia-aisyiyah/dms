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

	function getDokterSpesialis()
	{
		return collect($this->dokterController->getDokterSpesialis());
	}

	function getDokterById($kd_dokter)
	{

		$dokter = $this->dokterController->getDokterById($kd_dokter);
		return collect($dokter);
	}
}
