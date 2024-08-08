<?php

namespace App\View\Components\Beranda;

use App\Http\Controllers\Collection\DokterCollection;
use App\Http\Controllers\Collection\KunjunganPoliklinikDokterCollection;
use App\Http\Controllers\Collection\RegPeriksaCollection;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\RegPeriksaController;
use http\Env\Request;
use Illuminate\View\Component;

class CardGrafikDokter extends Component
{
	protected $dokterCollection;
	protected $regPeriksaCollection;

	/**
	 * Create a new component instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->dokterCollection = new DokterCollection();
		$this->regPeriksaCollection = new RegPeriksaCollection();
	}

	/**
	 * Get the view / contents that represent the component.
	 *
	 * @return \Illuminate\Contracts\View\View|\Closure|string
	 */
	public function render()
	{
		$dokter = $this->dokterCollection->getDokterSpesialis();
		$dataGrafikCollection = new KunjunganPoliklinikDokterCollection();
		$dataGrafik = $dataGrafikCollection->get(new \Illuminate\Http\Request());
		$jsonDokter = json_decode($dokter, true);
		return view('components.beranda.card-grafik-dokter', ['dokter' => $jsonDokter, 'dataGrafik' => $dataGrafik]);
	}
}
