<?php

namespace App\Http\Actions;
use App\Models\RegPeriksa;
use App\Models\RsiaSkriningTb;
class SkriningTbByPoliklinik
{
	public function __invoke(RegPeriksa $registrasi, RsiaSkriningTb $skrining, $year = null, $month = null){
	    $skrining = collect($skrining->getCountByPoliklinik()
		    ->year($year)->month($month)->get());
		return $skrining->map(function($item){
			return $item->poliklinik;
		})->countBy('kd_poli');

	}
}