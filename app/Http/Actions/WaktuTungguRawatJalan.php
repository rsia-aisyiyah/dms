<?php

namespace App\Http\Actions;

use App\Models\RegPeriksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WaktuTungguRawatJalan
{
    public function __invoke(Request $request)
    {
        $tgl1 = date('Y-m-d', strtotime($request->tgl_registrasi1));
        $tgl2 = date('Y-m-d', strtotime($request->tgl_registrasi2));
        $tgl_registrasi1 = $request->tgl_registrasi1 ? $tgl1 : date('Y-m-d');
        $tgl_registrasi2 = $request->tgl_registrasi2 ? $tgl2 : date('Y-m-d');

        $data = RegPeriksa::select(['no_rkm_medis', 'no_rawat', 'tgl_registrasi', 'jam_reg', 'kd_poli', 'kd_pj', 'kd_dokter'])->where('status_lanjut', 'Ralan')
            ->whereBetween('tgl_registrasi', [$tgl_registrasi1, $tgl_registrasi2])
            ->where('stts', '!=', 'Batal')
            ->whereHas('poliklinik', function ($query) {
                return $query->where('nm_poli', '!=', 'UGD');
            });

        return $data = $data->with(['pemeriksaanRalan' => function($q){
            return $q->select(['no_rawat', DB::raw('CONCAT(tgl_perawatan, " ", jam_rawat) AS tunggu_poli')]);
        }, 'estimasi','selesai','resepObat' => function($q){
            return $q->select(['no_rawat', 
                DB::raw('CONCAT(tgl_peresepan, " ", jam_peresepan) AS waktu_resep'),
                DB::raw('CONCAT(tgl_perawatan, " ", jam) AS waktu_obat'),
                DB::raw('CONCAT(tgl_penyerahan, " ", jam_penyerahan) AS selesai_obat'),
            ]);
        }, 'poliklinik' => function($q){
            return $q->select(['kd_poli', 'nm_poli']);
        }, 'penjab' => function($q){
            return $q->select(['kd_pj', 'png_jawab']);
        }, 'pasien'=> function($q){
            return $q->select(['no_rkm_medis', 'nm_pasien']);
        }, 'dokter' => function($q){
            return $q->select(['kd_dokter', 'nm_dokter']);
        }])->get();
    }
}
