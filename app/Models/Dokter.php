<?php

namespace App\Models;

use App\Models\Operasi;
use App\Models\Spesialis;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dokter extends Model
{
    use HasFactory;
    protected $table = "dokter";

    public function operasi()
    {
        return $this->hasMany(Operasi::class);
    }

    public function spesialis()
    {
        return $this->belongsTo(Spesialis::class, 'kd_sps', 'kd_sps');
    }

    public function rawatInapDr()
    {
        return $this->hasMany(RawatInapDr::class, 'kd_dokter', 'kd_dokter');
    }

    public function pegawai()
    {
        return $this->hasOne(Pegawai::class, 'nik', 'kd_dokter');
    }
}
