<?php

namespace App\View\Components\Beranda;

use App\Http\Controllers\Collection\RegPeriksaCollection;
use Illuminate\Http\Request;
use Illuminate\View\Component;

class CounterKunjungan extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    protected $counterKunjungan;
    public function __construct()
    {
        $this->counterKunjungan = new RegPeriksaCollection();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $data = $this->counterKunjungan->getRegByStatusLanjut(new Request());
        $dataUgd = $this->counterKunjungan->getRegPeriksaOnUgd(new Request());
        return view('components.beranda.counter-kunjungan', ['data' => $data, 'dataUgd' => $dataUgd]);
    }
}
