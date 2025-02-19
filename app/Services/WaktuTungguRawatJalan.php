<?php

namespace App\Services;

use App\Models\RegPeriksa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WaktuTungguRawatJalan
{
    
    function get(Request $request){

        $tgl1 = date('Y-m-d', strtotime($request->tgl_registrasi1));
        $tgl2 = date('Y-m-d', strtotime($request->tgl_registrasi2));
        $tgl_registrasi1 = $request->tgl_registrasi1 ? $tgl1 : date('Y-m-d');
        $tgl_registrasi2 = $request->tgl_registrasi2 ? $tgl2 : date('Y-m-d');

        $data = RegPeriksa::select(['no_rkm_medis', 'no_rawat', 'tgl_registrasi', 'jam_reg', 'kd_poli', 'kd_pj', 'kd_dokter'])->where('status_lanjut', 'Ralan')
            
            ->where('stts', '!=', 'Batal')
            ->whereHas('poliklinik', function ($query) {
                return $query->where('nm_poli', '!=', 'UGD');
            })
            ->whereHas('pemeriksaanRalan')
            ->whereHas('estimasi')
            ->whereHas('selesai')
            ->with(['pemeriksaanRalan' => function($q){
            return $q->select(['no_rawat', DB::raw('CONCAT(tgl_perawatan, " ", jam_rawat) AS tunggu_poli')]);
        }, 'estimasi','selesai','resepObat' => function($q){
            return $q->select(['no_rawat', 
                DB::raw('CONCAT(tgl_peresepan, " ", jam_peresepan) AS waktu_resep'),
                DB::raw('CONCAT(tgl_perawatan, " ", jam) AS waktu_obat'),
                DB::raw('CONCAT(tgl_penyerahan, " ", jam_penyerahan) AS selesai_obat'),
            ]);
        }]);

            if($request->year){
                $data = $data->whereYear('tgl_registrasi', $request->year);
            }else{
                $data = $data->whereBetween('tgl_registrasi', [$tgl_registrasi1, $tgl_registrasi2])
                ->with(['poliklinik' => function($q){
                    return $q->select(['kd_poli', 'nm_poli']);
                }, 'penjab' => function($q){
                    return $q->select(['kd_pj', 'png_jawab']);
                }, 'pasien'=> function($q){
                    return $q->select(['no_rkm_medis', 'nm_pasien']);
                }, 'dokter' => function($q){
                    return $q->select(['kd_dokter', 'nm_dokter']);
                }]);
            }

        return $data;

        

        // return $data = $data->with(['pemeriksaanRalan' => function($q){
        //     return $q->select(['no_rawat', DB::raw('CONCAT(tgl_perawatan, " ", jam_rawat) AS tunggu_poli')]);
        // }, 'estimasi','selesai','resepObat' => function($q){
        //     return $q->select(['no_rawat', 
        //         DB::raw('CONCAT(tgl_peresepan, " ", jam_peresepan) AS waktu_resep'),
        //         DB::raw('CONCAT(tgl_perawatan, " ", jam) AS waktu_obat'),
        //         DB::raw('CONCAT(tgl_penyerahan, " ", jam_penyerahan) AS selesai_obat'),
        //     ]);
        // }]);
    }

    // function getByYear($year = '')
    // {
    //     $year = $year ?: date('Y');
    //     $yearRequest = new Request(['year' => $year]);
      
    //     $data = $this->get($yearRequest)->get();

    //     return collect($data)->map(function ($item) {
    //         $waktu_tunggu_poli = isset($item->pemeriksaanRalan->tunggu_poli)
    //             ? $this->dateTimeDiffInSeconds($item->pemeriksaanRalan->tunggu_poli, $item->estimasi->jam_periksa)
    //             : 0;

    //         $waktu_layanan_poli = isset($item->selesai->jam_periksa)
    //             ? $this->dateTimeDiffInSeconds($item->estimasi->jam_periksa, $item->selesai->jam_periksa)
    //             : 0;

    //         $waktu_tunggu_obat = isset($item->resepObat->waktu_obat)
    //             ? $this->dateTimeDiffInSeconds($item->resepObat->waktu_resep, $item->resepObat->waktu_obat)
    //             : 0;

    //         $waktu_layanan_obat = isset($item->resepObat->selesai_obat)
    //             ? $this->dateTimeDiffInSeconds($item->resepObat->waktu_obat, $item->resepObat->selesai_obat)
    //             : 0;

    //         $total = $waktu_tunggu_poli + $waktu_layanan_poli + $waktu_tunggu_obat + $waktu_layanan_obat;

    //         return [
    //             'tgl_registrasi' => $item->tgl_registrasi . ' ' . $item->jam_reg,
    //             'waktu_tunggu_poli' => $this->formatTime($waktu_tunggu_poli),
    //             'waktu_layanan_poli' => $this->formatTime($waktu_layanan_poli),
    //             'waktu_tunggu_obat' => $this->formatTime($waktu_tunggu_obat),
    //             'waktu_layanan_obat' => $this->formatTime($waktu_layanan_obat),
    //             'total' => $this->formatTime($total),
    //         ];
    //     });
    // }

    function getByYear($year = '')
    {
        $year = $year ?: date('Y');
        $yearRequest = new Request(['year' => $year]);
        
        $result = collect();

        $this->get($yearRequest)->orderBy('tgl_registrasi')->chunk(5000, function ($data) use (&$result) {
            foreach ($data as $item) {
                $waktu_tunggu_poli = isset($item->pemeriksaanRalan->tunggu_poli)
                    ? $this->dateTimeDiffInSeconds($item->pemeriksaanRalan->tunggu_poli, $item->estimasi->jam_periksa)
                    : 0;

                $waktu_layanan_poli = isset($item->selesai->jam_periksa)
                    ? $this->dateTimeDiffInSeconds($item->estimasi->jam_periksa, $item->selesai->jam_periksa)
                    : 0;

                $waktu_tunggu_obat = isset($item->resepObat->waktu_obat)
                    ? $this->dateTimeDiffInSeconds($item->resepObat->waktu_resep, $item->resepObat->waktu_obat)
                    : 0;

                $waktu_layanan_obat = isset($item->resepObat->selesai_obat)
                    ? $this->dateTimeDiffInSeconds($item->resepObat->waktu_obat, $item->resepObat->selesai_obat)
                    : 0;

                $total = $waktu_tunggu_poli + $waktu_layanan_poli + $waktu_tunggu_obat + $waktu_layanan_obat;

                $result->push([
                    'tgl_registrasi' => $item->tgl_registrasi . ' ' . $item->jam_reg,
                    'waktu_tunggu_poli' => $this->formatTime($waktu_tunggu_poli),
                    'waktu_layanan_poli' => $this->formatTime($waktu_layanan_poli),
                    'waktu_tunggu_obat' => $this->formatTime($waktu_tunggu_obat),
                    'waktu_layanan_obat' => $this->formatTime($waktu_layanan_obat),
                    'total' => $this->formatTime($total),
                ]);
            }
        });

    return $result;
}



    function dateTimeDiffInSeconds($date1, $date2)
    {
        if ($date1 === '0000-00-00 00:00:00' || $date2 === '0000-00-00 00:00:00') {
            return 0;
        }
        return abs(strtotime($date2) - strtotime($date1));
    }

    function formatTime($seconds)
    {
        return $seconds > 0 ? gmdate("H:i:s", $seconds) : '00:00:00';
    }


    function groupByMonth($year='')
    {   
        $year = $year ?: date('Y');
        $data = $this->getByYear($year);

        return collect($data)->groupBy(function ($item) {
            return Carbon::parse($item['tgl_registrasi'])->format('Y-m'); 
        })->map(function ($items) {
            return [
                'bulan' => Carbon::parse($items[0]['tgl_registrasi'])->format('F Y'),
                'waktu_tunggu_poli' => $this->groupingWaktu($items, 'waktu_tunggu_poli'),
                'waktu_layanan_poli' => $this->groupingWaktu($items, 'waktu_layanan_poli'),
                'waktu_tunggu_obat' => $this->groupingWaktu($items, 'waktu_tunggu_obat'),
                'waktu_layanan_obat' => $this->groupingWaktu($items, 'waktu_layanan_obat'),
                'total' => $this->groupingWaktu($items, 'total'),
            ];
        });
    }

    function groupingWaktu($data, $name){
        $waktuGrouping = $data->map(function ($item) use ($name) {
            return $item[$name];
        });

        return $this->hitungRataRataWaktu($waktuGrouping);
    }

    function hitungRataRataWaktu($waktuArray) {
        $totalDetik = 0;
        $arrayTime = array_diff($waktuArray->toArray(), ['00:00:00']);
        $jumlahWaktu = count($arrayTime);

        if ($jumlahWaktu == 0) {
            return "00:00:00";
        }

        foreach ($waktuArray as $waktu) {
            $bagianWaktu = explode(":", $waktu);
            $jam = intval($bagianWaktu[0]);
            $menit = intval($bagianWaktu[1]);
            $detik = intval($bagianWaktu[2]);

            $totalDetik += ($jam * 3600) + ($menit * 60) + $detik;
        }

        $rataRataDetik = $totalDetik / $jumlahWaktu;

        $rataRataJam = floor($rataRataDetik / 3600);
        $rataRataDetik %= 3600;
        $rataRataMenit = floor($rataRataDetik / 60);
        $rataRataDetik %= 60;

        $rataRataWaktu = sprintf("%02d:%02d:%02d", $rataRataJam, $rataRataMenit, $rataRataDetik);

        return $rataRataWaktu;
    }

   
  
}
