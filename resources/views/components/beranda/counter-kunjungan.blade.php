@foreach($data as $key => $value)
    @php
        if($key === 'Ralan'){
		    $color = 'bg-success';
		    $textInfo = 'Rawat Jalan';
			$icon  = 'fas fa-stethoscope';
        }else if($key === 'Ranap'){
			$textInfo = 'Rawat Inap';
		    $color = 'bg-warning';
			$icon  = 'fa fa-bed';
        }else if($key ==='UGD'){
			$textInfo = 'Unit Gawat Darurat';
			$color = 'bg-danger';
			$icon  = 'fa fa-ambulance';
        }else{
            $color = 'bg-primary';
			$textInfo = $key;
			$icon  = 'fas fa-hospital-user';
        }
    @endphp
    <div class="col-lg-3 col-sm-6 col-md-6">
        <div class="info-box">
            <span class="info-box-icon {{$color}} elevation-1"><i class="{{$icon}}"></i></span>
            <div class="info-box-content">
                <p class="info-box-text mb-0">{{$textInfo}}</p>
                <h3 class="info-box-number mt-0 mb-0 p-0">
                    <span id="count{{$key}}">{{$value}}</span>
                </h3>
                <small> Pasien</small>
            </div>
        </div>
    </div>

@endforeach
