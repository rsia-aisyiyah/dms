<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingRegistrasi extends Model
{
    use HasFactory;
    protected $table = 'booking_registrasi';
    public function regPeriksa()
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rkm_medis', 'no_rkm_medis');
    }
    public function scopeGetBooking($query)
    {

    }
}
