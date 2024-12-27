<?php

namespace App\Services;

use App\Traits\DateTrait;

/**
 * Class RsiaLosService.
 */
class RsiaLosService
{
    use DateTrait;
    protected $year;
    protected $month;
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

    function getLos(string $specialist, int $year = null){
        $this->setSpecialist($specialist);
        $this->setYear($year ? $year : date('Y'));

        for ($i = 1; $i <= 12; $i++) {
            $this->setMonth($i);
            $numerator = KamarInapService::getLamaInap($this->specialist, $i, $this->year);
            $denumerator = KamarInapService::getPasienPulang($this->specialist, $i, $this->year);
            $data[] = [
                'month' => $this->getMonthName($i),
                'year' => $year,
                'lamaInap' => $numerator,
                'pasienPulang'=>$denumerator,
                'los' => number_format($numerator / $denumerator, 2)
            ];
        }
        return $data;
    }
}
