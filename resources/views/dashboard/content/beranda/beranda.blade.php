@extends('dashboard.layouts.main')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <div class="row">
                            <label>Filter Data </label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="tanggal" name="tanggal" autocomplete="off" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-blue elevation-1"><i class="fas fa-hospital-user"></i></span>
                <div class="info-box-content">
                    <p class="info-box-text mb-0">Total Kunjungan</p>
                    <h3 class="info-box-number mt-0 mb-0 p-0">
                        <span id=total>0</span>
                    </h3>
                    <small> Pasien</small>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-hospital-user"></i></span>
                <div class="info-box-content">
                    <p class="info-box-text mb-0">Rawat Jalan</p>
                    <h3 class="info-box-number mt-0 mb-0 p-0">
                        <span id="ralan">0</span>
                    </h3>
                    <small> Pasien</small>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-yellow elevation-1"><i class="fas fa-hospital-user"></i></span>
                <div class="info-box-content">
                    <p class="info-box-text mb-0">Rawat Inap</p>
                    <h3 class="info-box-number mt-0 mb-0 p-0">
                        <span id="ranap">0</span>
                    </h3>
                    <small> Pasien</small>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-hospital-user"></i></span>

                <div class="info-box-content">
                    <p class="info-box-text mb-0">IGD</p>
                    <h3 class="info-box-number mt-0 mb-0 p-0">
                        <span id="igd">0</span>
                    </h3>
                    <small> Pasien</small>
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        @include('dashboard.content.beranda._diagramPembayaran')
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-4">
            <div class="card">
                <div class="card-header m-auto">
                    <span>Status Daftar</span>
                </div>
                <div class="card-body pt-0">
                    <canvas id="statusDaftar" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>
        @include('dashboard.content.beranda._diagramBooking')
        @include('dashboard.content.beranda._diagramStatusReg')
    </div>
    <div class="row">
        @include('dashboard.content.beranda._diagramRegistrasi')
    </div>
    <div class="row">
        @include('dashboard.content.beranda._diagramKunjunganDokter')
    </div>
    <div class="row">
        @include('dashboard.content.beranda._diagramRalan')
    </div>
@endsection

@push('scripts')
    <script>
        var donutChart;

        function statusDaftar(tgl_pertama = '', tgl_kedua = '') {
            $.ajax({
                url: 'beranda/status',
                type: 'GET',
                data: {
                    'tgl_pertama': tgl_pertama,
                    'tgl_kedua': tgl_kedua,
                },
                success: function(data) {
                    var donutChartCanvas = $('#statusDaftar').get(0);
                    var donutData = {
                        labels: [
                            'Baru',
                            'Lama',
                        ],
                        datasets: [{
                            data: [data.lama, data.baru],
                            backgroundColor: ['#6f42c1', '#e83e8c'],
                        }]
                    }

                    donutChart = new Chart(donutChartCanvas, {
                        type: 'doughnut',
                        data: donutData,
                        options: {
                            plugins: {
                                datalabels: {
                                    color: 'white',
                                    anchor: 'center',
                                    align: 'center',
                                    formatter: Math.round,
                                    font: {
                                        size: 11
                                    }
                                }
                            }
                        }
                    });
                }
            });

        }

        function jumlahRalan(tgl_pertama = '', tgl_kedua = '') {
            $.ajax({
                url: 'ralan/hitung',
                data: {
                    tgl_pertama: tgl_pertama,
                    tgl_kedua: tgl_kedua
                },
                success: function(data) {
                    ralan = data;
                    document.getElementById('ralan').innerHTML = data;
                }
            });
        }

        function jumlahRanap(tgl_pertama = '', tgl_kedua = '') {
            $.ajax({
                url: 'ranap/hitung',
                data: {
                    tgl_pertama: tgl_pertama,
                    tgl_kedua: tgl_kedua
                },
                success: function(data) {
                    document.getElementById('ranap').innerHTML = data;
                }
            });
        }

        function jumlahIGD(tgl_pertama = '', tgl_kedua = '') {
            $.ajax({
                url: 'igd/hitung',
                data: {
                    tgl_pertama: tgl_pertama,
                    tgl_kedua: tgl_kedua
                },
                success: function(data) {
                    document.getElementById('igd').innerHTML = data;

                }
            });
        }

        function totalKunjungan(tgl_pertama = '', tgl_kedua = '') {
            $.ajax({
                url: 'beranda/kunjungan',
                data: {
                    tgl_pertama: tgl_pertama,
                    tgl_kedua: tgl_kedua
                },
                success: function(data) {
                    document.getElementById('total').innerHTML = data;
                }
            });
        }


        $('#tanggal').on('apply.daterangepicker', function(env, picker) {

            tgl_pertama = picker.startDate.format('YYYY-MM-DD');
            tgl_kedua = picker.endDate.format('YYYY-MM-DD');

            var bulan = new Array(12);
            bulan[0] = "Januari";
            bulan[1] = "Februari";
            bulan[2] = "Maret";
            bulan[3] = "April";
            bulan[4] = "Mei";
            bulan[5] = "Juni";
            bulan[6] = "Juli";
            bulan[7] = "Agustus";
            bulan[8] = "September";
            bulan[9] = "Oktober";
            bulan[10] = "November";
            bulan[11] = "Desember";
            var tanggal1 = new Date(tgl_pertama);
            var tanggal2 = new Date(tgl_kedua);

            hari1 = tanggal1.getDate();
            bulan1 = tanggal1.getMonth();
            tahun1 = tanggal1.getFullYear();

            hari2 = tanggal2.getDate();
            bulan2 = tanggal2.getMonth();
            tahun2 = tanggal2.getFullYear();

            tgl1 = hari1 + ' ' + bulan[bulan1] + ' ' + tahun1
            tgl2 = hari2 + ' ' + bulan[bulan2] + ' ' + tahun2

            diagramBayar.destroy();
            donutChart.destroy();
            booking.destroy();
            statusPeriksa.destroy();
            caraBooking(tgl_pertama, tgl_kedua);
            statusDaftar(tgl_pertama, tgl_kedua);
            pembiayaanPasien(tgl_pertama, tgl_kedua);
            jumlahIGD(tgl_pertama, tgl_kedua);
            jumlahRalan(tgl_pertama, tgl_kedua);
            jumlahRanap(tgl_pertama, tgl_kedua);
            totalKunjungan(tgl_pertama, tgl_kedua);
            statusReg(tgl_pertama, tgl_kedua);

        });
        $(document).ready(function() {
            statusReg();
            loadDiagramRalan();
            statusDaftar();
            pembiayaanPasien();
            jumlahIGD();
            jumlahRalan();
            jumlahRanap();
            totalKunjungan();
            registrasi();
            caraBooking();
        });
    </script>
@endpush
