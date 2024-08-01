<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
	protected $jadwal;
	public function __construct(Jadwal $jadwal){
		$this->jadwal = $jadwal;
	}
    function getAll()
    {
		$data = $this->jadwal
			->with('dokter', 'poliklinik')
			->get();
		return response()->json($data);
    }

}
