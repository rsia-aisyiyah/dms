<?php

namespace App\Http\Controllers;

use App\Services\RsiaToiService;

class ToiController extends Controller
{
    public function index(RsiaToiService $toi, string $specialist, int $year = null){
        return $toi->getToi($specialist, $year);
    }
}
