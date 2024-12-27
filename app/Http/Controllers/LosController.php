<?php

namespace App\Http\Controllers;

use App\Services\RsiaLosService;
use Illuminate\Http\Request;

class LosController extends Controller
{
    public function index(RsiaLosService $los, string $specialist, int $year = null)
    {
        return $los->getLos($specialist, $year);
    }
}

