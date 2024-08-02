<?php

namespace App\Models;

use App\Models\RegPeriksa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BridgingSep extends Model
{
    use HasFactory;
    protected $table = "bridging_sep";

    public function regPeriksa()
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }

	function scopeGetByMonth($query, $month='', $year='')
	{
		if($month == '' && $year == ''){
			$month = date('m');
			$year = date('Y');
		}
		return $query->whereMonth('tglsep', $month)->whereYear('tglsep', $year);
	}
}
