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
		return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat')
			->select(['no_rawat', 'umurdaftar', 'sttsumur', 'status_lanjut']);
	}

	public function diagnosa(): HasOneThrough
	{
		return $this->hasOneThrough(DiagnosaPasien::class, RegPeriksa::class, 'no_rawat', 'no_rawat', 'no_rawat', 'no_rawat');
	}

	public function pasien(): HasOneThrough
	{
		return $this->hasOneThrough(Pasien::class, RegPeriksa::class, 'no_rawat', 'no_rkm_medis', 'no_rawat', 'no_rkm_medis')
			->select('pasien.no_rkm_medis', 'nm_pasien', 'kd_kel', 'kd_kec', 'kd_kab', 'tgl_lahir');
	}

	public function pegawai(): BelongsTo
	{
		return $this->belongsTo(Pegawai::class, 'nip', 'nik')->select('nik', 'nama');
	}

	public function poliklinik(): HasOneThrough
	{
		return $this->hasOneThrough(Poliklinik::class, RegPeriksa::class, 'no_rawat', 'kd_poli', 'no_rawat', 'kd_poli');
	}

	public function scopeYear($query, $year = null): void
	{
		$year = $year ?? date('Y');
		$query->whereYear('tanggal', $year);
	}

	public function scopeMonth($query, $month = null): void
	{
		$month = $month ?? date('m');
		$query->whereMonth('tanggal', $month);
	}

	public function getCountByYear($year = null)
	{
		if ($year == null) {
			$year = date('Y');
		}
		return $this->selectRaw('count(*) as count, month(tanggal) as month, tanggal')
			->whereYear('tanggal', $year)
			->groupByRaw('month(tanggal)')
			->get();
	}

	public function getCountByPoliklinik()
	{
		return $this->whereHas('regPeriksa', function ($query) {
				return $query->selectRaw('count(*) as count');
			})->with(['regPeriksa', 'poliklinik']);

	}

}
