<?php

namespace App\Http\Controllers;

use App\Services\RsiaBorService;

class BorController extends Controller
{
    public function index(RsiaBorService $bor, string $specialist, int $year = null)
    {
        return $bor->get($specialist, $year);
    }
}
