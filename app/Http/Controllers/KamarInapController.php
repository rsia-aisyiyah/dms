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

	public function rekapKunjungan(Request $request)
	{
		$kelas = $this->kelas;
		$jumlahKelas = $this->jumlah;
		$tahun = $request->tahun ? $request->tahun : date('Y');
		$bulan = $request->bulan ? $request->bulan : date('m');
		foreach ($kelas as $i => $k) {

			$kamarInap = KamarInap::whereYear('tgl_keluar', $tahun)
				->whereMonth('tgl_keluar', $bulan)
				->where('stts_pulang', '!=', 'Pindah Kamar')
				->whereHas('kamar', function ($q) use ($k) {
					$q->where('kelas', 'like', "%" . $k . "%")
						->where('kd_bangsal', 'not like', '%BYA%');
				})->get()->count();


			$data[] = [
				'kelas' => $k,
				'jumlahKelas' => $jumlahKelas[$i],
				'data' => $kamarInap,
				'lama' => KamarInap::whereMonth('tgl_keluar', $bulan)
					->whereYear('tgl_keluar', $tahun)
					->whereHas('kamar', function ($q) use ($k) {
						$q->where('kelas', 'like', "%" . $k . "%");
					})
					->select('lama')->get()->sum('lama'),
			];
		}

		$kamarVK = KamarInap::whereYear('tgl_keluar', $tahun)
			->whereMonth('tgl_keluar', $bulan)
			->where('stts_pulang', '!=', 'Pindah Kamar')
			->whereHas('kamar', function ($q) {
				$q->where('kd_bangsal', 'like', '%VK%');
			})->get()->count();

		$vk[] = [
			'kelas' => 'VK',
			'jumlahKelas' => Kamar::where('kd_kamar', 'like', '%VK%')->get()->count(),
			'data' => $kamarVK,
			'lama' => KamarInap::whereMonth('tgl_keluar', $bulan)
				->whereYear('tgl_keluar', $tahun)
				->select('lama')->where('kd_kamar', 'like', '%VK%')->get()->sum('lama'),
		];
		$kamarICU = KamarInap::whereYear('tgl_keluar', $tahun)
			->whereMonth('tgl_keluar', $bulan)
			->where('stts_pulang', '!=', 'Pindah Kamar')
			->whereHas('kamar', function ($q) {
				$q->where('kd_bangsal', 'like', 'ICU%');
			})->get()->count();
		$kamarPICU = KamarInap::whereYear('tgl_keluar', $tahun)
			->whereMonth('tgl_keluar', $bulan)
			->where('stts_pulang', '!=', 'Pindah Kamar')
			->whereHas('kamar', function ($q) {
				$q->where('kd_bangsal', 'like', 'PICU%');
			})->get()->count();
		$kamarNICU = KamarInap::whereYear('tgl_keluar', $tahun)
			->whereMonth('tgl_keluar', $bulan)
			->where('stts_pulang', '!=', 'Pindah Kamar')
			->whereHas('kamar', function ($q) {
				$q->where('kd_bangsal', 'like', 'NICU%');
			})->get()->count();
		$kamarIsolasi = KamarInap::whereYear('tgl_keluar', $tahun)
			->whereMonth('tgl_keluar', $bulan)
			->where('stts_pulang', '!=', 'Pindah Kamar')
			->whereHas('kamar', function ($q) {
				$q->where('kd_bangsal', 'like', '%ISO%');
			})->get()->count();
		$icu[] = [
			'kelas' => 'ICU',
			'jumlahKelas' => Kamar::where('kd_kamar', 'like', 'ICU%')
				->where('statusdata', '1')->get()->count(),
			'data' => $kamarICU,
			'lama' => KamarInap::whereMonth('tgl_keluar', $bulan)
				->whereYear('tgl_keluar', $tahun)
				->select('lama')->where('kd_kamar', 'like', '%ICU%')->get()->sum('lama'),
		];
		$picu[] = [
			'kelas' => 'PICU',
			'jumlahKelas' => Kamar::where('kd_kamar', 'like', 'PICU%')
				->where('statusdata', '1')->get()->count(),
			'data' => $kamarPICU,
			'lama' => KamarInap::whereMonth('tgl_keluar', $bulan)
				->whereYear('tgl_keluar', $tahun)
				->select('lama')->where('kd_kamar', 'like', '%PICU%')->get()->sum('lama'),
		];
		$nicu[] = [
			'kelas' => 'NICU',
			'jumlahKelas' => Kamar::where('kd_kamar', 'like', 'NICU%')
				->where('statusdata', '1')->get()->count(),
			'data' => $kamarNICU,
			'lama' => KamarInap::whereMonth('tgl_keluar', $bulan)
				->whereYear('tgl_keluar', $tahun)
				->select('lama')->where('kd_kamar', 'like', '%NICU%')->get()->sum('lama'),
		];

		$isolasi[] = [
			'kelas' => 'ISOLASI',
			'jumlahKelas' => Kamar::where('kd_kamar', 'like', '%ISO%')
				->where('statusdata', '1')->get()->count(),
			'data' => $kamarIsolasi,
			'lama' => KamarInap::whereMonth('tgl_keluar', $bulan)
				->whereYear('tgl_keluar', $tahun)
				->select('lama')->where('kd_kamar', 'like', '%ISO%')->get()->sum('lama'),
		];

		$dataMerge = array_merge($data, $vk, $icu, $picu, $nicu, $isolasi);
		return DataTables::of($dataMerge)->make(true);
	}

	public function jumlahKamar()
	{
		$data = Kamar::select(DB::raw('count(*) as jumlah'), 'kelas')
			->where('trf_kamar', '!=', 0)
			->where('statusdata', '1')
			->where('kelas', '!=', 'Kelas Utama')
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
