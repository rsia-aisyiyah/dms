<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
	use HasFactory;

	protected $table = "pegawai";

	public function pegawai()
	{
		return $this->hasMany(Operasi::class);
	}

}
