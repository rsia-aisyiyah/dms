@extends('dashboard.layouts.main')

@section('content')
    <div class="row">
        <x-beranda.counter-kunjungan></x-beranda.counter-kunjungan>
    </div>
    <div class="card">
        <div class="card-header">
            <strong>Pembiayaan Pasien</strong>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <x-beranda.card-grafik-pembiayaan></x-beranda.card-grafik-pembiayaan>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <x-beranda.card-grafik-detail-bpjs></x-beranda.card-grafik-detail-bpjs>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="input-group w-25">
                <div class="input-group-append">
                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                </div>
                <input type="text" id="blnPembiayaan" class="form-control monthPicker"
                    data-toggle="datetimepicker" aria-describedby="blnPembiayaan"
                    data-target="#blnPembiayaan"
                    autocomplete="off" />
                <button type="button" class="btn btn-primary" onclick="getPembiayaanPasien()">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </div>
    <x-beranda.card-grafik-kunjungan-tahunan></x-beranda.card-grafik-kunjungan-tahunan>
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
    {{--    <div class="row"> --}}
    {{--        @include('dashboard.content.beranda._diagramKunjunganDokter') --}}
    {{--    </div> --}}
    <div class="row">
        <x-beranda.card-grafik-dokter></x-beranda.card-grafik-dokter>
    </div>
    {{--    <div class="row"> --}}
    {{--        @include('dashboard.content.beranda._diagramRalan') --}}
    {{--    </div> --}}
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

        // function jumlahRalan(tgl_pertama = '', tgl_kedua = '') {
        //     $.ajax({
        //         url: 'ralan/hitung',
        //         data: {
        //             tgl_pertama: tgl_pertama,
        //             tgl_kedua: tgl_kedua
        //         },
        //         success: function(data) {
        //             ralan = data;
        //             document.getElementById('ralan').innerHTML = data;
        //         }
        //     });
        // }
        //
        // function jumlahRanap(tgl_pertama = '', tgl_kedua = '') {
        //     $.ajax({
        //         url: 'ranap/hitung',
        //         data: {
        //             tgl_pertama: tgl_pertama,
        //             tgl_kedua: tgl_kedua
        //         },
        //         success: function(data) {
        //             document.getElementById('ranap').innerHTML = data;
        //         }
        //     });
        // }
        //
        // function jumlahIGD(tgl_pertama = '', tgl_kedua = '') {
        //     $.ajax({
        //         url: 'igd/hitung',
        //         data: {
        //             tgl_pertama: tgl_pertama,
        //             tgl_kedua: tgl_kedua
        //         },
        //         success: function(data) {
        //             document.getElementById('igd').innerHTML = data;
        //
        //         }
        //     });
        // }
        //
        // function totalKunjungan(tgl_pertama = '', tgl_kedua = '') {
        //     $.ajax({
        //         url: 'beranda/kunjungan',
        //         data: {
        //             tgl_pertama: tgl_pertama,
        //             tgl_kedua: tgl_kedua
        //         },
        //         success: function(data) {
        //             document.getElementById('total').innerHTML = data;
        //         }
        //     });
        // }


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
            // pembiayaanPasien(tgl_pertama, tgl_kedua);
            // jumlahIGD(tgl_pertama, tgl_kedua);
            // jumlahRalan(tgl_pertama, tgl_kedua);
            // jumlahRanap(tgl_pertama, tgl_kedua);
            // totalKunjungan(tgl_pertama, tgl_kedua);
            statusReg(tgl_pertama, tgl_kedua);

        });
        $(document).ready(function() {
            statusReg();
            // loadDiagramRalan();
            statusDaftar();
            // pembiayaanPasien();
            // jumlahIGD();
            // jumlahRalan();
            // jumlahRanap();
            // totalKunjungan();
            registrasi();
            caraBooking();
        });
    </script>
@endpush
