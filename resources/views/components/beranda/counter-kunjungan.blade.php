<div class="col-lg-12 col-md-12 col-sm-12 mb-3">
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
        </div>
        <input type="text" id="blnCounterKunjungan" class="form-control monthPicker"
            data-toggle="datetimepicker" aria-describedby="blnCounterKunjungan"
            data-target="#blnCounterKunjungan"
            autocomplete="off" />
    </div>
</div>
@foreach ($data as $key => $value)
    @php
        if ($key === 'Ralan') {
            $color = 'bg-success';
            $textInfo = 'Rawat Jalan (Non UGD)';
            $icon = 'fas fa-stethoscope';
        } elseif ($key === 'Ranap') {
            $textInfo = 'Rawat Inap';
            $color = 'bg-warning';
            $icon = 'fa fa-bed';
        } elseif ($key === 'UGD') {
            $textInfo = 'Unit Gawat Darurat';
            $color = 'bg-danger';
            $icon = 'fa fa-ambulance';
        } else {
            $color = 'bg-primary';
            $textInfo = $key;
            $icon = 'fas fa-hospital-user';
        }
    @endphp
    <div class="col-lg-3 col-sm-6 col-md-6">
        <div class="info-box">
            <span class="info-box-icon {{ $color }} elevation-1"><i class="{{ $icon }}"></i></span>
            <div class="info-box-content">
                <p class="info-box-text mb-0">{{ $textInfo }}</p>
                <h3 class="info-box-number mt-0 mb-0 p-0">
                    <span id="count{{ $key }}">{{ $value }}</span>
                </h3>
                <small> Pasien</small>
            </div>
        </div>
    </div>
@endforeach

<div class="col-lg-3 col-sm-6 col-md-6">
    <div class="info-box">
        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-hospital-user"></i></span>
        <div class="info-box-content">
            <p class="info-box-text mb-0">UGD</p>
            <h3 class="info-box-number mt-0 mb-0 p-0">
                <span id="kunjunganUgdRanap">RI {{ $dataUgd['Ranap'] }}</span> / <span id="kunjunganUgdRalan">RJ {{ $dataUgd['Ralan'] }}</span>
            </h3>
            <small> Pasien</small>
        </div>
    </div>

    @push('scripts')
        <script>
            const blnCounterKunjungan = $('#blnCounterKunjungan');

            blnCounterKunjungan.on('change.datetimepicker', function(e) {
                const year = e.currentTarget.value.split('-')[0];
                const month = e.currentTarget.value.split('-')[1];
                getCounterKunjungan(year, month);

            })

            function getCounterKunjungan(year = '', month = '') {
                $.get(`${url}/beranda/kunjungan/total`, {
                    'year': year,
                    'month': month
                }).done((response) => {

                    console.log(response.data);
                    

                    for (const [key, value] of Object.entries(response.data)) {
                        if (value === 0) {
                            $('.info-box-number').find('span').text(0)
                        }
                        $(`#count${key}`).html(value)

                    }

                    $('#kunjunganUgdRanap').text(`RI ${response.dataUgd['Ranap']}`)
                    $('#kunjunganUgdRalan').text(`RJ ${response.dataUgd['Ralan']}`)
                    toastr.success('Memuat data total kunjungan', 'Berhasil');
                })
            }
        </script>
    @endpush
