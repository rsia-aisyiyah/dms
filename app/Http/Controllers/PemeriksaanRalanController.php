<?php

namespace App\Http\Controllers;

use App\Models\PemeriksaanRalan;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PemeriksaanRalanController extends Controller
{
	public $model;

	public function __construct(PemeriksaanRalan $model)
	{
		$this->model = $model;
	}

	public function get(Request $request)
	{
		$tgl1 = $request->tgl1;
		$tgl2 = $request->tgl2;

		$data = $this->model->with(['regPeriksa.pasien', 'regPeriksa.diagnosaPasien', 'petugas', 'dokter.spesialis'])
			->whereHas('regPeriksa', function ($q) {
				return $q->where('stts', '!=', 'Batal');
			})->whereHas('dokter');

		if ($tgl1 && $tgl2) {
			$data = $data->whereBetween('tgl_perawatan', [$tgl1, $tgl2]);
		} else {
			$data = $data->whereMonth('tgl_perawatan', 10)
				->whereYear('tgl_perawatan', date('Y'));
		}

		return DataTables::of($data)
			->filter(function ($query) use ($request) {
				if ($request->filled('tgl1') && $request->filled('tgl2')) {
					$query->whereBetween('tgl_perawatan', [
						$request->tgl1,
						$request->tgl2
					]);
				}
				if ($request->poli) {
					$query->whereHas('dokter', function ($q) use ($request) {
						$q->where('kd_sps', $request->poli);
					});
				}
				if ($request->dokter) {
					$query->where('nip', $request->dokter);
				}

				$keyword = $request->search['value'] ?? null;

				if ($keyword) {

					$query->where(function ($q) use ($keyword) {
						$q->orWhere('pemeriksaan', 'like', "%{$keyword}%");
						$q->orWhere('keluhan', 'like', "%{$keyword}%");
						$q->orWhere('penilaian', 'like', "%{$keyword}%");
					});
				}
			})
			->make(true);

	}
}
