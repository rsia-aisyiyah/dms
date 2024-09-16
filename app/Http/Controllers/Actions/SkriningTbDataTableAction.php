<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use App\Services\RsiaSkriningTbService;

class SkriningTbDataTableAction extends Controller
{

    public function __invoke(RsiaSkriningTbService $skrining, $year = null, $month = null)
    {
        return $skrining->getDataTable($year, $month);

    }
}
