<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Operasi;
use Illuminate\Http\Request;
use Yajra\DataTables\Contracts\DataTable;
use Yajra\DataTables\DataTables;

class OperasiController extends Controller
{

    public $carbon;
    public function __construct()
    {
        $this->carbon = new Carbon();
    }
    public function index()
    {

        $tanggal = new Carbon('this month');
        $sekarang = $tanggal->now();
        $awalBulan = $tanggal->startOfMonth();

        $label = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        $dataCaesar = [];
        $dataCuretage = [];

        for ($i = 1; $i <= 12; $i++) {
            $sc = Operasi::whereMonth('tgl_operasi', $i)
                ->whereYear('tgl_operasi', $sekarang->year)
                ->whereHas('paketOperasi', function ($query) {
                    $query->where('nm_perawatan', 'like', '%SC%');
                    $query->orWhere('nm_perawatan', 'like', '%Sectio Caesaria%');
                })->count();
            $curetage = Operasi::whereMonth('tgl_operasi', $i)
                ->whereYear('tgl_operasi', $sekarang->year)
                ->whereHas('paketOperasi', function ($query) {
                    $query->where('nm_perawatan', 'like', '%curetage%');
                })->count();
            $lain = Operasi::whereMonth('tgl_operasi', $i)
                ->whereYear('tgl_operasi', $sekarang->year)
                ->whereHas('paketOperasi', function ($query) {
                    $query->where('nm_perawatan', 'not like', '%curetage%')
                        ->where('nm_perawatan', 'not like', '%sc%')
                        ->where('nm_perawatan', 'not like', '%sectio caesaria%');
                })->count();

            $dataCaesar[] = $sc;
            $dataCuretage[] = $curetage;
            $dataLain[] = $lain;
        }

        return view('dashboard.content.operasi.list_operasi', [
            'title' => 'Data Operasi',
            'bigTitle' => 'Operasi',
            'month' => 'Jadwal Operasi : ' . $awalBulan->translatedFormat('d F Y') . ' s/d ' . $sekarang->translatedFormat('d F Y'),
            'dateNow' => $sekarang->toDateString(),
            'dateStart' => $awalBulan->toDateString(),
            'label' => $label,
            'dataCaesar' => $dataCaesar,
            'dataCuretage' => $dataCuretage,
            'dataLain' => $dataLain,
        ]);
    }

