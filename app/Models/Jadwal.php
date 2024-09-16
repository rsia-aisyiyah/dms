<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Jadwal extends Model
{
	use HasFactory, Compoships;

	protected $table = 'jadwal';

	function dokter(): BelongsTo
	{
		return $this->belongsTo(Dokter::class, 'kd_dokter', 'kd_dokter')
			->select('kd_dokter', 'nm_dokter');
	}

	function poliklinik(): BelongsTo
	{
		return $this->belongsTo(Poliklinik::class, 'kd_poli', 'kd_poli')
			->select('kd_poli', 'nm_poli');
	}

	public function scopeWithPoliDokter($query)
	{
		return $query->with(['poliklinik', 'dokter']);
	}

	public function scopeGetAll($query)
	{
		return $query->get();
	}

	public function scopeGetFirst($query)
	{
		return $query->first();
	}
}
