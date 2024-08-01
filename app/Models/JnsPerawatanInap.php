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
    public function kategoriPerawatan()
    {
        return $this->belongsTo(KategoriPerawatan::class, 'kd_kategori', 'kd_kategori');
    }
    public function bangsal()
    {
        return $this->belongsTo(Bangsal::class, 'kd_bangsal', 'kd_bangsal');
    }
    public function penjab()
    {
        return $this->belongsTo(Penjab::class, 'kd_pj', 'kd_pj');
    }
}
