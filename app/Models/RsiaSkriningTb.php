<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class RsiaSkriningTb extends Model
{
    use HasFactory;

    protected $table = 'rsia_skrining_tb';
    protected $guarded = [];
    public $timestamps = false;

    public function regPeriksa(): BelongsTo
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }
    public function diagnosa(): HasOneThrough
    {
        return $this->hasOneThrough(DiagnosaPasien::class, RegPeriksa::class, 'no_rawat', 'no_rawat', 'no_rawat', 'no_rawat');
    }
    public function pasien(): HasOneThrough
    {
        return $this->hasOneThrough(Pasien::class, RegPeriksa::class, 'no_rawat', 'no_rkm_medis', 'no_rawat', 'no_rkm_medis');
    }
    public function getByYear($year = null, $month = null)
    {
        if ($year == null) {
            $year = date('Y');
        }
        return $this->selectRaw('count(*) as count, month(tanggal) as month, tanggal')
            ->whereYear('tanggal', $year)
            ->groupByRaw('month(tanggal)')
            ->get();
    }

}
