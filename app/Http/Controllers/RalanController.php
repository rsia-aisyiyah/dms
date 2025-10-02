<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\RegPeriksa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class RalanController extends Controller
{
    private $tanggal;

    public function __construct()
    {
        $this->tanggal = new Carbon('this month');
    }

    public function index()
    {
        $tanggal = new Carbon('this month');
        return view(
            'dashboard.content.ralan.list_status_pasien',
            [
                'title' => 'Kunjungan Rawat Jalan',
                'bigTitle' => 'Kunjungan Rawat Jalan',
                'month' => Carbon::now()->monthName,
                'tanggal' => 'Per Tanggal : ' . $tanggal->now()->translatedFormat('d F Y'),
                'tglAwal' => $tanggal->startOfMonth()->toDateString(),
                'tglSekarang' => $tanggal->now()->toDateString(),
            ]
        );
    }
    public function ambilKandungan()
    {
        $tanggal = new Carbon('this month');
        return view(
            'dashboard.content.ralan.list_kandungan',
            [
                'title' => 'Kunjungan Rawat Jalan',
                'bigTitle' => 'Kunjungan Rawat Jalan',
                'month' => Carbon::now()->monthName,
                'tanggal' => 'Per Tanggal : ' . $tanggal->now()->translatedFormat('d F Y'),
                'tglAwal' => $tanggal->startOfMonth()->toDateString(),
                'tglSekarang' => $tanggal->now()->toDateString(),
            ]
        );
    }

    public function json(Request $request)
    {
        $data = '';
        $tanggal = new Carbon('this month');
        $data = RegPeriksa::where('stts', '!=', 'Batal')
            ->orderBy('tgl_registrasi', 'asc')
            ->where('status_lanjut', 'Ralan')
            ->whereHas('dokter', function ($query) {
                $query->whereIn('kd_sps', ['S0001', 'S0003', 'S0005']);
            });
        if ($request->ajax()) {
            if ($request->tgl_pertama && $request->tgl_kedua) {
                $data->where('stts_daftar', 'like', '%' . $request->daftar . '%')
                    ->whereBetween('tgl_registrasi', [$request->tgl_pertama, $request->tgl_kedua])
                    ->whereHas('dokter', function ($query) use ($request) {
                        $query->where('kd_sps', 'like', '%' . $request->poli . '%');
                    })
                    ->whereHas('penjab', function ($query) use ($request) {
                        $query->where('png_jawab', 'like', '%' . $request->pembiayaan . '%');
                    })
                    ->whereHas('dokter', function ($query) use ($request) {
                        $query->where('kd_dokter', 'like', '%' . $request->kd_dokter . '%');
                    });
            } else {
                $data->whereMonth('tgl_registrasi', date('m'))
                    ->whereYear('tgl_registrasi', date('Y'));
            }
        }

        return DataTables::of($data)
            ->filter(function ($query) use ($request) {
                if ($request->has('search') && !is_null($request->get('search')['value'])) {
                    return $query->whereHas('pasien', function ($query) use ($request) {
                        $query->where('nm_pasien', 'like', '%' . $request->get('search')['value'] . '%');
                    });
                }
            })
            ->editColumn('kd_sps', function ($data) {
                return $data->dokter->kd_sps;
            })
            ->editColumn('tgl_registrasi', function ($data) {
                return Carbon::parse($data->tgl_registrasi)->translatedFormat('d F Y');
            })
            ->editColumn('nm_pasien', function ($data) {
                return $data->pasien->nm_pasien . " ( No. RM " . $data->no_rkm_medis . ")";
            })
            ->editColumn('tgl_lahir', function ($data) {
                return Carbon::parse($data->pasien->tgl_lahir)->translatedFormat('d F Y');
            })
            ->editColumn('alamat', function ($data) {
                return $data->pasien->alamat . ", "
                    . $data->pasien->kelurahan->nm_kel . ", "
                    . $data->pasien->kecamatan->nm_kec . ", "
                    . $data->pasien->kabupaten->nm_kab;
            })
            ->editColumn('stts_daftar', function ($data) {
                if ($data->stts_daftar == 'Lama') {
                    return '<span class="badge badge-primary">Lama</span>';
                } else {
                    return '<span class="badge badge-warning">Baru</span>';
                }
            })
            ->editColumn('png_jawab', function ($data) {
                return $data->penjab->png_jawab;
            })
            ->editColumn('no_tlp', function ($data) {
                return $data->pasien->no_tlp;
            })
            ->editColumn('nm_dokter', function ($data) {
                return $data->dokter->nm_dokter;
            })
            ->rawColumns(['stts_daftar'])
            ->make(true);
    }

    public function viewLaporanBpjs()
    {
        $tanggal = new Carbon('this month');
        return view(
            'dashboard.content.ralan.laporan_kunjungan_ralan',
            [
                'title' => 'Kunjungan Rawat Jalan',
                'bigTitle' => 'Kunjungan Rawat Jalan',
                'month' => Carbon::now()->monthName,
                'tglAwal' => $tanggal->startOfMonth()->toDateString(),
                'tglSekarang' => $tanggal->now()->toDateString(),
            ]
        );
    }

    public function jsonLaporanBpjs(Request $request)
    {
        $data = '';
        $tanggal = new Carbon('this month');

        if ($request->ajax()) {
            if ($request->tgl_pertama && $request->tgl_kedua) {
                $data = RegPeriksa::select('no_rawat', 'tgl_registrasi', 'kd_dokter', 'no_rkm_medis')
                    ->whereBetween('tgl_registrasi', [$request->tgl_pertama, $request->tgl_kedua])
                    ->where('status_lanjut', 'Ralan')
                    ->where('stts', '!=', 'Batal')
                    ->whereHas('penjab', function ($query) {
                        $query->where('png_jawab', 'like', '%bpjs%');
                    })
                    ->whereHas('diagnosaPasien', function ($query) {
                        $query->where('prioritas', 1);
                    })
                    ->whereHas('dokter.spesialis', function ($query) use ($request) {
                        $query->where('nm_sps', 'like', '%' . $request->poli . '%');
                    })
                    ->groupBy('no_rawat');
            } else {
                $data = RegPeriksa::select('no_rawat', 'tgl_registrasi', 'kd_dokter', 'no_rkm_medis')
                    ->whereBetween('tgl_registrasi', [$tanggal->startOfMonth()->toDateString(), $tanggal->lastOfMonth()->toDateString()])
                    ->where('status_lanjut', 'Ralan')
                    ->where('stts', '!=', 'Batal')
                    ->whereHas('penjab', function ($query) {
                        $query->where('png_jawab', 'like', '%bpjs%');
                    })
                    ->whereHas('diagnosaPasien', function ($query) {
                        $query->where('prioritas', 1);
                    })
                    ->whereHas('dokter.spesialis', function ($query) {
                        $query->whereIn('kd_sps', ['S0001', 'S0003', 'S0005']);
                    })
                    ->groupBy('no_rawat');
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
            ->editColumn('diagnosa', function ($data) {
                return $data->diagnosaPasien->kd_penyakit;
            })
            ->make(true);
    }

    public function jsonStatusBayar(Request $request)
    {
        $tanggal = new Carbon('this month');
        $tahun = $request->tahun ? $request->tahun : date('Y');

        // if ($request->ajax()) {
        for ($i = 1; $i <= 12; $i++) {
            $query = RegPeriksa::select(DB::raw('count(*) as jumlah'))
                ->whereYear('tgl_registrasi', $tahun)
                ->whereMonth('tgl_registrasi', $i)
                ->where('status_lanjut', 'Ralan')
                ->where('stts', '!=', 'Batal')
                ->whereHas('dokter', function ($query) {
                    $query->whereIn('kd_sps', ['S0001', 'S0003', 'S0005']);
                })
                ->groupBy('kd_pj')
                ->get()
                ->pluck('jumlah');

            $bpjsNonPBI = empty($query[0]) ? 0 : $query[0];
            $bpjsPBI = empty($query[2]) ? 0 : $query[2];
            $umum = empty($query[1]) ? 0 : $query[1];

            $jmlBpjs = $bpjsNonPBI + $bpjsPBI;
            $jmlUmum = $umum;
            $indexBulan = $tanggal->startOfMonth()->month($i)->monthName;

            $data["$indexBulan"] = (object) [
                'bulan' => $indexBulan . " " . $tahun,
                'bpjs' => $jmlBpjs,
                'umum' => $jmlUmum,
            ];
        }
        // }

        return DataTables::of($data)->make(true);
    }
    public function jsonStatusDaftar(Request $request)
    {
        $tanggal = new Carbon('this month');
        $tahun = $request->tahun ? $request->tahun : date('Y');

        if ($request->ajax()) {
            for ($i = 1; $i <= 12; $i++) {
                $query = RegPeriksa::select('stts_daftar', DB::raw('count(*) as jumlah'))
                    ->whereYear('tgl_registrasi', $tahun)
                    ->whereMonth('tgl_registrasi', $i)
                    ->where('status_lanjut', 'Ralan')
                    ->where('stts', '!=', 'Batal')
                    ->whereHas('dokter', function ($query) {
                    $query->whereIn('kd_sps', ['S0001', 'S0003', 'S0005']);
                })
                    ->groupBy('stts_daftar')
                    ->get()
                    ->pluck('jumlah', 'stts_daftar');

                // Mengambil data secara aman, jika tidak ada maka nilainya 0
                $lama = $query['Lama'] ?? 0;
            $baru = $query['Baru'] ?? 0;
                $indexBulan = $tanggal->startOfMonth()->month($i)->monthName;
                $data["$indexBulan"] = (object) [
                    'bulan' => $indexBulan . " " . $tahun,
                    'lama' => $lama,
                    'baru' => $baru,
                ];
            }
        }

        return DataTables::of($data)->make(true);
    }
    public function jsonPoli(Request $request)
    {
        $tahun = $request->tahun ? $request->tahun : date('Y');

        $records = RegPeriksa::with('penjab', 'dokter')
            ->whereYear('tgl_registrasi', $tahun)
            ->where('status_lanjut', 'Ralan')
            ->where('stts', '!=', 'Batal')
            ->whereHas('dokter', function ($q) {
                $q->whereIn('dokter.kd_sps', ['S0001', 'S0003', 'S0005']);
            })
            ->get();

        $data = $records->groupBy(function ($item) {
            return Carbon::parse($item->tgl_registrasi)->format('n'); // Group by numeric month (1, 2, ..., 12)
        })->map(function ($monthRecords, $monthNumber) use ($tahun) {
            $groupBySpecialization = $monthRecords->groupBy(function ($item) {
                return $item->dokter ? $item->dokter->kd_sps : '';
            })->map(function ($specializationRecords) {
                return $specializationRecords->groupBy('penjab.png_jawab')
                    ->map(function ($item) {
                        return $item->count();
                    })->mapWithKeys(function ($item, $key) use (&$bpjsTotal) {
                        if (stripos($key, 'BPJS') !== false) {
                            $bpjsTotal += $item; // Accumulate BPJS counts
                            return [];
                        }
                        return [strtolower($key) => $item];
                    })
                    ->put('bpjs', $bpjsTotal ?? 0) // Add the accumulated BPJS total
                    ->put('total', $specializationRecords->count());
            });

            $monthName = Carbon::createFromFormat('n', $monthNumber)->format('F') . " " . $tahun;

            return (object) [
                'bulan' => $monthName,
                'obgyn' => $groupBySpecialization['S0001'] ?? null,
                'anak' => $groupBySpecialization['S0003'] ?? null,
                'dalam' => $groupBySpecialization['S0005'] ?? null,
            ];
        });

        return DataTables::of($data)->make(true);
    }

    public function getCountsBySpesialis($tahun, $bulan, $spesialis)
    {
        $data = RegPeriksa::whereYear('tgl_registrasi', $tahun)
            ->whereMonth('tgl_registrasi', $bulan)
            ->whereHas('dokter', function ($q) use ($spesialis) {
                $q->where('kd_sps', $spesialis);
            })
            ->where('status_lanjut', 'Ralan')
            ->where('stts', '!=', 'Batal')
            ->get();

        return collect($data)->groupBy('penjab.png_jawab')
            ->map(function ($item) {
                return $item->count();
            })
            ->mapWithKeys(function ($item, $key) {
                return strpos($key, 'BPJS') !== false ? ['bpjs' => $item] : [strtolower($key) => $item];
            })->put('total', $data->count());
    }

    public function jsonDokterAnak(Request $request)
    {

        // return 'wlwlwlwl';
        $tanggal = new Carbon('this month');
        $tahun = $request->tahun ? $request->tahun : date('Y');

        // if ($request->ajax()) {
        for ($i = 1; $i <= 12; $i++) {
            $query = RegPeriksa::select(DB::raw('count(*) as jumlah'))
                ->whereYear('tgl_registrasi', $tahun)
                ->whereMonth('tgl_registrasi', $i)
                ->whereHas('dokter', function ($q) {
                    $q->where('kd_sps', 'S0003');
                })
                ->where('status_lanjut', 'Ralan')
                ->where('stts', '!=', 'Batal')
                ->whereIn('kd_poli', ['P003', 'P008'])
                ->groupBy('kd_dokter')
                ->get()
                ->pluck('jumlah');

            $indexBulan = $tanggal->startOfMonth()->month($i)->monthName;

            $query[0] = empty($query[0]) ? 0 : $query[0];
            $query[1] = empty($query[1]) ? 0 : $query[1];

            $jmlAnak1 = $query[0];
            $jmlAnak2 = $query[1];
            $total = $jmlAnak1 + $jmlAnak2;
            $data["$indexBulan"] = (object) [
                'bulan' => $indexBulan . " " . $tahun,
                'anak1' => $jmlAnak1,
                'anak2' => $jmlAnak2,
                'total' => $total,
            ];
        }
        // }
        // return DataTables::of($data)->make(true);
        return $data;

    }

    public function jsonDokterObgyn(Request $request)
    {
        $tanggal = new Carbon('this month');
        $tahun = $request->tahun ? $request->tahun : date('Y');

        if ($request->ajax()) {
            for ($i = 1; $i <= 12; $i++) {
                $query = RegPeriksa::select(DB::raw('count(*) as jumlah'))
                    ->whereYear('tgl_registrasi', $tahun)
                    ->whereMonth('tgl_registrasi', $i)
                    ->whereHas('dokter', function ($q) {
                        $q->where('kd_sps', 'S0001');
                    })
                    ->where('status_lanjut', 'Ralan')
                    ->where('stts', '!=', 'Batal')
                    ->whereIn('kd_poli', ['P001', 'P007', 'P009'])
                    ->groupBy('kd_dokter')
                    ->get()
                    ->pluck('jumlah');

                $indexBulan = $tanggal->startOfMonth()->month($i)->monthName;

                $query[0] = empty($query[0]) ? 0 : $query[0];
                $query[1] = empty($query[1]) ? 0 : $query[1];

                $jmlObgyn1 = $query[0];
                $jmlObgyn2 = $query[1];
                $total = $jmlObgyn1 + $jmlObgyn2;
                $data["$indexBulan"] = (object) [
                    'bulan' => $indexBulan . " " . $tahun,
                    'obgyn1' => $jmlObgyn1,
                    'obgyn2' => $jmlObgyn2,
                    'total' => $total,
                ];
            }
        }
        return DataTables::of($data)->make(true);
    }
    public function jsonPoliJk(Request $request)
    {
        $tanggal = new Carbon('this month');
        $tahun = $request->tahun ? $request->tahun : date('Y');

        // if ($request->ajax()) {
        for ($i = 1; $i <= 12; $i++) {
            $laki = RegPeriksa::whereYear('tgl_registrasi', $tahun)
                ->whereMonth('tgl_registrasi', $i)
                ->whereHas('pasien', function ($q) {
                    $q->where('jk', 'L');
                })
                ->where('status_lanjut', 'Ralan')
                ->where('stts', '!=', 'Batal')
                ->get()
                ->count();
            $perempuan = RegPeriksa::whereYear('tgl_registrasi', $tahun)
                ->whereMonth('tgl_registrasi', $i)
                ->whereHas('pasien', function ($q) {
                    $q->where('jk', 'P');
                })
                ->where('status_lanjut', 'Ralan')
                ->where('stts', '!=', 'Batal')
                ->get()
                ->count();

            $indexBulan = $tanggal->startOfMonth()->month($i)->monthName;

            $laki = empty($laki) ? 0 : $laki;
            $perempuan = empty($laki) ? 0 : $perempuan;

            $total = $laki + $perempuan;
            $data["$indexBulan"] = (object) [
                'bulan' => $indexBulan . " " . $tahun,
                'laki' => $laki,
                'perempuan' => $perempuan,
                'total' => $total,
            ];
        }
        // }
        return DataTables::of($data)->make(true);
    }
    public function diagramRalanPoli(Request $request)
    {
        if ($request->tahun) {
            $tahun = $request->tahun;
        } else {
            $tahun = date('Y');
        }

        for ($i = 1; $i <= 12; $i++) {
            $ralanAnakTahunan = RegPeriksa::select(DB::raw('count(*) as jumlah'))
                ->ralanTahunan($tahun, $i)
                ->whereHas('dokter', function ($q) {
                    $q->where('kd_sps', 'S0003');
                })->count();

            $ralanObgynTahunan = RegPeriksa::select(DB::raw('count(*) as jumlah'))
                ->ralanTahunan($tahun, $i)
                ->whereHas('dokter', function ($q) {
                    $q->where('kd_sps', 'S0001');
                })->count();
            $anak[] = $ralanAnakTahunan;
            $obgyn[] = $ralanObgynTahunan;
        }
        return response()->json([
            'anak' => $anak,
            'obgyn' => $obgyn,
        ]);
    }

    public function sepRalan()
    {
        $tanggal = new Carbon('this month');
        return view(
            'dashboard.content.ralan.list_sep_ralan',
            [
                'title' => 'List SEP Rawat Jalan',
                'bigTitle' => 'Monitoring SEP',
                'month' => $tanggal->now()->translatedFormat('d F Y'),
                'tanggal' => 'Per Tanggal : ' . $tanggal->now()->translatedFormat('d F Y'),
                'tglAwal' => $tanggal->startOfMonth()->toDateString(),
                'tglSekarang' => $tanggal->now()->toDateString(),
            ]
        );
    }
    //monitoring SEP
    public function jsonSepRalan(Request $request)
    {
        $tanggal = new Carbon('this month');
        $tgl_pertama = $request->tgl_pertama;
        $tgl_kedua = $request->tgl_kedua;
        $poli = $request->poli;

        $data = RegPeriksa::getSepRalan()->orderBy('kd_poli', 'ASC');

        $dataRanap = RegPeriksa::getSepRanap();
        $row = [];

        if ($request->ajax()) {

            //filter tangal

            if ($tgl_pertama && $tgl_kedua) {
                $data->whereBetween('tgl_registrasi', [$tgl_pertama, $tgl_kedua])
                    ->with('bridgingSep');
                $dataRanap->whereBetween('tgl_registrasi', [$tgl_pertama, $tgl_kedua])
                    ->with('bridgingSep');
            } else {
                $data->where('tgl_registrasi', $tanggal->now()->format('Y-m-d'))
                    ->with('bridgingSep');
                $dataRanap->where('tgl_registrasi', $tanggal->now()->format('Y-m-d'))
                    ->with('bridgingSep');
            }

            if ($poli) {
                $data->whereHas('poli', function ($query) use ($poli) {
                    $query->where('nm_poli', 'like', '%' . $poli . '%');
                });
            }

            if ($request->has('search') && $request->get('search')['value']) {
                $data->whereHas('pasien', function ($query) use ($request) {
                    $query->where('nm_pasien', 'like', '%' . $request->get('search')['value'] . '%');
                });
            }

            $collection2 = collect($dataRanap->get());
            foreach ($data->get() as $d) {

                if ($d->bridgingSep == null) {
                    if ($collection2->where('no_rkm_medis', $d->no_rkm_medis)->first() == null) {
                        $d->bridgingSep = 'Belum Cetak';
                    } else {
                        continue;
                    }
                } else {
                    $d->bridgingSep = $tanggal->parse($d->bridgingSep->tglsep)->translatedFormat('d F Y');
                }

                $row[] = [
                    'no_rawat' => $d->no_rawat,
                    'tgl_registrasi' => $tanggal->parse($d->tgl_registrasi)->translatedFormat('d F Y'),
                    'nm_pasien' => $d->pasien->nm_pasien . ' (' . $d->no_rkm_medis . ')',
                    'poliklinik' => $d->poli->nm_poli,
                    'bridging_sep' => $d->bridgingSep,
                ];
            }

            return DataTables::of($row)
                ->editColumn('bridging_sep', function ($row) {
                    if ($row['bridging_sep'] == "Belum Cetak") {
                        $class = "btn btn-danger";
                    } else {
                        $class = "btn btn-success";
                    }
                    return "<button class='$class'>" . $row['bridging_sep'] . "</button>";
                })
                ->rawColumns(['bridging_sep'])
                ->make(true);
        }
        return redirect('ralan/sep');
    }
}
