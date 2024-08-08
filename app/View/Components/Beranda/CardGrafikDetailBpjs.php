<?php

namespace App\View\Components\Beranda;

use App\Http\Controllers\Collection\PembiayaanPasienCollection;
use Illuminate\View\Component;

class CardGrafikDetailBpjs extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    protected $pembiayaan;
    public function __construct()
    {
        $this->pembiayaan = new PembiayaanPasienCollection();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $pembiayaan = $this->pembiayaan->getPenjabBpjs();
        return view('components.beranda.card-grafik-detail-bpjs', ['data' => $pembiayaan]);
    }
}
