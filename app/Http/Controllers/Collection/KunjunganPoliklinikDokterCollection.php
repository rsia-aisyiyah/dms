<?php

namespace App\Http\Controllers\Collection;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class KunjunganPoliklinikDokterCollection extends Controller
{

	protected $dokterCollection;
	protected $regPeriksaCollection;
	protected $jadwalCollection;
	public function __construct()
	{
		$this->dokterCollection = new DokterCollection();
		$this->regPeriksaCollection = new RegPeriksaCollection();
		$this->jadwalCollection = new JadwalCollection();
	}

	// Function to get dokter collection
	private function getDokterCollection()
	{
		$jsonDokter = $this->dokterCollection->getDokter()->getContent();
		return collect(json_decode($jsonDokter, true));
	}

// Function to get dokter spesialis codes
	private function getDokterSpesialisCodes($dokterCollection)
	{
		return $dokterCollection->pluck('kd_dokter');
	}

// Function to get grouped registration data
	private function getGroupedRegistrationData($dokterSpesialis, $request)
	{
		return $this->regPeriksaCollection->getAll($request)
			->whereIn('kd_dokter', $dokterSpesialis)
			->where('stts', '!=', 'Batal')
			->groupBy('kd_dokter');
	}

// Function to map data into the required format
	private function mapRegistrationData($regPeriksa, $dokterCollection)
	{
		return $regPeriksa->map(function ($items) use ($dokterCollection) {
			$dokter = $dokterCollection->firstWhere('kd_dokter', $items->first()->kd_dokter);

			$jumlah = $items->groupBy('tgl_registrasi')->map(function($item) {
				return $item->count();
			})->values();
			$tanggal = $items->groupBy('tgl_registrasi')->keys()->values()->map(function ($item){
				return Carbon::parse($item)->translatedFormat('d F');
			});

			return [
				'tanggal' => $tanggal,
				'jumlah' => $jumlah,
				'dokter' => $dokter,
			];
		})->values(); // Ensure the outer array is numerically indexed
	}

// Main function to handle the request
	public function getDokterData(Request $request)
	{
		$dokterCollection = $this->getDokterCollection();
		$dokterSpesialis = $this->getDokterSpesialisCodes($dokterCollection);
		$regPeriksa = $this->getGroupedRegistrationData($dokterSpesialis, $request);
		$data = $this->mapRegistrationData($regPeriksa, $dokterCollection);
		return response()->json($data);
	}

}
