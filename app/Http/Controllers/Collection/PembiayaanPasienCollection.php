<?php

namespace App\Http\Controllers\Collection;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PenjabController;
use App\Models\BridgingSep;
use App\Models\RegPeriksa;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;

class PembiayaanPasienCollection extends Controller
{
	protected $regPeriksa;
	protected $penjab;
	protected $sep;
	protected $allPenjab;

	public function __construct()
	{
		$this->regPeriksa = new RegPeriksaCollection();
		$this->penjab = new PenjabController();
		$this->sep = new BridgingSep();
	}

	public function getPembiayaan(Request $request)
	{
		$penjabCollection = $this->getAllPenjab();
		$regPeriksa = $this->regPeriksa->getAll($request);

		if (!count($regPeriksa)) {
			return [
				['status' => 'Ralan', 'data' => []],
				['status' => 'Ranap', 'data' => []],
			];
		}

		return $regPeriksa->groupBy('status_lanjut')->map(function ($item) use ($penjabCollection) {
			$groupItem = $item->groupBy('kd_pj')->map(function ($items) use ($penjabCollection) {
				$penjab = $penjabCollection->firstWhere('kd_pj', $items->first()->kd_pj);
				return [
					'jumlah' => $items->count(),
					'kd_pj' => $penjab['kd_pj'],
					'png_jawab' => $penjab['png_jawab']
				];
			})->sortBy('png_jawab')->values();
			return ['status' => $item->first()->status_lanjut, 'data' => $groupItem];
		})->values();
	}

	private function getAllPenjab()
	{
		return collect($this->penjab->getAllPenjab())->map(function ($item) {
			return ['kd_pj' => $item->kd_pj, 'png_jawab' => $item->png_jawab];
		});
	}

	public function getPenjabBpjs($year = '', $month = '')
	{
		 $sep = $this->sep->month($month, $year)
			->whereHas('regPeriksa', function($query){
				$query->where('stts', 'Sudah');
			})->get();
		$sepCollection = collect($sep);
		return $sepCollection->groupBy('jnspelayanan')->map(function ($item) {
			$pelayanan = $item->first()->jnspelayanan;
			$pelayanan = $pelayanan === '1' ? 'Rawat Inap' : 'Rawat Jalan';
			$groupItem = $item->groupBy('peserta')->map(function ($items, $key) {
				return $items->count();
			});

			return ['jnspelayanan' => $pelayanan, 'data' => $groupItem];
		})->values();
	}
}
