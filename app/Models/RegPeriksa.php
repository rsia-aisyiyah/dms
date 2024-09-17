<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class RegPeriksa extends Model
{
	use HasFactory;

	protected $table = "reg_periksa";

	public function poliklinik()
	{
		return $this->belongsTo(Poliklinik::class, 'kd_poli', 'kd_poli');
	}

	// data_triase_igd
	public function dataTriaseIgd()
	{
		return $this->hasOne(DataTriaseIgd::class, 'no_rawat', 'no_rawat');
	}

	public function bridgingSep()
	{
		return $this->hasOne(BridgingSep::class, 'no_rawat', 'no_rawat');
	}

	public function diagnosaPasien()
	{
		return $this->belongsTo(DiagnosaPasien::class, 'no_rawat', 'no_rawat');
	}

	public function diagnosa()
	{
		return $this->hasMany(DiagnosaPasien::class, 'no_rawat', 'no_rawat');
//			->where('kd_penyakit', 'not like', 'Z%');
	}

	public function diagnosaWithoutZ() : HasMany{
		return $this->hasMany(DiagnosaPasien::class, 'no_rawat', 'no_rawat')
			->where('kd_penyakit', 'not like', 'Z%');
	}

	public function diagnosaTb(): BelongsTo
	{
		return $this->belongsTo(DiagnosaPasien::class, 'no_rawat', 'no_rawat')
			->where('kd_penyakit', 'like', 'A15%')
			->orWhere('kd_penyakit', 'like', 'A16%');
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
		return $this->hasOne(ResepObat::class, 'no_rawat', 'no_rawat');
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

	public function scopeMonth($query, $month, $year)
	{
		if ($month == '' && $year == '') {
			$month = date('m');
			$year = date('Y');
		}
		$query->whereMonth('tgl_registrasi', $month)
			->whereYear('tgl_registrasi', $year);
	}

	protected function scopeYear($query, $year)
	{
		if ($year == '') {
			$year = date('Y');
		}
		$query->whereYear('tgl_registrasi', $year);
	}

	function scopeStatus($query, $status = 'Sudah')
	{
		$query->where('stts', $status);
	}

	// rsia_general_consent
	public function rsiaGeneralConsent()
	{
		return $this->hasOne(RsiaGeneralConsent::class, 'no_rawat', 'no_rawat');
	}

	// RsiaVerifPemeriksaanRanap
	public function rsiaVerifPemeriksaanRanap()
	{
		return $this->hasOne(RsiaVerifPemeriksaanRanap::class, 'no_rawat', 'no_rawat');
	}

	public function resumePasienRanap()
	{
		return $this->hasOne(ResumePasienRanap::class, 'no_rawat', 'no_rawat');
	}

	// penilaian medis ranap
	public function penilaianMedisRanap()
	{
		return $this->hasOne(PenilaianMedisRanap::class, 'no_rawat', 'no_rawat');
	}

	// penilaian medis ranap kandungan
	public function penilaianMedisRanapKandungan()
	{
		return $this->hasOne(PenilaianMedisRanapKandungan::class, 'no_rawat', 'no_rawat');
	}

	public function ranapDokter()
	{
		return $this->hasOne(RawatInapDr::class, 'no_rawat', 'no_rawat');
	}

	public function ranapGabungan()
	{
		return $this->hasOne(RawatInapDrPr::class, 'no_rawat', 'no_rawat');
	}

	public function ralanDokter()
	{
		return $this->hasOne(RawatJalanDr::class, 'no_rawat', 'no_rawat');
	}

	public function ralanGabungan()
	{
		return $this->hasOne(RawatJalanDrPr::class, 'no_rawat', 'no_rawat');
	}

	public function transferPasienAntarRuang()
	{
		return $this->hasOne(TransferPasienAntarRuang::class, 'no_rawat', 'no_rawat');
	}

	public function pemeriksaanRanap()
	{
		return $this->hasMany(PemeriksaanRanap::class, 'no_rawat', 'no_rawat');
	}

	// no_rawat
	public function grafikHarian()
	{
		return $this->hasMany(RsiaGrafikHarian::class, 'no_rawat', 'no_rawat');
	}

	// rekonsiliasiObat
	public function rekonsiliasiObat()
	{
		return $this->hasOne(RekonsiliasiObat::class, 'no_rawat', 'no_rawat');
	}

	// skriningGizi
	public function skriningGizi()
	{
		return $this->hasOne(RsiaSkriningGizi::class, 'no_rawat', 'no_rawat');
	}

	// penilaianAwalKeperawatanIgd
	public function penilaianAwalKeperawatanIgd()
	{
		return $this->hasOne(PenilaianAwalKeperawatanIgd::class, 'no_rawat', 'no_rawat');
	}

	// PenilaianAwalKeperawatanKebidanan
	public function penilaianAwalKeperawatanKebidanan()
	{
		return $this->hasOne(PenilaianAwalKeperawatanKebidanan::class, 'no_rawat', 'no_rawat');
	}

	// penilaian awal keperawatan ranap
	public function penilaianAwalKeperawatanRanap()
	{
		return $this->hasOne(PenilaianAwalKeperawatanRanap::class, 'no_rawat', 'no_rawat');
	}

	// penilaian awal keperawatan ranap anak
	public function penilaianAwalKeperawatanRanapAnak()
	{
		return $this->hasOne(PenilaianAwalKeperawatanRanapAnak::class, 'no_rawat', 'no_rawat');
	}

	// penilaian awal keperawatan ranap neonatus
	public function penilaianAwalKeperawatanRanapNeonatus()
	{
		return $this->hasOne(PenilaianAwalKeperawatanRanapNeonatus::class, 'no_rawat', 'no_rawat');
	}

	// PenilaianMedisIgd
	public function penilaianMedisIgd()
	{
		return $this->hasOne(PenilaianMedisIgd::class, 'no_rawat', 'no_rawat');
	}

	public function pemeriksaanRalan()
	{
		return $this->hasOne(PemeriksaanRalan::class, 'no_rawat', 'no_rawat');
	}

	public function kecamatan(): HasOneThrough
	{
		return $this->hasOneThrough(Kecamatan::class, Pasien::class, 'no_rkm_medis', 'kd_kec', 'no_rkm_medis', 'kd_kec');
	}
	public function kelurahan(): HasOneThrough
	{
		return $this->hasOneThrough(Kelurahan::class, Pasien::class, 'no_rkm_medis', 'kd_kel', 'no_rkm_medis', 'kd_kel');
	}

	public function kabupaten(): HasOneThrough
	{
		return $this->hasOneThrough(Kabupaten::class, Pasien::class, 'no_rkm_medis', 'kd_kab', 'no_rkm_medis', 'kd_kab');
	}
	// public function propinsi(): HasOneThrough
	// {
	//     return $this->hasOneThrough(P::class, Pasien::class, 'no_rkm_medis', 'kd_kab', 'no_rkm_medis', 'kd_kab');
	// }
}
