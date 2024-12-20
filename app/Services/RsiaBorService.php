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
    function getCountRawat()
    {
        $kamarInap = KamarInap::whereMonth('tgl_keluar', $this->month)
            ->whereYear('tgl_keluar', $this->year)->select('lama')
            ->where('kd_kamar', 'like', '%' . $this->specialist . '%')
            ->get()
            ->sum('lama');

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
            ->where('bulan', $this->month)
            ->where('kategori', 'like', '%' . $this->specialist . '%')
            ->first();
        return $kamarLog ? $kamarLog->jumlah : 0;
    }

    function get(string $specialist)
    {
        $this->setSpecialist($specialist);
        $numerator = $this->getCountRawat();
        $denumerator = $this->getDaysOnMonth() * $this->getCountTempatTidur();
        $jumlahBor = $this->getCountTempatTidur() ? $numerator / $denumerator * 100 : 0;
        return [
            'month' => Carbon::create()->month($this->month)->translatedFormat('F'),
            'countRawat' => $this->getCountRawat(),
            'daysOnMonth' => $this->getDaysOnMonth(),
            'jumlahKamar' => $this->getCountTempatTidur(),
            'jumlahBor' => number_format($jumlahBor, 2),
        ];
    }

}
