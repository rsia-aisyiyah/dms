<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DokterController extends Controller
{
	public function semuaDokter()
	{
		$dokter = Dokter::where('status', '1')
			->whereHas('pegawai', function ($q) {
				$q->where('jbtn', '!=', '-')
					->whereIn('jnj_jabatan', ['RS10', 'DIRU']);
			})
			->get();

		return response()->json($dokter);
	}

	public function getDokterSpesialis(Request $request = null)
	{
		$data = Dokter::orderBy('kd_sps', 'asc')->where('status', '1');
		if ($request) {
			if ($request->sps) {
				$data = $data->where('kd_sps', $request->sps);

			}
		} else {
			$data = $data->where('kd_sps', '!=', 'S0007')
				->where('nm_dokter', '!=', '-');
		}
		return $data->get();

	}

	public function getDokterPoli(): JsonResponse
	{
		$dokter = Dokter::whereHas('jadwal')->get();
		return response()->json($dokter);
	}

	function getDokterById($kd_dokter)
	{

		return Dokter::where('kd_dokter', $kd_dokter)->first();
	}
}
