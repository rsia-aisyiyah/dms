<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Operasi;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class OperasiController extends Controller
{
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
    public function json(Request $request)
    {
        $data = '';
        $tanggal = new Carbon('this month');
        $sekarang = $tanggal->now();
        $awalBulan = $tanggal->startOfMonth();
        if ($request->ajax()) {
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
                    ->with('kamarInap', function ($query) {
                        $query->where('stts_pulang', '!=', 'Pindah Kamar')
                            ->where('tgl_keluar', '!=', '0000-00-00');
                    });
            }
        }
        return DataTables::of($data)
            ->filter(function ($query) use ($request) {
                if ($request->has('search') && $request->get('search')['value']) {
                    return $query->whereHas('regPeriksa.pasien', function ($query) use ($request) {
                        $query->where('nm_pasien', 'like', '%' . $request->get('search')['value'] . '%');
                    });
                }
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
                return $data->paketOperasi->kelas;
            })
            ->editColumn('kamar', function ($data) {
                if ($data->kamarInap == null) {
                    return 'Belum Pulang';
                } else {
                    return $data->kamarInap->kd_kamar;
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
