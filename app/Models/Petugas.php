<?php

namespace App\Models;

use App\Models\Operasi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Petugas extends Model
{
    use HasFactory;
    protected $table = "petugas";

    public function operasi()
    {
        return $this->hasMany(Operasi::class, 'omloop', 'nip');
    }

    public function dokter()
    {
        return $this->hasOne(Dokter::class, 'kd_dokter', 'nip');
    }
}
