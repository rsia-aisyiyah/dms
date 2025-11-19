<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\KamarInap;
use App\Models\RegPeriksa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class KamarInapController extends Controller
{
	private $kelas;
	private $jumlah;
	private $tanggal;

	public function __construct()
	{
		$this->jumlahKamar();
		$this->tanggal = new Carbon();
	}

//	public function rekapKunjungan(Request $request)
//	{
//		$tahun = $request->tahun ?: date('Y');
//		$bulan = $request->bulan ?: date('m');
//
//		$rows = [];
//
//		// 🔹 KELAS BIASA
//		foreach ($this->kelas as $i => $kelas) {
//
//			$rows[] = $this->getRekapKategori(
//				$kelas,
//				[
//					['kelas', 'like', "%$kelas%"],
//					['statusdata', '=', '1'],
//					['trf_kamar', '>', 0],
//					['kd_bangsal', 'not like', '%BYA%'],
//					['kd_bangsal', 'not like', '%HCU%'],
//				],
//				$tahun,
//				$bulan
//			);
//		}
//
//		$kategoriKhusus = [
//			['VK', ['kd_bangsal', 'like', 'VK%'], ['kd_bangsal', 'like', '%VKISO%']],
//			['ICU', ['kd_bangsal', 'like', 'ICU%']],
//			['PICU', ['kd_bangsal', 'like', 'PICU%']],
//			['NICU', ['kd_bangsal', 'like', 'NICU%']],
//			['ISOLASI', ['kd_bangsal', 'like', '%ISO%']],
//			['PERINATOLOGI', ['kd_bangsal', 'like', '%BYC%']],
//		];
//
//		foreach ($kategoriKhusus as $item) {
//			list($label, $filter) = $item;
//
//			$rows[] = $this->getRekapKategori(
//				$label,
//				[$filter],
//				$tahun,
//				$bulan
//			);
//		}
//
//		return DataTables::of($rows)->make(true);
//	}
//
//	private function getRekapKategori($label, $filterBangsal, $tahun, $bulan)
//	{
//		// Hitung jumlah kamar
//		$jumlahKelas = Kamar::where($filterBangsal)
//			->where('statusdata', '1')
//			->count();
//
//		// Hitung total pasien
//		$totalPasien = KamarInap::whereYear('tgl_keluar', $tahun)
//			->whereMonth('tgl_keluar', $bulan)
//			->where('stts_pulang', '!=', 'Pindah Kamar')
//			->whereHas('kamar', function ($q) use ($filterBangsal) {
//				foreach ($filterBangsal as $filter) {
//					$q->where($filter[0], $filter[1], $filter[2]);
//				}
//			})
//			->count();
//
//		// Hitung total lama inap
//		$totalLama = KamarInap::whereYear('tgl_keluar', $tahun)
//			->whereMonth('tgl_keluar', $bulan)
//			->whereHas('kamar', function ($q) use ($filterBangsal) {
//				foreach ($filterBangsal as $filter) {
//					$q->where($filter[0], $filter[1], $filter[2]);
//				}
//			})
//			->sum('lama');
//
//		return [
//			'kelas' => $label,
//			'jumlahKelas' => $jumlahKelas,
//			'data' => $totalPasien,
//			'lama' => $totalLama,
//		];
//	}

	public function rekapKunjungan(Request $request)
	{
		$tahun = $request->tahun ?: date('Y');
		$bulan = $request->bulan ?: date('m');

		$start = $request->tanggal ? $request->tanggal[0] : null;
		$end = $request->tanggal ? $request->tanggal[1] : null;


		// jika user pakai range tanggal → abaikan tahun-bulan
		$useRange = ($start && $end);

		$rows = [];

		// ============================
		// 1. KELAS NORMAL
		// ============================
		foreach ($this->kelas as $kelas) {

			$rows[] = $this->getRekapKategori(
				$kelas,
				[
					['kelas', 'like', "%$kelas%"],
					['statusdata', '=', '1'],
					['trf_kamar', '>', 0],
					['kd_bangsal', 'not like', '%BYA%'],
					['kd_bangsal', 'not like', '%HCU%'],
				],
				$tahun,
				$bulan,
				$start,
				$end,
				$useRange
			);
		}

		// ============================
		// 2. KATEGORI KHUSUS
		// ============================
		$kategoriKhusus = [
			// VK tapi exclude VKISO
			['VK', [['kd_bangsal', 'like', 'VK%'], ['kd_bangsal', 'not like', '%VKISO%']]],
			['ICU', [['kd_bangsal', 'like', 'ICU%']]],
			['PICU', [['kd_bangsal', 'like', 'PICU%']]],
			['NICU', [['kd_bangsal', 'like', 'NICU%']]],
			['ISOLASI', [['kd_kamar', 'like', '%ISO%']]],
			['PERINATOLOGI', [['kd_bangsal', 'like', '%BYC%']]],
		];

		foreach ($kategoriKhusus as [$label, $filter]) {

			$rows[] = $this->getRekapKategori(
				$label,
				$filter,
				$tahun,
				$bulan,
				$start,
				$end,
				$useRange
			);
		}

		return DataTables::of($rows)->make(true);
	}


	private function getRekapKategori($label, $filterBangsal, $tahun, $bulan, $start, $end, $useRange)
	{
		// ========== HITUNG JUMLAH KAMAR ==========
		$jumlahKelas = Kamar::where($filterBangsal)
			->where('statusdata', '1')
			->count();

		// Query dasar
		$pulangQuery = KamarInap::where('stts_pulang', '!=', 'Pindah Kamar');
		$pasienQuery = KamarInap::query();
		$lamaQuery = KamarInap::query();

		// ========== FILTER TANGGAL ==========
		if ($useRange) {
			$pulangQuery->whereBetween('tgl_keluar', [$start, $end]);
			$pasienQuery->whereBetween('tgl_keluar', [$start, $end]);
			$lamaQuery->whereBetween('tgl_keluar', [$start, $end]);
		} else {
			$pulangQuery->whereYear('tgl_keluar', $tahun)
				->whereMonth('tgl_keluar', $bulan);
			$pasienQuery->whereYear('tgl_keluar', $tahun)
				->whereMonth('tgl_keluar', $bulan);

			$lamaQuery->whereYear('tgl_keluar', $tahun)
				->whereMonth('tgl_keluar', $bulan);
		}

		// ========== FILTER BANGSAL ==========
		$pulangQuery->whereHas('kamar', function ($q) use ($filterBangsal) {
			foreach ($filterBangsal as $filter) {
				$q->where($filter[0], $filter[1], $filter[2]);
			}
		});
		$pasienQuery->whereHas('kamar', function ($q) use ($filterBangsal) {
			foreach ($filterBangsal as $filter) {
				$q->where($filter[0], $filter[1], $filter[2]);
			}
		});

		$lamaQuery->whereHas('kamar', function ($q) use ($filterBangsal) {
			foreach ($filterBangsal as $filter) {
				$q->where($filter[0], $filter[1], $filter[2]);
			}
		});

		// ========== HITUNG ==========
		$totalPulang = $pulangQuery->count();
		$totalPasien = $pasienQuery->count();
		$totalLama = $lamaQuery->sum('lama');

		return [
			'kelas' => $label,
			'jumlahKelas' => $jumlahKelas,
			'data' => $totalPasien,
			'pulang' => $totalPulang,
			'lama' => $totalLama,
		];
	}

	public function jumlahKamar()
	{
		$data = Kamar::select(DB::raw('count(*) as jumlah'), 'kelas')
			->where('statusdata', '1')
			->where('kelas', '!=', 'Kelas Utama')
			->where('trf_kamar', '>', 0)
			->where('kd_bangsal', 'not like', '%BYA%')
			->where('kd_bangsal', 'not like', '%HCU%')
			->groupBy('kelas')->get();

		$this->kelas = $data->pluck('kelas')->toArray();
		$this->jumlah = $data->pluck('jumlah')->toArray();
	}

	public function rekapKamar(Request $request)
	{
		$tgl_pertama = $request->tgl_pertama;
		$tgl_kedua = $request->tgl_kedua;

		$kamar = RegPeriksa::where('status_lanjut', 'Ranap');
		if ($tgl_pertama && $tgl_kedua) {
			$kamar->whereHas('kamarInap', function ($q) use ($tgl_pertama, $tgl_kedua) {
				$q->whereBetween('tgl_keluar', [$tgl_pertama, $tgl_kedua]);
			})->with('kamarInap');
		} else {
			$kamar->whereHas('kamarInap', function ($q) {
				$q->whereYear('tgl_keluar', date('Y'))
					->whereMonth('tgl_keluar', date('m'));
			})->with('kamarInap', function ($q) {
				$q->with('kamar');
			});
		}

		return $kamar;

	}

	public function kamarHCU(Request $request)
	{
		return $this->rekapKamar($request)
			->whereHas('kamarInap', function ($q) {
				$q->where('stts_pulang', '!=', 'Pindah Kamar');
				$q->whereHas('kamar', function ($q) {
					$q->where('kd_kamar', 'like', '%HCU%');
				});
			})->get();

	}

	public function kamarICU(Request $request)
	{
		return $this->rekapKamar($request)
			->whereHas('kamarInap', function ($q) {
				$q->where('stts_pulang', '!=', 'Pindah Kamar');
				$q->whereHas('kamar', function ($q) {
					$q->where('kd_kamar', 'like', '%ICU%');
				});
			})->get();

	}

	public function kamarVK(Request $request)
	{
		return $this->rekapKamar($request)
			->whereHas('kamarInap', function ($q) {
				$q->where('stts_pulang', '!=', 'Pindah Kamar')
					->whereHas('kamar', function ($q) {
						$q->where('kd_kamar', 'like', '%VK%');
					});
			})->get();

	}

}
