<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
	use HasFactory;

	protected $table = "dokter";

	public function operasi()
	{
		return $this->hasMany(Operasi::class);
	}

	public function spesialis()
	{
		return $this->belongsTo(Spesialis::class, 'kd_sps', 'kd_sps');
	}

	public function rawatInapDr()
	{
		return $this->hasMany(RawatInapDr::class, 'kd_dokter', 'kd_dokter');
	}

	public function pegawai()
	{
		return $this->hasOne(Pegawai::class, 'nik', 'kd_dokter');
	}

	public function jadwal()
	{
		return $this->hasMany(Jadwal::class, 'kd_dokter', 'kd_dokter');
	}
}
