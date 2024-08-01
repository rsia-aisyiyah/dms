<?php

namespace App\Http\Controllers\Collection;

use App\Http\Controllers\Controller;
use App\Http\Controllers\RegPeriksaController;
use App\Models\RegPeriksa;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class RegPeriksaCollection extends Controller
{

	protected $regPeriksaController;
	public function __construct()
	{
		$this->regPeriksaController = new RegPeriksaController();
	}

	function getAll(Request $request) : Collection
	{
		 return collect($this->regPeriksaController->getAll($request));
	}
}
