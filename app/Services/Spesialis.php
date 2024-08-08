<?php

namespace App\Services;

use Illuminate\Support\Collection;

interface Spesialis
{
    function all(): Collection;
    function getSpesialisDokter(): Collection;
}
