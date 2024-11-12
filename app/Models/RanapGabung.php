<?php

namespace App\Models;

use App\Models\AskepRanapBayi;
use App\Models\RegPeriksa;
use Awobaz\Compoships\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RanapGabung extends Model
{
    use HasFactory;

    protected $table = 'ranap_gabung';

    public function rp()
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat2', 'no_rawat');
    }

    public function askepBayi()
    {
        return $this->hasOne(AskepRanapBayi::class, 'no_rawat', 'no_rawat2');
    }
    public function kamarInap()
    {
        return $this->hasMany(KamarInap::class, 'no_rawat', 'no_rawat');
    }

    // public function bayiReg()
    // {
    //     return $this->hasOne(RegPeriksa::class, 'no_rawat2', 'no_rawat');
    // }
}
