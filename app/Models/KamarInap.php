<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KamarInap extends Model
{
    use HasFactory;
    protected $table = 'kamar_inap';

    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'kd_kamar', 'kd_kamar');
    }
    public function bangsal()
    {
        return $this->hasOne(Bangsal::class, 'kd_bangsal', 'kd_bangsal');
    }
    public function regPeriksa()
    {
        return $this->hasMany(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }

    
}
