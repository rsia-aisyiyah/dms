<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JnsPerawatanInap extends Model
{
    use HasFactory;
    protected $table = 'jns_perawatan_inap';

    public function rawatInapDr()
    {
        $this->hasMany(RawatInap::class, 'kd_jenis_prw', 'kd_jenis_prw');
    }
}
