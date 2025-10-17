<?php

namespace App\Http\Controllers;

use App\Services\WaktuTungguRawatJalan as ServicesWaktuTungguRawatJalan;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class WaktuTungguRawatJalanController extends Controller
{
	protected $service;

	public function __construct()
	{
		$this->service = new ServicesWaktuTungguRawatJalan();
	}

	public function index()
	{
		return view(
			'dashboard.content.ralan.table_waktu_tunggu',
			[
				'title' => 'Waktu Tunggu Rawat Jalan',
				'bigTitle' => 'Waktu Tunggu Rawat Jalan',
			]
		);
	}

	public function get(Request $request)
	{
		$data = $this->service->get($request);
		return DataTables::of($data)
			->filter(function ($query) use ($request) {

				if ($request->has('search') && $request->get('search')['value']) {
					$query->where('no_rawat', $request->get('search')['value']);
				}

				if ($request->has('tgl_registrasi1') && $request->has('tgl_registrasi2')) {
					$query->whereBetween('tgl_registrasi', [$request->tgl_registrasi1, $request->tgl_registrasi2]);
				}
				if ($request->has('sps')) {
					$query->whereHas('dokter', function ($query) use ($request) {
						return $query->where('kd_sps', $request->sps);
					});
				}
				if ($request->has('dokter')) {
					$query->where('kd_dokter', $request->dokter);
				}
			})
			->setRowClass(function ($data) {
				return $data->estimasi ? '' : 'text-danger';
			})
			->make(true);
	}

	public function getByYear($tahun = null)
	{
		$tahun = $tahun ?? date('Y');
		$data = $this->service->groupByMonth($tahun);
		return DataTables::of($data)->make(true);
	}
}
