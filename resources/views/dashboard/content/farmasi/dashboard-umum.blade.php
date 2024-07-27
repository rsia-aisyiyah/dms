@extends('dashboard.layouts.main')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <div class="row">
                        <label>Filter Data </label>
                        <div class="input-group mb-3">
                            <div class="input-group-append">
                                <span class="input-group-text" id="tahun-addon"><i class="fas fa-calendar"></i></span>
                            </div>
                            <input type="text" id="date-registrasi" class="form-control datetimepicker-input"
                                data-toggle="datetimepicker" aria-describedby="tahun-addon"
                                data-target="#date-registrasi" autocomplete="off" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="col-12 col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-blue elevation-1"><i class="fas fa-hospital-user"></i></span>
                    <div class="info-box-content py-2">
                        <p class="info-box-text mb-0">Total Penjualan</p>
                        <h3 class="info-box-number mt-0 mb-0 p-0">
                            <span id=total>Loading...</span>
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-walking"></i></span>
                    <div class="info-box-content py-2">
                        <p class="info-box-text mb-0">Penjualan Ralan</p>
                        <h3 class="info-box-number mt-0 mb-0 p-0">
                            <span id="ralan">Loading...</span>
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow elevation-1"><i class="fas fa-procedures"></i></span>
                    <div class="info-box-content py-2">
                        <p class="info-box-text mb-0">Penjualan Ranap</p>
                        <h3 class="info-box-number mt-0 mb-0 p-0">
                            <span id="ranap">Loading...</span>
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <span><a href="#diagramPenjualanObat" style=" color:black!important">Penjualan Obat</a> </span>
            </div>
            <div class="card-body">
                <canvas id="diagramPenjualanObat"
                    style="min-height: 250px; height: 250px; max-height: 500px; max-width: 100%;"></canvas>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <span><a href="#topObat" style=" color:black!important">Top Obat bulan <strong><span class="fw-bold" id="bulan-title"></span></strong></a> </span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4>Top Teratas</h4>
                        <div class="table-responsive">
                            <table id="topObat" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Nama Obat</th>
                                        <th>Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h4>Top Terbawah</h4>
                        <div class="table-responsive">
                            <table id="bottomObat" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Nama Obat</th>
                                        <th>Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function formatRupiah(angka, prefix){

        if (typeof angka === 'number') {
            angka = angka.toString();
        }

        var number_string = angka.replace(/[^,\d]/g, '').toString(),
        split   		= number_string.split(','),
        sisa     		= split[0].length % 3,
        rupiah     		= split[0].substr(0, sisa),
        ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);
    
        // tambahkan titik jika yang di input sudah menjadi angka ribuan
        if(ribuan){
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
    
        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }

    function fetchData(bulan = '', tahun = '') {
        console.log("ajax fetching data");
        $.ajax({
            url: `{{ env('APP_URL') }}/api/farmasi/gudang/metrics`,
            type: "POST",
            dataType: "JSON",
            data: {
                'tgl_perawatan' : {
                    'bulan': bulan,
                    'tahun': tahun,
                }
            },

            beforeSend: function (request) {
                request.setRequestHeader("Authorization", "Bearer " + '{{ Session::get('token') }}');
            },
            
            success: function (data) {
                if (data.success) {
                    var m = data.data;
                    
                    var ranap = m.filter(function (e) {
                        return e.status == 'Ranap';
                    });

                    var ralan = m.filter(function (e) {
                        return e.status == 'Ralan';
                    });

                    $('#total').html(formatRupiah(ranap[0].total + ralan[0].total, 'Rp. '));
                    $('#ralan').html(formatRupiah(ralan[0].total, 'Rp. '));
                    $('#ranap').html(formatRupiah(ranap[0].total, 'Rp. '));
                    
                } else {
                    toastr.error(data.message, 'Error');
                }
            },
            
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR, textStatus, errorThrown);
            }
        });
    }

    function getTopObat(bulan = '', tahun = '') {
        // datatable topObat ajax
        $('#topObat').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            ajax: {
                url: `{{ env('APP_URL') }}/api/farmasi/gudang/metrics/top/obat?datatables=1`,
                type: "POST",
                dataType: "JSON",
                data: {
                    'tgl_perawatan' : {
                        'bulan': bulan,
                        'tahun': tahun,
                    }
                },
                beforeSend: function (request) {
                    request.setRequestHeader("Authorization", "Bearer " + '{{ Session::get('token') }}');
                }
            },
            columns: [
                { data: 'nama_obat', name: 'nama_obat' },
                { data: 'total', name: 'total', className: 'text-center' },
            ],
            order: [[ 1, "desc" ]],
            searching: false,
            paging: false,
        });
    }   

    function getBottomObat(bulan = '', tahun = '') {
        // datatable topObat ajax
        $('#bottomObat').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            ajax: {
                url: `{{ env('APP_URL') }}/api/farmasi/gudang/metrics/bottom/obat?datatables=1`,
                type: "POST",
                dataType: "JSON",
                data: {
                    'tgl_perawatan' : {
                        'bulan': bulan,
                        'tahun': tahun,
                    }
                },
                beforeSend: function (request) {
                    request.setRequestHeader("Authorization", "Bearer " + '{{ Session::get('token') }}');
                }
            },
            lengChange: false,
            order: [[ 1, "asc" ]],
            searching: false,
            paging: true,
            columns: [
                { data: 'nama_obat', name: 'nama_obat' },
                { data: 'total', name: 'total', className: 'text-center' },
            ],
            // dom make info and pagination in one line
            dom: 't<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            pagingType: 'simple',
            language: {
                paginate: {
                    previous: "<i class='fas fa-chevron-left'></i>",
                    next: "<i class='fas fa-chevron-right'></i>"
                }
            }
        });
    }   

    function fetchMetricsDetail(bulan = '', tahun = '') {
        // Chart.register(ChartDataLabels);
        diagramPenjualanObat = document.getElementById("diagramPenjualanObat");

        console.log("ajax fetching detail data");
        $.ajax({
            url: `{{ env('APP_URL') }}/api/farmasi/gudang/metrics/detail`,
            type: "POST",
            dataType: "JSON",
            data: {
                'tgl_perawatan' : {
                    'bulan': bulan,
                    'tahun': tahun,
                }
            },

            beforeSend: function (request) {
                request.setRequestHeader("Authorization", "Bearer " + '{{ Session::get('token') }}');
            },
            
            success: function(data) {
                var mdata = data.data;

                var ranap = mdata.map(function (e) {
                    return e.count_no_rawat_ranap;
                });

                var ralan = mdata.map(function (e) {
                    return e.count_no_rawat_ralan;
                });

                var tgl = mdata.map(function (e) {
                    return e.tgl_perawatan;
                });
                

                var dataChart = {
                    labels: tgl,
                    datasets: [
                        {
                            label: "Rawat Inap",
                            data: ranap,
                            backgroundColor: '#4062BB',
                            beginAtZero: true,
                        },
                        {
                            label: "Rawat Jalan",
                            data: ralan,
                            backgroundColor: '#59C3C3',
                            beginAtZero: true,
                        }
                    ],
                };

                var chartOptions = {
                    indexAxis: 'x',
                    responsive: true,
                    scales: {
                        x: {
                            beginAtZero: true,
                            grace: '5%',
                            max: 2000,
                            stacked: true
                        },
                        y: {
                            stacked: true
                        }

                    },
                    plugins: {
                        datalabels: {
                            color: 'white',
                            anchor: 'center',
                            align: 'center',
                            formatter: Math.round,
                            font: {
                                size: 10,
                            }
                        }
                    }
                };

                diagramPenjualanObat = new Chart(diagramPenjualanObat, {
                    type: 'bar',
                    data: dataChart,
                    options: chartOptions
                });
            },

            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR, textStatus, errorThrown);
            }
        });
    }

    function resetMetric() {
        $('#total').html('Loading...');
        $('#ralan').html('Loading...');
        $('#ranap').html('Loading...');

        $('#bulan-title').html('');

        console.log("metric reseted");
    }

    $(document).ready(async function () {
        $('#date-registrasi').datetimepicker({
            format: "YYYY-MM",
            useCurrent: false,
        });

        $('#date-registrasi').on('change.datetimepicker', async function() {
            var date = $(this).val().split('-');
            tahun = date[0];
            bulan = date[1];

            
            diagramPenjualanObat.destroy();
            
            await resetMetric();
            await fetchData(bulan, tahun);
            await fetchMetricsDetail(bulan, tahun);
            await getTopObat(bulan, tahun);
            await getBottomObat(bulan, tahun);

            $('#bulan-title').html(formatBulanTahun(moment(tahun + '-' + bulan + '-01').format('MMMM YYYY')));
        });
        
        
        // set default value to current month
        var currentMonth = moment().format('YYYY-MM');
        $('#date-registrasi').val(currentMonth);
        $('#bulan-title').html(formatBulanTahun(moment(currentMonth + '-01').format('MMMM YYYY')));
        
        await fetchData(moment().format('MM'), moment().format('YYYY'));
        await fetchMetricsDetail(moment().format('MM'), moment().format('YYYY'));
        await getTopObat(moment().format('MM'), moment().format('YYYY'));
        await getBottomObat(moment().format('MM'), moment().format('YYYY'));
    });
</script>
@endpush