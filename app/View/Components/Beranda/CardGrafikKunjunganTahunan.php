<?php

namespace App\View\Components\Beranda;

use App\Http\Controllers\Collection\RegPeriksaCollection;
use Illuminate\View\Component;

class CardGrafikKunjunganTahunan extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    protected $regPeriksaCollection;
    public function __construct(RegPeriksaCollection $regPeriksaCollection)
    {
        $this->regPeriksaCollection = $regPeriksaCollection;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $data = $this->regPeriksaCollection->getByYear();
        return view('components.beranda.card-grafik-kunjungan-tahunan');
    }
}
