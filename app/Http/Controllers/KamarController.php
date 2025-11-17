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
		$data = DB::table('kamar as k')
			->select(
				'k.kd_kamar',
				'k.kelas',
				'k.kd_bangsal',
				'b.nm_bangsal',
				DB::raw('COALESCE(COUNT(DISTINCT ki.no_rawat), 0) as total_pasien'),
				DB::raw('COALESCE(SUM(ki.lama), 0) as total_lama_inap')
			)
			->leftJoin('kamar_inap as ki', function ($join) use ($tahun, $bulan) {
				$join->on('ki.kd_kamar', '=', 'k.kd_kamar')
					->whereYear('ki.tgl_keluar', $tahun)
					->whereMonth('ki.tgl_keluar', $bulan);
			})
			->join('bangsal as b', 'b.kd_bangsal', '=', 'k.kd_bangsal')
			->where('k.kd_bangsal', 'not like', '%BYA%')
			->where('k.statusdata', '1')
			->where('k.trf_kamar', '>', 0);

		if ($kelas) {
			$data->where('k.kelas', $kelas);
		}

		$data->groupBy('k.kd_kamar', 'k.kelas', 'k.kd_bangsal', 'b.nm_bangsal');

		return DataTables::of($data)->make(true);

	}

}
