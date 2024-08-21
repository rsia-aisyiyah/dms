<?php

namespace App\View\Components\tb\datatable;

use Illuminate\View\Component;

class Skrining extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.tb.datatable.skrining');
    }
}
