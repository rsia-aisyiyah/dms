<?php

namespace App\Models;

use App\Models\RegPeriksa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BridgingSep extends Model
{
    use HasFactory;
    protected $table = "bridging_sep";

    public function regPeriksa()
    {
        return $this->hasOne(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }
}
