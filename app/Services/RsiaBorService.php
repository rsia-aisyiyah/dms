<?php

namespace App\Services;

use App\Models\KamarInap;
use App\Models\RsiaLogJumlahKamar;
use Carbon\Carbon;

class RsiaBorService
{
    protected $month;
    protected $year;
    protected $specialist;

    public function __construct(int $month = null, int $year = null)
    {
        $this->month = $month;
        $this->year = $year;
    }

    function setSpecialist($specialist)
    {
        $this->specialist = $specialist;
    }
    function setMonth($month)
    {
        $this->month = $month;
    }
    function setYear($year)
    {
        $this->year = $year;
    }

    function getCountRawat()
    {
        $kamarInap = KamarInap::whereMonth('tgl_keluar', $this->month)
            ->whereYear('tgl_keluar', $this->year)
            ->select('lama');

        if ($this->specialist === 'all') {
            $kamarInap->where(function ($query) {
                $query->where('kd_kamar', 'like', '%' . 'Kandungan' . '%')
                    ->orWhere('kd_kamar', 'like', '%' . 'Anak' . '%');
            });
        } else {
            $kamarInap->where('kd_kamar', 'like', '%' . $this->specialist . '%');
        }

        $kamarInap = $kamarInap->get()->sum('lama');

        return $kamarInap;

    }

    function getDaysOnMonth()
    {
        $carbon = Carbon::createFromDate($this->year, $this->month);
        return $carbon->daysInMonth;
    }

    function getCountTempatTidur()
    {
        $kamarLog = RsiaLogJumlahKamar::where('tahun', $this->year)
            ->where('bulan', $this->month);
        if ($this->specialist === 'all') {
            return $kamarLog->where(function ($query) {
                $query->where('kategori', 'like', '%' . 'Kandungan' . '%')
                    ->orWhere('kategori', 'like', '%' . 'Anak' . '%');
            })->get()->sum('jumlah');
        } else {
            $kamar = $kamarLog->where('kategori', 'like', '%' . $this->specialist . '%')->first();
            return $kamar ? $kamar->jumlah : 0;
        }
    }

    function get(string $specialist, int $year = null)
    {
        $this->setSpecialist($specialist);
        $this->setYear($year ? $year : date('Y'));

        for ($i = 1; $i <= 12; $i++) {
            $this->setMonth($i);
            $numerator = $this->getCountRawat();
            $denumerator = $this->getDaysOnMonth() * $this->getCountTempatTidur();
            $jumlahBor = $this->getCountTempatTidur() ? $numerator / $denumerator * 100 : 0;
            $data[] = [
                'month' => Carbon::create()->month($i)->translatedFormat('F'),
                'year' => $this->year,
                'countRawat' => $this->getCountRawat(),
                'daysOnMonth' => $this->getDaysOnMonth(),
                'jumlahKamar' => $this->getCountTempatTidur(),
                'jumlahBor' => number_format($jumlahBor, 2),
            ];
        }

        return $data;
    }

}
