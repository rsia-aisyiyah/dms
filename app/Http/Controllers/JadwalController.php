<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
	protected $jadwalModel;
	public function __construct()
	{
		$this->jadwalModel = new Jadwal();
	}
}
