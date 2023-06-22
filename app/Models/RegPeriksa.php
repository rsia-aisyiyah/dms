<?php

namespace App\Models;

use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\Penjab;
use App\Models\KamarInap;
use App\Models\Spesialis;
use App\Models\Poliklinik;
use App\Models\BridgingSep;
use App\Models\RanapGabung;
use App\Models\AskepRanapBayi;
use App\Models\DiagnosaPasien;
use App\Models\BookingRegistrasi;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RegPeriksa extends Model
{
    use HasFactory;
    protected $table = "reg_periksa";

    public function bridgingSep()
    {
        return $this->hasOne(BridgingSep::class, 'no_rawat', 'no_rawat');
    }
    public function diagnosaPasien()
    {
        return $this->belongsTo(DiagnosaPasien::class, 'no_rawat', 'no_rawat');
    }
    public function bookingRegistrasi()
    {
        return $this->hasOne(BookingRegistrasi::class, 'no_rkm_medis', 'no_rkm_medis');
    }

    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'kd_dokter', 'kd_dokter');
    }

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'no_rkm_medis', 'no_rkm_medis');
    }

    public function poli()
    {
        return $this->belongsTo(Poliklinik::class, 'kd_poli', 'kd_poli');
    }

    public function penjab()
    {
        return $this->belongsTo(Penjab::class, 'kd_pj', 'kd_pj');
    }

    public function spesialis()
    {
        return $this->hasOneThrough(Spesialis::class, Dokter::class, 'kd_sps', 'kd_sps', 'kd_dokter');
    }

    public function kamarInap()
    {
        return $this->belongsTo(KamarInap::class, 'no_rawat', 'no_rawat');
    }
    public function ranapGabung()
    {
        return $this->hasMany(RanapGabung::class, 'no_rawat2', 'no_rawat');
    }

    public function askepBayi()
    {
        return $this->hasOne(AskepRanapBayi::class, 'no_rawat', 'no_rawat');
    }
    public function resepObat()
    {
        return $this->hasMany(ResepObat::class, 'no_rawat', 'no_rawat');
    }
    public function pemberianObat()
    {
        return $this->hasMany(DetailPemberianObat::class, 'no_rawat', 'no_rawat');
    }
    public function scopeRalanTahunan($query, $tahun, $bulan)
    {
        $query->whereIn('kd_poli', ['P001', 'P003', 'P007', 'P008', 'P009'])
            ->where('status_lanjut', 'Ralan')
            ->whereYear('tgl_registrasi', $tahun)
            ->whereMonth('tgl_registrasi', $bulan)
            ->where('stts', '!=', 'Batal');
    }
    public function scopeGetSepRalan($query)
    {
        $query->where('stts', '!=', 'Batal')
            ->whereIn('kd_poli', ['P001', 'P003', 'P007', 'P008', 'P009'])
            ->where('status_lanjut', 'Ralan')
            ->whereHas('penjab', function ($q) {
                $q->where('png_jawab', 'like', '%BPJS%');
            });
    }
    public function scopeGetSepRanap($query)
    {
        $query->where('status_lanjut', 'Ranap')
            ->whereHas('kamarInap', function ($q) {
                $q->where('stts_pulang', '!=', 'Pindah Kamar');
            })
            ->whereHas('dokter.spesialis', function ($q) {
                $q->whereIn('kd_sps', ['S0001', 'S0003']);
            })
            ->whereHas('penjab', function ($q) {
                $q->where('png_jawab', 'like', '%BPJS%');
            })
            ->groupBy('reg_periksa.no_rawat');
    }

    public function scopeRegLangsung($query)
    {
        $query->select(DB::raw('count(*) as jumlah'), 'tgl_registrasi')
            ->whereIn('kd_poli', ['P001', 'P003', 'P005', 'P007', 'P008', 'P009'])
            ->doesntHave('bookingRegistrasi')
            ->where('status_lanjut', 'Ralan')
            ->where('stts', 'Sudah');
    }
    public function scopeRegBooking($query)
    {
        $query->select(DB::raw('count(*) as jumlah'), 'tgl_registrasi')
            ->whereIn('kd_poli', ['P001', 'P003', 'P005', 'P007', 'P008', 'P009'])
            ->whereHas('bookingRegistrasi')
            ->where('status_lanjut', 'Ralan')
            ->where('stts', 'Sudah');
    }
}
