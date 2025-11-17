<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class KamarController extends Controller
{
	//
	public function getTarif(Request $request)
	{
		$status = $request->status;
		$kelas = $request->kelas;

		if ($request->ajax()) {
			$kamar = Kamar::where('statusdata', '1');
			if ($status) {
				$kamar->where('status', $status)->get();
			}
			if ($kelas) {
				$kamar->where('kelas', $kelas)->get();
			}
		}

		return DataTables::of($kamar)
			->editColumn('nm_bangsal', function ($kamar) {
				return $kamar->bangsal->nm_bangsal;
			})
			->make(true);
	}

	public function index()
	{
		return view('dashboard.content.kamar.list_tarifkamar', [
			'title' => 'Tarif Kamar',
			'bigTitle' => 'Tarif Kamar',
		]);
	}

	public function getTarifById($kd_bangsal)
	{
		$kamar = Kamar::where('kd_bangsal', $kd_bangsal)
			->where('statusdata', '1')
			->with('bangsal')->get();

		return json_encode($kamar);
	}

	public function setTarifKamar(Request $request)
	{
		$kd_bangsal = $request->kd_bangsal;
		$status = $request->status;
		$tarif = $request->tarif;
		DB::table('kamar')->where('kd_bangsal', $kd_bangsal)->update(
			[
				'trf_kamar' => $tarif,
				'status' => $status,
			]
		);
	}

	public function rekapKamar(Request $request)
	{
		$tahun = $request->tahun ?? date('Y');
		$bulan = $request->bulan ?? date('m');
		$kelas = $request->kelas;

		$data = DB::table('kamar_inap as ki')
			->select(
				'ki.kd_kamar',
				DB::raw('COUNT(DISTINCT ki.no_rawat) as total_pasien'),
				DB::raw('SUM(ki.lama) as total_lama_inap'),
				'kamar.kd_bangsal',
				'kamar.kelas as kelas',
				'bangsal.nm_bangsal',

			)
			->where('kamar.kd_bangsal', 'not like', '%BYA%')
			->join('kamar', 'kamar.kd_kamar', '=', 'ki.kd_kamar')
			->join('bangsal', 'bangsal.kd_bangsal', '=', 'kamar.kd_bangsal')
			->whereYear('ki.tgl_keluar', $tahun)
			->whereMonth('ki.tgl_keluar', $bulan)
			->groupBy('ki.kd_kamar', 'kamar.kd_bangsal', 'bangsal.nm_bangsal');

		if ($kelas) {
			$data = $data->where('kamar.kelas', $kelas);
		}

		$data->get();

		return DataTables::of($data)->make(true);
	}

}
