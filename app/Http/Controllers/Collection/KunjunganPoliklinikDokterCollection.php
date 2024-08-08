<?php

namespace App\Http\Controllers\Collection;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;

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
	private function getJadwalDokterCollection()
	{
		return $this->getJadwalDokter()->map(function ($item) {
			return $item->map(function ($items) {
				return $items->dokter;
			})->first();
		})->values();
	}

	// Function to get dokter spesialis codes
	private function getDokterSpesialisCodes($dokterCollection)
	{
		return $dokterCollection->pluck('kd_dokter');
	}

	private function getJadwalDokter()
	{
		return $this->jadwalCollection->getAll()
			->groupBy('kd_dokter');
	}

	private function getGroupedRegistrationData($dokterSpesialis, Request $request)
	{
		$dokter = [];
		foreach ($dokterSpesialis as $kd_dokter) {
			$registrations = $this->regPeriksaCollection->getAll($request)
				->where('kd_dokter', $kd_dokter)
				->where('status_lanjut', 'Ralan');

			$dokter[$kd_dokter] = $registrations;
		}

		return collect($dokter);
	}

	private function mapRegistrationData($regPeriksa, $dokterCollection)
	{
		return $regPeriksa->map(function ($items, $dokterCode) use ($dokterCollection) {
			$jumlah = $items->groupBy('tgl_registrasi')->map(function ($item) {
				return $item->count();
			})->values();

		$tanggal = $items->groupBy('tgl_registrasi')->keys()->values()->map(function ($item) {
				return Carbon::parse($item)->translatedFormat('d F');
			});

			$dokter = $dokterCollection->firstWhere('kd_dokter', $dokterCode);

			return [
				'tanggal' => $tanggal,
				'jumlah' => $jumlah,
				'dokter' => $dokter ? ['kd_dokter' => $dokter->kd_dokter, 'nm_dokter' => $dokter->nm_dokter] : [],
			];
		})->values();
	}

	// Main function to handle the request
	public function get(Request $request)
	{
		$dokterCollection = $this->getJadwalDokterCollection();
		$dokterSpesialis = $this->getDokterSpesialisCodes($dokterCollection);
		$regPeriksa = $this->getGroupedRegistrationData($dokterSpesialis, $request);
		return $data = $this->mapRegistrationData($regPeriksa, $dokterCollection);
	}

	public function getByDokter($year, $month, $dokter)
	{
		$request = ['year' => $year, 'month' => $month, 'dokter' => $dokter];
		$registrasi = $this->getRegistrasiPoliDokter(new Request($request));
		return $this->mappingRegistrasiPoliDokter($registrasi, $request['dokter']);
	}

	private function getRegistrasiPoliDokter($request)
	{
		return $this->regPeriksaCollection->getAll($request)
			->where('status_lanjut', 'Ralan')
			->where('kd_dokter', $request->dokter)
			->groupBy('tgl_registrasi');
	}
	private function mappingRegistrasiPoliDokter($registrasi, $kd_dokter)
	{
		$jumlah = $registrasi->map((function ($item) {
			return $item->count();
		}))->values();

		$tanggal = $registrasi->keys()->map(function ($item) {
			return Carbon::parse($item)->translatedFormat('d M y');
		});
		$dokter = $this->dokterCollection->getDokterById($kd_dokter)->only('kd_dokter', 'nm_dokter');

		return ['jumlah' => $jumlah, 'tanggal' => $tanggal, 'dokter' => $dokter];
	}
}
