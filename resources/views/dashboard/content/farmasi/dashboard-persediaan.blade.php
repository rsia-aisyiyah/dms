@extends('dashboard.layouts.main')

@section('content')
<div class="card">
    <div class="card-body">
        <h4>Ringkasan Pesanan</h4>

        <div class="row px-2 mt-3">
            <div class="col-md-6">
                <div class="form-group">
                    <div class="row">
                        <label>Filter Data </label>
                        <div class="input-group mb-3">
                            <div class="input-group-append">
                                <span class="input-group-text" id="tahun-addon"><i class="fas fa-calendar"></i></span>
                            </div>
                            <input type="text" id="date-registrasi" class="form-control datetimepicker-input"
                                data-toggle="datetimepicker" aria-describedby="tahun-addon" data-target="#date-registrasi"
                                autocomplete="off" />
                        </div>
                    </div>
                </div>          
            </div>

            <div class="col-md-1"></div>

            <div class="col-md-5">
                <div class="">
                    <label>Tipe Filter Filter Data</label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="radio_tgl" id="tgl_pesan" value="tgl_pesan" checked>
                            <label class="form-check-label" for="tgl_pesan">Tanggal Pesan</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="radio_tgl" id="tgl_faktur" value="tgl_faktur">
                            <label class="form-check-label" for="tgl_faktur">Tanggal Faktur</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="radio_tgl" id="tgl_tempo" value="tgl_tempo">
                            <label class="form-check-label" for="tgl_tempo">Tanggal Tempo</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-6">
                <div class="info-box" style="background-color: whitesmoke">
                    <span class="info-box-icon bg-blue elevation-1"><i class="fas fa-hospital-user"></i></span>
                    <div class="info-box-content py-2">
                        <p class="info-box-text mb-0">Sudah Dibayar</p>
                        <h3 class="info-box-number mt-0 mb-0 p-0">
                            <span id=sdhByr>Loading...</span>
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="info-box" style="background-color: whitesmoke">
                    <span class="info-box-icon bg-yellow elevation-1"><i class="fas fa-procedures"></i></span>
                    <div class="info-box-content py-2">
                        <p class="info-box-text mb-0">Belum Lunas</p>
                        <h3 class="info-box-number mt-0 mb-0 p-0">
                            <span id="blmLns">Loading...</span>
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-12">
                <div class="info-box" style="background-color: whitesmoke">
                    <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-walking"></i></span>
                    <div class="info-box-content py-2">
                        <p class="info-box-text mb-0">Belum Dibayar</p>
                        <h3 class="info-box-number mt-0 mb-0 p-0">
                            <span id="blmByr">Loading...</span>
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const apiUrl = "{{ env('API_URL') }}";
    var tahun = '';
    var bulan = '';
    var type = 'tgl_pesan';

    function formatRupiah(angka, prefix){
        if (typeof angka === 'number') {
            angka = angka.toString();
        }

        
        var number_string = angka.replace(/[^,\d]/g, ',').toString(),
        
        split   		= number_string.split(','),
        sisa     		= split[0].length % 3,
        rupiah     		= split[0].substr(0, sisa),
        ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);
        
        // tambahkan titik jika yang di input sudah menjadi angka ribuan
        if(ribuan){
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        
        // rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }

    function fetchMetrics (bulan = '', tahun = '', jenis = '') {
        $.ajax({
            url: `${apiUrl}farmasi/gudang/pesanan`,
            type: 'GET',
            data: {
                "tgl" : {
                    "bulan" : bulan != '' ? bulan : moment().format('MM'),
                    "tahun" : tahun != '' ? tahun : moment().format('YYYY'),
                    "type" : jenis
                },
            },
            beforeSend: function (request) {
                request.setRequestHeader("Authorization", "Bearer " + '{{ Session::get('token') }}');
            },
            success: function (response) {
                if (response.success) {
                    var rdata = response.data;

                    const blmByr = rdata.filter(function (item) {
                        return item.status == 'Belum Dibayar';
                    });

                    const sdhBayr = rdata.filter(function (item) {
                        return item.status == 'Sudah Dibayar';
                    });

                    const blmLns = rdata.filter(function (item) {
                        return item.status == 'Belum Lunas';
                    });
                    
                    
                    $('#blmByr').html(blmByr.length != 0 ? formatRupiah(blmByr[0].total_tagihan, 'Rp. ') : 0);
                    $('#sdhByr').html(sdhBayr.length != 0 ? formatRupiah(sdhBayr[0].total_tagihan, 'Rp. ') : 0);
                    $('#blmLns').html(blmLns.length != 0 ? formatRupiah(blmLns[0].total_tagihan, 'Rp. ') : 0);

                } else {
                    toastr.error(response.message, 'Error');
                }
            }
        });
    }

    $(document).ready(function () {
        $('#date-registrasi').datetimepicker({
            format: "YYYY-MM",
            useCurrent: false,
        });

        $('#date-registrasi').on('change.datetimepicker', async function() {
            var date = $(this).val().split('-');
            tahun = date[0];
            bulan = date[1];

            fetchMetrics(bulan, tahun);
        });

        // radio button on change set type to value
        $('input[type=radio][name=radio_tgl]').change(function() {
            type = this.value;
            fetchMetrics(bulan, tahun, type);
        });
        
        var currentMonth = moment().format('YYYY-MM');
        $('#date-registrasi').val(currentMonth);

        fetchMetrics();
    });
</script>
@endpush