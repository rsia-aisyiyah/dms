<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use App\Models\RegPeriksa;
use App\Models\RsiaSkriningTb;
use Carbon\Carbon;

class SkriningTbCountByYearAction extends Controller
{

    public function __invoke(RegPeriksa $registrasi, RsiaSkriningTb $skrining, $year = null)
    {

        $totalRegistrasiByMonth = $registrasi
            ->year($year)
            ->where('stts', 'Sudah')
            ->with('skriningTb')
            ->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->tgl_registrasi)->format('Y-m'); // Group by year-month
            })
            ->map(function ($registrations, $month) { // $month is the key (Y-m)
                $totalRegistrations = $registrations->count(); // Count total registrations for the month
                $registrationsWithSkrining = $registrations->filter(function ($registration) {
                    return $registration->skriningTb !== null; // Check if 'skriningTb' exists (not null)
                })->count();
                $monthName = Carbon::parse($month . '-01')->translatedFormat('F');

                $percentage = $totalRegistrations > 0
                ? number_format(($registrationsWithSkrining / $totalRegistrations) * 100, 2)
                : 0;

                return [
                    'data' => $registrationsWithSkrining,
                    'label' => $monthName,
                    'capaian' => $percentage,
                    'kunjungan' => $totalRegistrations,
                ];
            });

        return [
            'data' => $totalRegistrasiByMonth->pluck('data'),
            'label' => $totalRegistrasiByMonth->pluck('label'),
            'capaian' => $totalRegistrasiByMonth->pluck('capaian'),
            'kunjungan' => $totalRegistrasiByMonth->pluck('kunjungan'),
        ];

    }

}
