<?php

namespace App\Models;

use App\Models\Dokter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Spesialis extends Model
{
    use HasFactory;
    protected $table = 'spesialis';

    public function dokter(): HasMany
    {
        return $this->hasMany(Dokter::class, 'kd_sps', 'kd_sps');
    }
    public function scopeDokter($query)
    {
        $this->$query->with('dokter');
    }
}
