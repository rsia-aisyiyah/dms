<?php

namespace App\View\Components\IndikatorRanap;

use Illuminate\View\Component;

class Los extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    protected $spc;
    public function __construct($spc)
    {
        $this->spc = $spc;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.indikator-ranap.los', [
            'spc' => $this->spc,
        ]);
    }
}
