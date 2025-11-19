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
//		$tahun = $request->tahun ?? date('Y');
//		$bulan = $request->bulan ?? date('m');
//		$kelas = $request->kelas;
//		$data = DB::table('kamar as k')
//			->select(
//				'k.kd_kamar',
//				'k.kelas',
//				'k.kd_bangsal',
//				'b.nm_bangsal',
//				DB::raw('COUNT(DISTINCT ki.no_rawat) as total_pasien'),
//				DB::raw('COALESCE(SUM(ki.lama), 0) as total_lama_inap')
//			)
//			->leftJoin('kamar_inap as ki', function ($join) use ($tahun, $bulan) {
//				$join->on('ki.kd_kamar', '=', 'k.kd_kamar')
//					->whereYear('ki.tgl_keluar', $tahun)
//					->whereMonth('ki.tgl_keluar', $bulan)
//					->where('ki.stts_pulang', '!=', 'Pindah Kamar');
//			})
//			->join('bangsal as b', 'b.kd_bangsal', '=', 'k.kd_bangsal')
//			->where('k.kd_bangsal', 'not like', '%BYA%')
//			->where('k.kd_bangsal', 'not like', '%HCU%')
//			->where('k.statusdata', '1')
//			->where('k.trf_kamar', '>', 0);
//
//		if ($kelas) {
//			$data->where('k.kelas', $kelas);
//		}
//
//		$data->groupBy('k.kd_kamar', 'k.kelas', 'k.kd_bangsal', 'b.nm_bangsal');

		$tahun = $request->tahun ?: date('Y');
		$bulan = $request->bulan ?: date('m');
		$kelas = $request->kelas;
		$tgl1 = $request->tanggal ? $request->tanggal[0] : null;
		$tgl2 = $request->tanggal ? $request->tanggal[1] : null;


		// Jika ada range tanggal → abaikan tahun & bulan
		$useRange = ($tgl1 && $tgl2);

		$qPasien = $this->queryTotalPasien($tahun, $bulan, $tgl1, $tgl2, $useRange);
		$qLama = $this->queryTotalLamaInap($tahun, $bulan, $tgl1, $tgl2, $useRange);
		$qPulang = $this->queryTotalPasienPulang($tahun, $bulan, $tgl1, $tgl2, $useRange);

		$data = DB::table('kamar as k')
			->select(
				'k.kd_kamar',
				'k.kelas',
				'k.kd_bangsal',
				'b.nm_bangsal',
				DB::raw('COALESCE(tp.total_pasien, 0) as total_pasien'),
				DB::raw('COALESCE(tpl.total_pulang, 0) as total_pulang'),
				DB::raw('COALESCE(tl.total_lama_inap, 0) as total_lama_inap')
			)
			->leftJoinSub($qPasien, 'tp', 'tp.kd_kamar', '=', 'k.kd_kamar')
			->leftJoinSub($qLama, 'tl', 'tl.kd_kamar', '=', 'k.kd_kamar')
			->leftJoinSub($qPulang, 'tpl', 'tpl.kd_kamar', '=', 'k.kd_kamar')
			->join('bangsal as b', 'b.kd_bangsal', '=', 'k.kd_bangsal')
			->where('k.statusdata', '1')
			->where('k.trf_kamar', '>', 0)
			->where('k.kd_bangsal', 'not like', '%BYA%')
			->where('k.kd_bangsal', 'not like', '%HCU%');


		if ($kelas) {
			$data->where('k.kelas', $kelas);
		}

		return DataTables::of($data)->make(true);
//		$data = KamarInap::whereYear('tgl_keluar', $tahun)
//			->whereMonth('tgl_keluar', $bulan)
//			->where('stts_pulang', '!=', 'Pindah Kamar')
//			->whereHas('kamar', function ($q) use ($kelas) {
//				$q->where('kelas', 'like', "%$kelas%")
//					->where('statusdata', '1')
//					->where('trf_kamar', '>', 0)
//					->where('kd_bangsal', 'not like', '%BYA%')
//					->where('kd_bangsal', 'not like', '%HCU%');
//			})
//			->select(
//				'kamar.kd_bangsal',
//				DB::raw('(SELECT nm_bangsal FROM bangsal b WHERE b.kd_bangsal = kamar.kd_bangsal) as nm_bangsal'),
//				DB::raw('kamar.kelas as kelas'),
//				DB::raw('COUNT(DISTINCT kamar_inap.no_rawat) as total_pasien'),
//				DB::raw('SUM(kamar_inap.lama) as total_lama_inap')
//			)
//			->join('kamar', 'kamar.kd_kamar', '=', 'kamar_inap.kd_kamar')
//			->groupBy('kamar.kd_bangsal', 'kamar.kelas')
//			->get();
	}

	private function queryTotalPasien($tahun, $bulan, $start, $end, $useRange)
	{
		$q = DB::table('kamar_inap')
			->select(
				'kd_kamar',
				DB::raw('COUNT(no_rawat) as total_pasien')
			);
//			->where('stts_pulang', '!=', 'Pindah Kamar');

		if ($useRange) {
			$q->whereBetween('tgl_keluar', [$start, $end]);
		} else {
			$q->whereYear('tgl_keluar', $tahun)
				->whereMonth('tgl_keluar', $bulan);
		}

		return $q->groupBy('kd_kamar');
	}

	private function queryTotalLamaInap($tahun, $bulan, $start, $end, $useRange)
	{
		$q = DB::table('kamar_inap')
			->select(
				'kd_kamar',
				DB::raw('SUM(lama) as total_lama_inap')
			);

		if ($useRange) {
			$q->whereBetween('tgl_keluar', [$start, $end]);
		} else {
			$q->whereYear('tgl_keluar', $tahun)
				->whereMonth('tgl_keluar', $bulan);
		}

		return $q->groupBy('kd_kamar');
	}

	private function queryTotalPasienPulang($tahun, $bulan, $start, $end, $useRange)
	{
		$q = DB::table('kamar_inap')
			->select(
				'kd_kamar',
				DB::raw('COUNT(DISTINCT no_rawat) as total_pulang')
			)
			->where('stts_pulang', '!=', 'Pindah Kamar');

		if ($useRange) {
			$q->whereBetween('tgl_keluar', [$start, $end]);
		} else {
			$q->whereYear('tgl_keluar', $tahun)
				->whereMonth('tgl_keluar', $bulan);
		}

		return $q->groupBy('kd_kamar');
	}


}
