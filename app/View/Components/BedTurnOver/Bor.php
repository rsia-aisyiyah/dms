<?php

namespace App\View\Components\BedTurnOver;

use Illuminate\View\Component;

class Bor extends Component
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
        return view('components.bed-turn-over.bor', [
            'spc' => $this->spc,
        ]);
    }
}
