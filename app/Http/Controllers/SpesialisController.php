<?php

namespace App\Http\Controllers;

use App\Services\Implement\SpesialisImplement;
use Illuminate\Support\Collection;

class SpesialisController extends Controller
{
    protected $spesialis;
    public function __construct(SpesialisImplement $spesialis)
    {
        $this->spesialis = $spesialis;
    }
    public function all(): Collection
    {
        return $this->spesialis->all();
    }

    public function getSpesialisDokter(): Collection
    {
        return $this->spesialis->getSpesialisDokter();
    }
}
