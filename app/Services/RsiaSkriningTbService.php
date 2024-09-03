<?php

namespace App\Services;

use App\Models\RsiaSkriningTb;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
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
        return DataTables::of($query)
	        ->filter(function ($query) use($month, $year) {
	        	$request = request();
	        	if($request->has('search') && $request->get('search')['value']) {
					$keyword = $request->get('search')['value'];
			        return $query->whereHas('pasien', function ($query) use ($keyword) {
				        $query->where('nm_pasien', 'like', '%' . $keyword . '%')
				        ->orWhereHas('kecamatan', function ($query) use ($keyword) {
					        $query->where('nm_kec', 'like', '%' . $keyword . '%');
				        });
			        })->orWhereHas('poliklinik', function ($query) use ($keyword, $month, $year) {
				        $query->where('nm_poli', 'like', '%' . $keyword . '%');
			        });
		        }
	        })
	        ->make(true);
    }

}
