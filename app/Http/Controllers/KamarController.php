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
		// Tentukan Tahun dan Bulan yang akan difilter
		// Anda bisa mengambil ini dari request ($request->input('tahun', '2025'))
		$tahun = $request->tahun ? $request->tahun : date('Y');
		$bulan = $request->bulan ? $request->bulan : date('m');

		$kamar = Kamar::where('statusdata', '1')->whereHas('inap', function ($query) use ($tahun, $bulan) {
			// 1. Memfilter Kamar: Hanya mengambil kamar yang memiliki relasi inap yang memenuhi kriteria
			$query->whereYear('tgl_keluar', $tahun)->whereMonth('tgl_keluar', $bulan);
		})
			// 2. Eager Load Relasi 'inap': Memuat data inap yang difilter
			->with(['inap' => function ($q) use ($tahun, $bulan) {
				$q->whereYear('tgl_keluar', $tahun)->whereMonth('tgl_keluar', $bulan);
			}])
			->with('bangsal')
			// 3. Menghitung Jumlah Relasi 'inap': Menambahkan 'inap_count'
			->withCount(['inap' => function ($q) use ($tahun, $bulan) {
				$q->whereYear('tgl_keluar', $tahun)->whereMonth('tgl_keluar', $bulan);
			}])
			// 4. Menghitung Total Lama Inap: Menambahkan 'inap_sum_lama' (nama kolom default)
			// Saya akan menggunakan alias agar namanya menjadi 'total_lama_inap'
			->withSum([
				'inap' => function ($q) use ($tahun, $bulan) {
					$q->whereYear('tgl_keluar', $tahun)->whereMonth('tgl_keluar', $bulan);
				}
			], 'lama')
			->get()
			// 5. Mengganti Nama Kolom Sum (Opsional):
			// Jika Anda ingin nama kolomnya persis 'total_lama_inap' bukan 'inap_sum_lama'
			->map(function ($k) {
				$k->total_lama_inap = $k->inap_sum_lama;
				unset($k->inap_sum_lama); // Hapus kolom asli jika tidak diperlukan
				return $k;
			});

		return DataTables::of($kamar)->make(true);
	}
}
