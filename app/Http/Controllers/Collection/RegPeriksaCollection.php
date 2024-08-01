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
		return collect($this->regPeriksaController->getAll($request)
			->where('stts', 'Sudah'));
	}
}
