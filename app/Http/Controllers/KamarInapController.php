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

			$kamarInap = KamarInap::where('stts_pulang', '!=', 'Pindah Kamar')
				->whereYear('tgl_keluar', $tahun)
				->whereMonth('tgl_keluar', $bulan)
				->whereHas('kamar', function ($q) use ($k) {
					$q->where('kelas', 'like', "%" . $k . "%");
				})->get()->count();
			$kamarHcu = KamarInap::where('stts_pulang', '!=', 'Pindah Kamar')
				->whereYear('tgl_keluar', $tahun)
				->whereMonth('tgl_keluar', $bulan)
				->whereHas('kamar', function ($q) {
					$q->whereIn('kd_bangsal', ['HCU1', 'HCU2']);
				})->get()->count();
			$kamarIcu = KamarInap::where('stts_pulang', '!=', 'Pindah Kamar')
				->whereYear('tgl_keluar', $tahun)
				->whereMonth('tgl_keluar', $bulan)
				->whereHas('kamar', function ($q) {
					$q->where('kd_bangsal', 'like', '%ICU%');
				})->get()->count();

			$data[] = [
				'kelas' => $k,
				'jumlahKelas' => $jumlahKelas[$i],
				'data' => $kamarInap,
			];
		}

		$hcu[] = [
			'kelas' => 'HCU',
			'jumlahKelas' => Kamar::where('kd_kamar', 'like', '%HCU%')->get()->count(),
			'data' => $kamarHcu,
		];

		$dataMerge = array_merge($data, $hcu);
		return DataTables::of($dataMerge)->make(true);
	}

	public function jumlahKamar()
	{
		$data = Kamar::select(DB::raw('count(*) as jumlah'), 'kelas')
			->where('trf_kamar', '!=', 0)
			->where('statusdata', '1')
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
