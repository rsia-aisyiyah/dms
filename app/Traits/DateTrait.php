<?php

namespace App\Traits;
use Carbon\Carbon;

trait DateTrait
{
    public function setMonth($month)
    {
        $this->month = $month;
    }

    public function setYear($year)
    {
        $this->year = $year;
    }   

    public function getDaysOnMonth()
    {
        $carbon = Carbon::createFromDate($this->year, $this->month);
        return $carbon->daysInMonth;
    }

    public function getMonthName(int $montNumber){
        $carbon = Carbon::create()->month($montNumber)->translatedFormat('F');
        return $carbon;
    }


}
