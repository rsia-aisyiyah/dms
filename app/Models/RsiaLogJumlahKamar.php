<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RsiaLogJumlahKamar extends Model
{

    use HasFactory, Compoships;

    protected $table = 'rsia_log_jumlah_kamar';
    protected $guarded = [];

    public function scopeJumlahKamar($spesialist, $year, $month)
    {
        return $this->where('tahun', $year)
            ->where('bulan', $month)
            ->where('kategori', $spesialist);

    }
}
