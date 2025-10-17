<?php

namespace App\Http\Controllers;

use App\Models\Spesialis;
use App\Services\Implement\SpesialisImplement;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class SpesialisController extends Controller
{
	protected $spesialis;

	public function __construct(SpesialisImplement $spesialis)
	{
		$this->spesialis = $spesialis;
	}

	public function all(): Collection
	{
		return $this->spesialis->all();
	}


	public function getSpesialisDokter(): Collection
	{
		return $this->spesialis->getSpesialisDokter();
	}

	public function getSpesialisPoli(): JsonResponse
	{
		$data = Spesialis::whereHas('dokter.jadwal')->get();

		return response()->json($data);
	}
}
