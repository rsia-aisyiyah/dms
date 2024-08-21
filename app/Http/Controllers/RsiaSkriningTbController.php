<?php

namespace App\Http\Controllers;

use App\Services\RsiaSkriningTbService;

class RsiaSkriningTbController extends Controller
{
    protected RsiaSkriningTbService $servicesRsiaSkriningTb;
    public function __construct(RsiaSkriningTbService $servicesRsiaSkriningTb)
    {
        $this->servicesRsiaSkriningTb = $servicesRsiaSkriningTb;
    }
    public function get(string $year = '', string $month = '', bool $isUseDatatable = false)
    {
        return $this->servicesRsiaSkriningTb->get($year, $month, $isUseDatatable);
    }

}