    public function viewSectio()
    {
        return view('dashboard.content.operasi.list_sectio', [
            'title' => 'Data Persalinan Sectio',
            'bigTitle' => 'Operasi',
            'month' => 'Jadwal Operasi : ' . $this->carbon->startOfMonth()->translatedFormat('d F Y') . ' s/d ' . $this->carbon->now()->translatedFormat('d F Y'),
            'dateNow' => $this->carbon->now()->toDateString(),
            'dateStart' => $this->carbon->startOfMonth()->toDateString(),

        ]);
    }
    public function ambilSectio(Request $request)
    {
        $data = Operasi::with(['regPeriksa.pasien', 'regPeriksa.dokter', 'regPeriksa.penjab', 'ranapGabung.askepBayi', 'ranapGabung.rp.pasien', 'paketOperasi', 'askepRanapKebidanan'])->with('kamarInap', function ($query) {
            $query->where('stts_pulang', '!=', 'Pindah Kamar')
                ->where('tgl_keluar', '!=', '0000-00-00');
        })->whereHas('paketOperasi', function ($query) {
            $query->where('nm_perawatan', 'like', '%sc%');
            $query->orWhere('nm_perawatan', 'like', '%sectio%');
        });

        if ($request->ajax()) {
            if ($request->tgl_pertama && $request->tgl_kedua) {
                $data->whereBetween('tgl_operasi', [$request->tgl_pertama . ' 00:00:00', $request->tgl_kedua . ' 00:00:00'])
                    ->whereHas('regPeriksa', function ($query) use ($request) {
                        $query->whereHas('penjab', function ($query) use ($request) {
                            $query->where('png_jawab', 'like', '%' . $request->pembiayaan . '%');
                        });
                    })
                    ->whereHas('dokter', function ($query) use ($request) {
                        if ($request->dokter) {
                            $query->where('kd_dokter', $request->dokter);
                        }
                    });
            } else {
                $data->whereMonth('tgl_operasi', date('m'))->whereYear('tgl_operasi', date('Y'));
            }
        }

        return DataTables::of($data)->make(true);

        // return response()->json($data);

        // return $data;
    }
    public function json(Request $request)
    {
        $data = '';
        $tanggal = new Carbon('this month');
        $sekarang = $tanggal->now();
        $awalBulan = $tanggal->startOfMonth();
        // if ($request->ajax()) {
        if ($request->tgl_pertama || $request->tgl_kedua) {
            $data = Operasi::whereBetween('tgl_operasi', [$request->tgl_pertama . ' 00:00:00', $request->tgl_kedua . ' 23:59:59'])
                ->whereHas('paketOperasi', function ($query) use ($request) {
                    if ($request->operasi == 'sc') {
                        $query->where('nm_perawatan', 'like', '%sc%');
                        $query->orWhere('nm_perawatan', 'like', '%sectio%');
                    } else if ($request->operasi == 'curetage') {
                        $query->where('nm_perawatan', 'like', '%curetage%');
                    } else if ($request->operasi == 'lain') {
                        $query->where('nm_perawatan', 'not like', '%sc%');
                        $query->where('nm_perawatan', 'not like', '%sectio%');
                        $query->where('nm_perawatan', 'not like', '%curetage%');
                    }
                })
                ->with('laporanOperasi')
                ->whereHas('dokter', function ($query) use ($request) {
                    $query->where('nm_dokter', 'like', '%' . $request->dokter . '%');
                })
                ->whereHas('pembiayaan', function ($query) use ($request) {
                    $query->where('png_jawab', 'like', '%' . $request->pembiayaan . '%');
                })
                ->with('kamarInap', function ($query) {
                    $query->where('stts_pulang', '!=', 'Pindah Kamar')
                        ->where('tgl_keluar', '!=', '0000-00-00');
                });
        } else {
            $data = Operasi::with('paketOperasi')
                ->whereBetween('tgl_operasi', [$awalBulan->toDateString() . ' 00:00:00', $sekarang->toDateString() . ' 23:59:59'])
                ->with('laporanOperasi')
                ->with('kamarInap', function ($query) {
                    $query->where('stts_pulang', '!=', 'Pindah Kamar')
                        ->where('tgl_keluar', '!=', '0000-00-00');
                });
        }
        // }
        return DataTables::of($data)
            ->filter(function ($query) use ($request) {
                if ($request->has('search') && $request->get('search')['value']) {
                    return $query->whereHas('regPeriksa.pasien', function ($query) use ($request) {
                        $query->where('nm_pasien', 'like', '%' . $request->get('search')['value'] . '%');
                    });
                }
            })
            ->editColumn('no_rkm_medis', function ($data) {
                return $data->regPeriksa->no_rkm_medis;
            })
            ->editColumn('pasien', function ($data) {
                return $data->regPeriksa->pasien->nm_pasien;
            })
            ->editColumn('tgl_operasi', function ($data) {
                return Carbon::parse($data->tgl_operasi)->translatedFormat('d F Y (H:i:s)');
            })
            ->editColumn('nm_perawatan', function ($data) {
                return $data->paketOperasi->nm_perawatan;
            })
            ->editColumn('kelas', function ($data) {
                if ($data->bridgingSep) {
                    return 'Kelas ' . $data->bridgingSep->klsrawat;
                } else {
                    return '- ';
                }
            })
            ->editColumn('kamar', function ($data) {
                if ($data->kamarInap == null) {
                    return 'Belum Pulang';
                } else {
                    return $data->kamarInap->kd_kamar;
                }
            })
            ->editColumn('lama', function ($data) {
                if ($data->kamarInap) {
                    return $data->kamarInap->lama . ' Hari';
                } else {
                    return '0 Hari';
                }
            })
            ->editColumn('dokter', function ($data) {
                return $data->dokter->nm_dokter;
            })
            ->editColumn('asisten1', function ($data) {
                return $data->asisten1->nama;
            })
            ->editColumn('asisten2', function ($data) {
                return $data->asisten2->nama;
            })
            ->editColumn('dokterAnestesi', function ($data) {
                return $data->dokterAnestesi->nm_dokter;
            })
            ->editColumn('asistenAnestesi', function ($data) {
                return $data->asistenAnestesi->nama;
            })
            ->editColumn('dokterAnak', function ($data) {
                return $data->dokterAnak->nm_dokter;
            })
            ->editColumn('omloop', function ($data) {
                return $data->omloops->nama;
            })
            ->editColumn('pembiayaan', function ($data) {
                return $data->pembiayaan->png_jawab;
            })
            ->editColumn('diagnosa', function ($data) {
                if ($data->kamarInap == null) {
                    return '-';
                } else {
                    $diagnosa = $data->kamarInap->diagnosa_akhir;
                    $arrDiagnosa = explode(', ', $diagnosa);
                    $arrFilter = array_filter($arrDiagnosa);
                    $diagnosaAkhir = "<ul>";
                    foreach ($arrFilter as $d) {
                        $diagnosaAkhir .= "<li>" . $d . ",</li>";
                    }
                    $diagnosaAkhir .= "</ul>";

                    return $diagnosaAkhir;
                }

                // if ($data->kamarInap->diagnosa_akhir != null) {
                //     return $diagnosa = $data->kamarInap->diagnosa_akhir;
                // }
                // $arrDiagnosa = explode(',', $diagnosa);
                // $diagnosaAkhir = "<ul>";
                // foreach ($arrDiagnosa as $d) {
                //     $diagnosaAkhir .= "<li>" . $d . "<li>";
                // }
                // $diagnosaAkhir .= "</ul>";

                // return $diagnosaAkhir;
            })
            ->rawColumns(['diagnosa'])
            ->make(true);
    }

    public function diagram($tahun)
    {
        for ($i = 1; $i <= 12; $i++) {
            $sc = Operasi::whereMonth('tgl_operasi', $i)
                ->whereYear('tgl_operasi', $tahun)
                ->whereHas('paketOperasi', function ($query) {
                    $query->where('nm_perawatan', 'like', '%SC%');
                    $query->orWhere('nm_perawatan', 'like', '%Sectio Caesaria%');
                })->count();
            $curetage = Operasi::whereMonth('tgl_operasi', $i)
                ->whereYear('tgl_operasi', $tahun)
                ->whereHas('paketOperasi', function ($query) {
                    $query->where('nm_perawatan', 'like', '%curetage%');
                })->count();
            $lain = Operasi::whereMonth('tgl_operasi', $i)
                ->whereYear('tgl_operasi', $tahun)
                ->whereHas('paketOperasi', function ($query) {
                    $query->where('nm_perawatan', 'not like', '%curetage%')
                        ->where('nm_perawatan', 'not like', '%sc%')
                        ->where('nm_perawatan', 'not like', '%sectio caesaria%');
                })->count();

            $dataCaesar[] = $sc;
            $dataCuretage[] = $curetage;
            $dataLain[] = $lain;
        }

        return response()->json([
            'sc' => $dataCaesar,
            'curetage' => $dataCuretage,
            'lain' => $dataLain,
        ]);
    }
}
