<?php

namespace App\Services\Implement;

use App\Models\Spesialis as SpesialisModel;
use App\Services\Spesialis;
use Illuminate\Support\Collection;

class SpesialisImplement implements Spesialis
{
	protected $spesialisModel;

	public function __construct(SpesialisModel $spesialisModel)
	{
		$this->spesialisModel = $spesialisModel;
	}

	public function all(): Collection
	{
		return collect($this->spesialisModel->get());
	}

	public function getSpesialisDokter(): Collection
	{
		return $spesialis = $this->spesialisModel->with('dokter')
			->whereHas('dokter', function ($q) {
				return $q->whereHas('jadwal');
			})
			->get();
	}
}
