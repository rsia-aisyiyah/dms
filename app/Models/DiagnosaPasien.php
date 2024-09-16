<?php

namespace App\Models;

use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\Penyakit;
use App\Models\RegPeriksa;
use App\Models\Kecamatan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Staudenmeir\EloquentHasManyDeep\HasOneDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class DiagnosaPasien extends Model
{
	use HasFactory, HasRelationships;

	protected $table = "diagnosa_pasien";

	public function penyakit()
	{
		return $this->belongsTo(Penyakit::class, 'kd_penyakit', 'kd_penyakit');
	}

	public function regPeriksa()
	{
		return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
	}

	public function scopePrioritas($query)
	{
		return $query->where('prioritas', 1);
	}

	function pasien(): HasOneThrough
	{
		return $this->hasOneThrough(Pasien::class, RegPeriksa::class, 'no_rawat', 'no_rkm_medis', 'no_rawat', 'no_rkm_medis')
			->select('pasien.no_rkm_medis', 'nm_pasien', 'kd_kel', 'kd_kec', 'kd_kab', 'tgl_lahir');
	}

	function scopeTuberkulosis($query)
	{
		return $query->where('kd_penyakit', 'like', 'A15%')
			->orWhere('kd_penyakit', 'like', 'A16%');
	}

	function kecamatan()
	{
		return $this->hasOneDeep(Kecamatan::class, [RegPeriksa::class, Pasien::class],
			['no_rawat', 'no_rkm_medis', 'kd_kec'],
			['no_rawat', 'no_rkm_medis', 'kd_kec']);
	}
	function kelurahan()
	{
		return $this->hasOneDeep(Kelurahan::class, [RegPeriksa::class, Pasien::class],
			['no_rawat', 'no_rkm_medis', 'kd_kel'],
			['no_rawat', 'no_rkm_medis', 'kd_kel']);
	}


}
