<?php

namespace App\Services;

use App\Traits\DateTrait;

/**
 * Class RsiaToiService.
 */
class RsiaToiService
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

    function getJumlahTempatTidur(){
        return RsiaLogJumlahKamarService::getJumlahKamar($this->specialist, $this->month, $this->year);
    }
    function getHariBulan()  {
        return $this->getDaysOnMonth();
    }

    function getJumlahLamaInap(){
        return KamarInapService::getLamaInap($this->specialist, $this->month, $this->year);
    }

    function getPasienPulang(){
        return KamarInapService::getPasienPulang($this->specialist, $this->month, $this->year);
    }

    function getToi(string $specialist, int $year = null){
        $this->setSpecialist($specialist);
        $this->setYear($year ? $year : date('Y'));

        for ($i = 1; $i <= 12; $i++) {
            $this->setMonth($i);
            $toi = $this->getPasienPulang() === 0 ? 0:(($this->getJumlahTempatTidur()*$this->getHariBulan())-$this->getJumlahLamaInap())/$this->getPasienPulang();
            $data[] = [
                'month' => $this->getMonthName($i),
                'year' => $this->year,
                'specialist' => $this->specialist,
                'tempat_tidur' => $this->getJumlahTempatTidur(),
                'periode_rawat' => $this->getHariBulan(),
                'lama_inap' => $this->getJumlahLamaInap(),
                'pulang' => $this->getPasienPulang(),
                'toi' => number_format($toi, 2),
            ];
        }

        return $data;
    }


}
