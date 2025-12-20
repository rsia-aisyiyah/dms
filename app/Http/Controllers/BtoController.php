<?php

namespace App\Http\Controllers;

use App\Services\RsiaBtoService;

class BtoController extends Controller
{
	public function index(RsiaBtoService $service, string $specialist, int $year = null)
	{
		return $service->get($specialist, $year);
	}
}
