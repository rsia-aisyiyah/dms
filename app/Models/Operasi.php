<?php

namespace App\Models;

use App\Models\Dokter;
use App\Models\Penjab;
use App\Models\Pegawai;
use App\Models\Petugas;
use App\Models\KamarInap;
use App\Models\RegPeriksa;
use App\Models\BridgingSep;
use App\Models\RanapGabung;
use App\Models\PaketOperasi;
use App\Models\LaporanOperasi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Operasi extends Model
{
    use HasFactory;
    protected $table = "operasi";


    public function pembiayaan()
    {
        return $this->hasOneThrough(
            Penjab::class,
            RegPeriksa::class,
            'no_rawat',
            'kd_pj',
            'no_rawat',
            'kd_pj',
        );
    }
    public function bridgingSep()
    {
        return $this->hasOne(BridgingSep::class, 'no_rawat', 'no_rawat');
    }
    public function paketOperasi()
    {
        return $this->belongsTo(PaketOperasi::class, 'kode_paket', 'kode_paket');
    }
    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'operator1', 'kd_dokter');
    }
    public function asisten1()
    {
        return $this->belongsTo(Pegawai::class, 'asisten_operator1', 'nik');
    }
    public function asisten2()
    {
        return $this->belongsTo(Pegawai::class, 'asisten_operator2', 'nik');
    }
    public function omloops()
    {
        return $this->belongsTo(Petugas::class, 'omloop', 'nip');
    }
    public function dokterAnestesi()
    {
        return $this->belongsTo(Dokter::class, 'dokter_anestesi', 'kd_dokter');
    }
    public function asistenAnestesi()
    {
        return $this->belongsTo(Pegawai::class, 'asisten_anestesi', 'nik');
    }
    public function dokterAnak()
    {
        return $this->belongsTo(Dokter::class, 'dokter_pjanak', 'kd_dokter');
    }
    public function kamarInap()
    {
        return $this->belongsTo(KamarInap::class, 'no_rawat', 'no_rawat');
    }
    public function regPeriksa()
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }
    public function laporanOperasi()
    {
        return $this->hasOne(LaporanOperasi::class, 'no_rawat', 'no_rawat');
    }
    public function ranapGabung()
    {
        return $this->hasOne(RanapGabung::class, 'no_rawat', 'no_rawat');
    }
    public function askepRanapKebidanan()
    {
        return $this->hasOne(AskepRanapKebidanan::class, 'no_rawat', 'no_rawat');
    }
}
