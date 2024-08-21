<?php

namespace App\Services;

use App\Models\RsiaSkriningTb;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\DataTables;

class RsiaSkriningTbService
{
    protected function filterByYearAndMonth(RsiaSkriningTb $skrining, ?string $year, ?string $month): Builder
    {
        $query = $skrining->with([
            'pasien' => function ($query) {
                $query->with(['kecamatan', 'kelurahan', 'kabupaten']);
            }, 'pegawai', 'poliklinik', 'regPeriksa']);

        if ($year) {
            $query = $query->year($year);
        }

        if ($month) {
            $query = $query->year($year)->month($month);
        }

        if (!$year && !$month) {
            $query = $query->whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'));
        }
        return $query;
    }
    public function getDataTable(?string $year, ?string $month)
    {

        $query = $this->filterByYearAndMonth(new RsiaSkriningTb(), $year, $month);
        return DataTables::of($query)->make(true);
    }

}
