<?php

namespace App\Services;

use App\Models\KamarInap;
use App\Models\RsiaLogJumlahKamar;
use Carbon\Carbon;
use App\Traits\DateTrait;

class RsiaBorService
{
    use DateTrait;
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
        return KamarInapService::getLamaInap($this->specialist, $this->month, $this->year);
    }

    function getCountTempatTidur()
    {
        return RsiaLogJumlahKamarService::getJumlahKamar($this->specialist, $this->month, $this->year);
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
                'month' =>$this->getMonthName($i),
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
