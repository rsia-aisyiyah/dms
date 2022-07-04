<?php

namespace App\Http\Controllers;

use App\Models\PaketOperasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class PaketOperasiController extends Controller
{
    public function index()
    {
        return view('dashboard.content.tarif.tarif_operasi', [
            'title' => 'Paket Operasi',
            'bigTitle' => 'Paket Operasi',
        ]);
    }

    public function getTarif(Request $request)
    {

        if ($request->ajax()) {
            $tarif = PaketOperasi::select('*', DB::raw('(operator1+operator2+operator3+asisten_operator1+asisten_operator2+asisten_operator3+instrumen+dokter_anak+perawaat_resusitas+dokter_anestesi+asisten_anestesi+asisten_anestesi2+bidan+bidan2+bidan3+perawat_luar+alat+sewa_ok+akomodasi+bagian_rs+omloop+omloop2+omloop3+omloop4+omloop5+sarpras+dokter_pjanak+dokter_umum) as total'))
                ->where('status', '1');
            if ($request->pembiayaan) {
                $tarif->whereHas('penjab', function ($q) use ($request) {
                    $q->where('png_jawab', 'like', '%' . $request->pembiayaan . '%');
                });
            }

            if ($request->kelas) {
                $tarif->where('kelas', $request->kelas);
            }

            if ($request->kategori) {
                $tarif->where('kategori', $request->kategori);

            }
            return DataTables::of($tarif->get())
                ->editColumn('kd_pj', function ($tarif) {
                    return $tarif->penjab->png_jawab;
                })
                ->make(true);
        }

    }

    public function getLastTarif(Request $request)
    {
        if ($request->ajax()) {
            $tarif = PaketOperasi::where('kode_paket', 'like', '%PK0%')->get()->last();
            $akhir = explode('PK', $tarif->kode_paket);
            return 'PK' . sprintf('%06d', (int) $akhir[1] + 1);
        }
    }
    public function addTarif(Request $request)
    {
        if ($request->ajax()) {
            DB::table('paket_operasi')->insert([
                'kode_paket' => $request->kode_paket,
                'nm_perawatan' => $request->nm_perawatan,
                'kategori' => $request->kategori,
                'operator1' => $request->operator1,
                'operator2' => $request->operator2,
                'operator3' => $request->operator3,
                'asisten_operator1' => $request->asisten_operator1,
                'asisten_operator2' => $request->asisten_operator2,
                'asisten_operator3' => $request->asisten_operator3,
                'instrumen' => $request->instrumen,
                'dokter_anak' => $request->dokter_anestesi,
                'perawaat_resusitas' => $request->perawat_resusitas,
                'dokter_anestesi' => $request->dokter_anestesi,
                'asisten_anestesi' => $request->asisten_anes,
                'asisten_anestesi2' => $request->asisten_anes2,
                'bidan' => $request->bidan,
                'bidan2' => $request->bidan2,
                'bidan3' => $request->bidan3,
                'perawat_luar' => $request->perawat_luar,
                'alat' => $request->alat,
                'sewa_ok' => $request->sewa_ok,
                'akomodasi' => $request->akomodasi,
                'bagian_rs' => $request->bagian_rs,
                'omloop' => $request->omloop,
                'omloop2' => $request->omloop2,
                'omloop3' => $request->omloop3,
                'omloop4' => $request->omloop4,
                'omloop5' => $request->omloop5,
                'sarpras' => $request->sarpras,
                'dokter_pjanak' => $request->dokter_pjanak,
                'dokter_umum' => $request->dokter_umum,
                'kd_pj' => $request->kd_pj,
                'kelas' => $request->kelas,
                'status' => '1',
            ]);
        }
    }
    public function setTarif(Request $request)
    {
        if ($request->ajax()) {
            DB::table('paket_operasi')->where('kode_paket', $request->kode_paket)->update([
                'nm_perawatan' => $request->nm_perawatan,
                'kategori' => $request->kategori,
                'operator1' => $request->operator1,
                'operator2' => $request->operator2,
                'operator3' => $request->operator3,
                'asisten_operator1' => $request->asisten_operator1,
                'asisten_operator2' => $request->asisten_operator2,
                'asisten_operator3' => $request->asisten_operator3,
                'instrumen' => $request->instrumen,
                'dokter_anak' => $request->dokter_anestesi,
                'perawaat_resusitas' => $request->perawat_resusitas,
                'dokter_anestesi' => $request->dokter_anestesi,
                'asisten_anestesi' => $request->asisten_anes,
                'asisten_anestesi2' => $request->asisten_anes2,
                'bidan' => $request->bidan,
                'bidan2' => $request->bidan2,
                'bidan3' => $request->bidan3,
                'perawat_luar' => $request->perawat_luar,
                'alat' => $request->alat,
                'sewa_ok' => $request->sewa_ok,
                'akomodasi' => $request->akomodasi,
                'bagian_rs' => $request->bagian_rs,
                'omloop' => $request->omloop,
                'omloop2' => $request->omloop2,
                'omloop3' => $request->omloop3,
                'omloop4' => $request->omloop4,
                'omloop5' => $request->omloop5,
                'sarpras' => $request->sarpras,
                'dokter_pjanak' => $request->dokter_pjanak,
                'dokter_umum' => $request->dokter_umum,
                'kd_pj' => $request->kd_pj,
                'kelas' => $request->kelas,
            ]);
        }

    }
    public function getTarifById(Request $request, $id)
    {
        if ($request->ajax()) {
            $tarif = PaketOperasi::where('kode_paket', $id)
                ->select('*', DB::raw('(operator1+operator2+operator3+asisten_operator1+asisten_operator2+asisten_operator3+instrumen+dokter_anak+perawaat_resusitas+dokter_anestesi+asisten_anestesi+asisten_anestesi2+bidan+bidan2+bidan3+perawat_luar+alat+sewa_ok+akomodasi+bagian_rs+omloop+omloop2+omloop3+omloop4+omloop5+sarpras+dokter_pjanak+dokter_umum) as total'))
                ->with('penjab')->get();
            return $tarif;
        }
    }
}
