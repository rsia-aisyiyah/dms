<?php

namespace App\View\Components\Beranda;

use App\Http\Controllers\Collection\PembiayaanPasienCollection;
use Illuminate\Http\Request;
use Illuminate\View\Component;

class CardGrafikPembiayaan extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

	protected $pembiayaanCollection;
    public function __construct()
    {
        $this->pembiayaanCollection = new PembiayaanPasienCollection();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
		$pembiayaan = $this->pembiayaanCollection->getPembiayaan(new Request());
        return view('components.beranda.card-grafik-pembiayaan', ['data' => $pembiayaan]);
    }
}
