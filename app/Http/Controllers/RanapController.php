<?php

namespace App\Http\Controllers;

use App\Models\DiagnosaPasien;
use App\Models\Dokter;
use App\Models\RawatInapDr;
use App\Models\RegPeriksa;
use App\Services\VisitDokterService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class RanapController extends Controller
{
    // view kunjungan pasien rawat inap
    public function index()
    {
        $tanggal = new Carbon('this month');
        return view(
            'dashboard.content.ranap.list_kunjungan_ranap',
            [
                'title' => 'Laporan Rawat Inap',
                'bigTitle' => 'Laporan Rawat Inap',
                'month' => $tanggal->now()->monthName,
                'tglAwal' => $tanggal->startOfMonth()->toDateString(),
                'tglSekarang' => $tanggal->now()->toDateString(),
            ]
        );
    }

    // kunjungan pasien rawat inap
    public function json(Request $request)
    {
        $tanggal = new Carbon('this month');
        $data = RegPeriksa::select('reg_periksa.no_rawat', 'tgl_registrasi', 'kd_dokter', 'no_rkm_medis', 'stts_daftar', 'kd_pj', 'bridging_sep.tglsep', 'umurdaftar', 'sttsumur')
            ->leftJoin('bridging_sep', function ($join) {
                $join->on('reg_periksa.no_rawat', '=', 'bridging_sep.no_rawat')
                    ->whereNotNull('bridging_sep.no_rawat');
            })
            ->where('status_lanjut', 'Ranap')
            ->whereHas('kamarInap', function ($query) {
                $query->where('stts_pulang', '!=', 'Pindah Kamar');
            })
            ->whereHas('dokter.spesialis', function ($query) {
                $query->whereIn('kd_sps', ['S0001', 'S0003']);
            })
            ->groupBy('reg_periksa.no_rawat')
            ->orderBy('tgl_registrasi', 'ASC');
        // ->whereBetween('tgl_registrasi', [$tanggal->startOfMonth()->toDateString(), $tanggal->lastOfMonth()->toDateString()])->get();

        if ($request->ajax()) {
            if ($request->tgl_pertama && $request->tgl_kedua) {
                $data->where('stts_daftar', 'like', '%' . $request->daftar . '%')
                    ->whereBetween('tgl_registrasi', [$request->tgl_pertama, $request->tgl_kedua])
                    ->whereHas('dokter', function ($query) use ($request) {
                        $query->where('kd_sps', 'like', '%' . $request->poli . '%');
                    })
                    ->whereHas('dokter', function ($query) use ($request) {
                        $query->where('kd_dokter', 'like', '%' . $request->kd_dokter . '%');
                    })
                    ->whereHas('penjab', function ($query) use ($request) {
                        $query->where('png_jawab', 'like', '%' . $request->pembiayaan . '%');
                    });
            } else {
                $data->whereBetween('tgl_registrasi', [$tanggal->startOfMonth()->toDateString(), $tanggal->lastOfMonth()->toDateString()])->get();
            }
        }

        return DataTables::of($data)
            ->filter(function ($query) use ($request) {
                if ($request->has('search') && $request->get('search')['value']) {
                    return $query->whereHas('pasien', function ($query) use ($request) {
                        $query->where('nm_pasien', 'like', '%' . $request->get('search')['value'] . '%');
                    });
                }
            })
            ->editColumn('tgl_registrasi', function ($data) use ($tanggal) {
                return $tanggal->parse($data->tgl_registrasi)->translatedFormat('d F Y');
            })
            ->editColumn('nm_pasien', function ($data) use ($tanggal) {
                return $data->pasien->nm_pasien;
            })
            ->editColumn('no_tlp', function ($data) {
                return $data->pasien->no_tlp;
            })
            ->editColumn('jk', function ($data) {
                return $data->pasien->jk;
            })
            ->editColumn('umurdaftar', function ($data) {
                return $data->umurdaftar . " " . $data->sttsumur;
            })
            ->editColumn('tgl_lahir', function ($data) use ($tanggal) {
                return $tanggal->parse($data->pasien->tgl_lahir)->translatedFormat('d F Y');
            })
            ->editColumn('stts_daftar', function ($data) {
                if ($data->stts_daftar == 'Lama') {
                    return '<span class="badge badge-primary">Lama</span>';
                } else {
                    return '<span class="badge badge-warning">Baru</span>';
                }
            })
            ->editColumn('alamat', function ($data) {
                return $data->pasien->alamat . ", "
                . $data->pasien->kelurahan->nm_kel . ", "
                . $data->pasien->kecamatan->nm_kec . ", "
                . $data->pasien->kabupaten->nm_kab;
            })
            ->editColumn('nm_dokter', function ($data) {
                return $data->dokter->nm_dokter;
            })
            ->editColumn('nm_sps', function ($data) {
                return $data->dokter->spesialis->nm_sps;
            })
            ->editColumn('pembiayaan', function ($data) {
                return $data->penjab->png_jawab;
            })
            ->editColumn('tgl_masuk', function ($data) use ($tanggal) {
                return $tanggal->parse($data->kamarInap->tgl_masuk)->translatedFormat('d F Y');
            })
            ->editColumn('diagnosa_awal', function ($data) {
                return $data->kamarInap->diagnosa_awal;
            })
            ->editColumn('diagnosa_akhir', function ($data) {
                return $data->kamarInap->diagnosa_akhir;
            })
            ->editColumn('tgl_keluar', function ($data) use ($tanggal) {
                if ($data->kamarInap->tgl_keluar == '0000-00-00') {
                    return '<span class="badge badge-warning">Belum Pulang</span>';
                } else {
                    return $tanggal->parse($data->kamarInap->tgl_keluar)->translatedFormat('d F Y');
                }
            })
            ->rawColumns(['tgl_keluar', 'stts_daftar', 'nm_pasien'])
            ->editColumn('kamar', function ($data) {
                return $data->kamarInap->kamar->bangsal->nm_bangsal;
            })->editColumn('tglsep', function ($data) use ($tanggal) {
            $data->tglsep == null ? $tgl_sep = '-' :
            $tgl_sep = $tanggal->parse($data->tglsep)->translatedFormat('d F Y');
            return $tgl_sep;
        })
            ->make(true);
    }

    // view laporan bpjs
    public function laporanBpjs()
    {
        $tanggal = new Carbon('this month');
        return view(
            'dashboard.content.ranap.laporan_kunjungan_ranap',
            [
                'title' => 'Laporan Rawat Inap',
                'bigTitle' => 'Laporan Rawat Inap',
                'month' => $tanggal->now()->monthName,
                'tglAwal' => $tanggal->startOfMonth()->toDateString(),
                'tglSekarang' => $tanggal->now()->toDateString(),
            ]
        );
    }

    // laporan bpjs
    public function jsonRanap(Request $request)
    {
        $tanggal = new Carbon('this month');

        if ($request->ajax()) {
            $data = RegPeriksa::select('*')
                ->where('status_lanjut', 'Ranap')
                ->whereHas('penjab', function ($query) {
                    $query->where('png_jawab', 'like', '%bpjs%');
                })
                ->whereHas('diagnosaPasien', function ($query) {
                    $query->where('prioritas', 1);
                })
                ->whereHas('kamarInap', function ($query) {
                    $query->where('stts_pulang', '!=', 'Pindah Kamar');
                })
                ->whereHas('dokter.spesialis', function ($query) {
                    $query->whereIn('kd_sps', ['S0001', 'S0003']);
                })
                ->groupBy('no_rawat')
                ->orderBy('tgl_registrasi', 'ASC');
            if ($request->tgl_pertama && $request->tgl_kedua) {
                $data->whereBetween('tgl_registrasi', [$request->tgl_pertama, $request->tgl_kedua]);
            } else {
                $data->whereBetween('tgl_registrasi', [$tanggal->startOfMonth()->toDateString(), $tanggal->lastOfMonth()->toDateString()])->get();
            }

            // if ($request->spesialis) {
            //     $data->where('dokter.spesialis', '%' . $request->spesialis . '%');
            // } else {
            //     $data->whereBetween('tgl_registrasi', [$tanggal->startOfMonth()->toDateString(), $tanggal->lastOfMonth()->toDateString()])->get();
            // }
        }

        return DataTables::of($data)
            ->editColumn('tgl_registrasi', function ($data) use ($tanggal) {
                return $tanggal->parse($data->tgl_registrasi)->translatedFormat('d F Y');
            })
            ->editColumn('no_ktp', function ($data) {
                return $data->pasien->no_ktp;
            })
            ->editColumn('nm_pasien', function ($data) {
                return $data->pasien->nm_pasien;
            })
            ->editColumn('no_peserta', function ($data) {
                return $data->pasien->no_peserta;
            })
            ->editColumn('no_tlp', function ($data) {
                return $data->pasien->no_tlp;
            })
            ->editColumn('alamat', function ($data) {
                return $data->pasien->alamat . ", "
                . $data->pasien->kelurahan->nm_kel . ", "
                . $data->pasien->kecamatan->nm_kec . ", "
                . $data->pasien->kabupaten->nm_kab;
            })
            ->editColumn('nm_dokter', function ($data) {
                return $data->dokter->nm_dokter;
            })
            ->editColumn('nm_sps', function ($data) {
                return $data->dokter->spesialis->nm_sps;
            })
            ->editColumn('tgl_masuk', function ($data) use ($tanggal) {
                return $tanggal->parse($data->kamarInap->tgl_masuk)->translatedFormat('d F Y');
            })
            ->editColumn('tgl_keluar', function ($data) use ($tanggal) {
                if ($data->kamarInap->tgl_keluar == '0000-00-00') {
                    return '<span class="badge badge-warning">Belum Pulang</span>';
                } else {
                    return $tanggal->parse($data->kamarInap->tgl_keluar)->translatedFormat('d F Y');
                }
            })
            ->editColumn('diagnosa', function ($data) {
                if ($data->diagnosaPasien) {
                    return $data->diagnosaPasien->kd_penyakit . ' - ' . $data->diagnosaPasien->penyakit->nm_penyakit;
                } else {
                    return '-';
                }
            })
            ->editColumn('kamar', function ($data) {
                return $data->kamarInap->kd_kamar;
            })
            ->editColumn('kelas', function ($data) {
                if ($data->bridgingSep) {
                    return 'Kelas ' . $data->bridgingSep->klsrawat;
                } else {
                    return '-';
                }
            })
            ->rawColumns(['tgl_keluar'])
            ->make(true);
    }

    // status bayar pasien bpjs dan umum
    public function jsonStatusBayar(Request $request)
    {

        $tanggal = new Carbon('this month');
        $tahun = $request->tahun ? $request->tahun : date('Y');
        if ($request->ajax()) {
            for ($i = 1; $i <= 12; $i++) {
                $query = RegPeriksa::select(DB::raw('count(*) as jumlah'))
                    ->whereYear('tgl_registrasi', $tahun)
                    ->whereMonth('tgl_registrasi', $i)
                    ->where('status_lanjut', 'Ranap')
                    ->whereHas('kamarInap', function ($query) {
                        $query->where('stts_pulang', '!=', 'Pindah Kamar');
                    })
                    ->where('stts', '!=', 'Batal')
                    ->whereHas('penjab', function ($query) {
                        $query->whereIn('kd_pj', ['A01', 'A05', 'A03']);
                    })
                    ->groupBy('kd_pj')
                    ->get()
                    ->pluck('jumlah');

                $bpjsNonPBI = empty($query[0]) ? 0 : $query[0];
                $bpjsPBI = empty($query[2]) ? 0 : $query[2];
                $umum = empty($query[1]) ? 0 : $query[1];

                $jmlBpjs = $bpjsNonPBI + $bpjsPBI;
                $jmlUmum = $umum;
                $jmlTotal = $jmlUmum + $jmlBpjs;

                $persenBpjs = 'aaa';

                $indexBulan = $tanggal->startOfMonth()->month($i)->monthName;

                $data["$indexBulan"] = (object) [
                    'bulan' => $indexBulan . " " . $tahun,
                    'bpjs' => $jmlBpjs,
                    'umum' => $jmlUmum,
                    'jumlah' => $jmlTotal,
                ];
            }
            return DataTables::of($data)->make(true);
        }
    }

    public function jsonpPembiayaan(Request $request)
    {

        $tahun = $request->tahun ? $request->tahun : date('Y');

        $records = RegPeriksa::select(
            ['no_rawat', 'kd_dokter', 'kd_pj', 'status_lanjut', 'stts', 'tgl_registrasi', 'umurdaftar', 'sttsumur']
         )->with(['penjab' => function($q){
            return $q->select('kd_pj', 'png_jawab');
         }, 'dokter' => function($q){
            return $q->select('kd_dokter', 'nm_dokter');
         }, 'spesialis', ])
            ->whereYear('tgl_registrasi', $tahun)
            ->where('status_lanjut', 'Ranap')
            ->whereHas('kamarInap', function ($query) {
                $query->where('stts_pulang', '!=', 'Pindah Kamar');
            })
            ->where('stts', '!=', 'Batal')
            ->whereHas('dokter', function ($q) {
                $q->whereIn('dokter.kd_sps', ['S0001', 'S0003']);
            })->orderBy('tgl_registrasi', 'asc')
            ->get();
$groupedByMonth = $records->groupBy(function ($item) {
    return Carbon::parse($item['tgl_registrasi'])->translatedFormat('F'); // Nama bulan
})->map(function ($items) {
    return $items->groupBy(function ($item) {
        // Menentukan kategori BAYI, ANAK, KANDUNGAN
        if ($item['sttsumur'] === "Hr" && $item['umurdaftar'] < 30) {
            return "bayi";
        }
        return str_contains($item['spesialis']['nm_sps'], "ANAK") ? "anak" : "kandungan";
    })->map(function ($group) {
        // Mengelompokkan berdasarkan jenis BPJS
        $grouped = $group->groupBy(function ($item) {
            return str_contains($item['penjab']['png_jawab'], "BPJS") ? "BPJS" : $item['penjab']['png_jawab'];
        })->map->count();

        // Menambahkan total per kategori
        $grouped['total'] = $grouped->sum();  // Total untuk kategori ini

        return $grouped;
    });
});

// Menyiapkan data untuk DataTables
$formattedData = $groupedByMonth->map(function ($monthsData, $month) use ($tahun) {
    return [
        'bulan' => $month. " " . $tahun,  // Kolom bulan
        'data' => $monthsData  // Menyimpan data spesialis dan BPJS
    ];
});

return DataTables::of($formattedData)->make(true);
    }
    public function jsonGenderRanap(Request $request)
    {

        $tanggal = new Carbon('this month');
        $tahun = $request->tahun ? $request->tahun : date('Y');
        if ($request->ajax()) {
            for ($i = 1; $i <= 12; $i++) {
                $laki = RegPeriksa::whereYear('tgl_registrasi', $tahun)
                    ->whereMonth('tgl_registrasi', $i)
                    ->where('status_lanjut', 'Ranap')
                    ->whereHas('kamarInap', function ($query) {
                        $query->where('stts_pulang', '!=', 'Pindah Kamar');
                    })
                    ->where('stts', '!=', 'Batal')
                    ->whereHas('pasien', function ($q) {
                        $q->where('jk', 'L');
                    })->count();
                $perempuan = RegPeriksa::whereYear('tgl_registrasi', $tahun)
                    ->whereMonth('tgl_registrasi', $i)
                    ->where('status_lanjut', 'Ranap')
                    ->whereHas('kamarInap', function ($query) {
                        $query->where('stts_pulang', '!=', 'Pindah Kamar');
                    })
                    ->where('stts', '!=', 'Batal')
                    ->whereHas('pasien', function ($q) {
                        $q->where('jk', 'P');
                    })->count();

                $jmlTotal = $laki + $perempuan;

                $indexBulan = $tanggal->startOfMonth()->month($i)->monthName;

                $data["$indexBulan"] = (object) [
                    'bulan' => $indexBulan . " " . $tahun,
                    'laki' => $laki,
                    'perempuan' => $perempuan,
                    'jumlah' => $jmlTotal,
                ];
            }
            return DataTables::of($data)->make(true);
        }
    }

    public function jsonVisitDokter(Request $request)
    {
        $visit = new VisitDokterService();
        $tanggal = new Carbon('this month');
        $tahun = $request->tahun;
        $tahun = $request->tahun ? $request->tahun : date('Y');

        // if ($request->ajax()) {
        for ($j = 1; $j <= 12; $j++) {
            $indexBulan = $tanggal->month($j)->translatedFormat('F');

            $query = RawatInapDr::select(DB::raw('count(*) as jumlah'), 'kd_dokter')
                ->whereYear('tgl_perawatan', $tahun)
                ->whereMonth('tgl_perawatan', $j)
                ->whereHas('jnsPerawatanInap', function ($query) {
                    $query->where('nm_perawatan', 'like', '%visite%');
                })
                ->whereHas('dokter', function ($query) {
                    $query->whereIn('kd_sps', ['S0001', 'S0003']);
                })
                ->with([
                    'dokter' => function ($query) {
                        return $query->select(['kd_dokter', 'nm_dokter']);
                    },
                ])
                ->groupBy('kd_dokter')
                ->with('dokter')
                ->get()
                ->pluck('jumlah', 'dokter.nm_dokter');

            $data[$indexBulan] = $query;
        }
        return response()->json($data);
    }

    public function getCpptVisitDokter(Request $request)
    {
        $visit = new VisitDokterService();
        $dokter = $request->dokter;
        $data = $visit->getVisitData($request);
        return DataTables::of($data)->make(true);
    }

    public function viewVisitDokter()
    {
        $tanggal = new Carbon('this month');
        $sekarang = $tanggal->now();
        $awalBulan = $tanggal->startOfMonth();

        return view('dashboard.content.ranap.list_visit_dokter', [
            'title' => 'Data Visit Dokter',
            'bigTitle' => 'Visit Dokter',
            'month' => 'Per Tanggal : ' . $sekarang->translatedFormat('d F Y'),
            'tglSekarang' => $sekarang->toDateString(),
            'tglAwal' => $awalBulan->toDateString(),

        ]);
    }

    public function viewTransfusi(Request $request)
    {
        $tanggal = new Carbon('this month');
        $sekarang = $tanggal->now();
        $awalBulan = $tanggal->startOfMonth();

        return view('dashboard.content.ranap.list_pasien_transfusi', [
            'title' => 'Transfusi Pasien',
            'bigTitle' => 'Transfusi Pasien',
            'month' => 'Per Tanggal : ' . $sekarang->translatedFormat('d F Y'),
            'tglSekarang' => $sekarang->toDateString(),
            'tglAwal' => $awalBulan->toDateString(),

        ]);
    }

    // pasien rawat inap transfusi
    public function jsonTransfusi(Request $request)
    {
        $tanggal = new Carbon('this month');

        $tgl_pertama = $request->tgl_pertama;
        $tgl_kedua = $request->tgl_kedua;
        $dokter = $request->dokter;

        $data = RawatInapDr::select('*', DB::raw('count(*) as jumlah, sum(biaya_rawat) as total_biaya', ))
            ->whereHas('jnsPerawatanInap', function ($q) {
                $q->where('nm_perawatan', 'like', '%transfusi%');
            })
            ->with('dokter', 'kamarInap.kamar.bangsal')
            ->groupBy('no_rawat');

        if ($request->ajax()) {
            // filter tanggal
            $tgl_pertama && $tgl_kedua ?
            $data->whereBetween('tgl_perawatan', [$tgl_pertama, $tgl_kedua])->get() :
            $data->whereYear('tgl_perawatan', $tanggal->year)
                ->whereMonth('tgl_perawatan', $tanggal->month)->get();
            // filter dokter
            if ($dokter) {
                $data->whereHas('dokter', function ($q) use ($dokter) {
                    $q->where('kd_dokter', $dokter);
                })->get();
            }
        }

        return DataTables::of($data)
            ->editColumn('tgl_perawatan', function ($data) use ($tanggal) {
                return $tanggal->parse($data->tgl_perawatan)->translatedFormat('d F Y');
            })
            ->editColumn('nm_pasien', function ($data) {
                return $data->regPeriksa->pasien->nm_pasien . ' (' . $data->regPeriksa->no_rkm_medis . ')';
            })
            ->editColumn('nm_perawatan', function ($data) {
                return $data->jnsPerawatanInap->nm_perawatan;
            })
            ->editColumn('dokter', function ($data) {
                return $data->dokter->nm_dokter;
            })
            ->editColumn('spesialis', function ($data) {
                return $data->dokter->spesialis->nm_sps;
            })
            ->editColumn('kamar', function ($data) {
                return $data->kamarInap->kamar->bangsal->nm_bangsal;
            })
            ->make(true);
    }

    public function getRekapTrasnfusi(Request $request)
    {
        $tanggal = new Carbon('this month');
        $tahun = $request->tahun ? $request->tahun : $tanggal->year;
        for ($i = 1; $i <= 12; $i++) {
            $indexBulan = $tanggal->month($i)->translatedFormat('F');
            $q = RawatInapDr::whereYear('tgl_perawatan', $tahun)
                ->whereMonth('tgl_perawatan', $i)
                ->whereHas('jnsPerawatanInap', function ($q) {
                    $q->where('nm_perawatan', 'like', '%transfusi%');
                })->count();
            $data[] = [
                'bulan' => $indexBulan . ' ' . $tahun,
                'jumlah' => $q,
            ];
        }

        return $data;
    }

    public function jsonRekapTransfusi(Request $request)
    {
        $dataRekap = $this->getRekapTrasnfusi($request);
        return DataTables::of($dataRekap)->make(true);
    }

    public function diagramTransfusi(Request $request)
    {
        return $this->getRekapTrasnfusi($request);
    }
}
