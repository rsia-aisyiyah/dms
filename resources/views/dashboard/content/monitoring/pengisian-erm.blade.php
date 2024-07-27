@extends('dashboard.layouts.main')
@section('content')
<div class="row">
    <div class="col-12 col-md-12">
        <div class="card card-teal collapsed-card card-ranap">
            <div class="card-header">
                <h3 class="card-title">Rawat Inap</h3>
                <div class="card-tools">
                    <span  id="bulan"></span>
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="form-group">
                            <label>Tanggal :</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="tanggal" name="tanggal" autocomplete="off" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive text-sm">
                            <table id="table-pengisian-erm-ranap" class="table table-bordered dataTable" width="100%"
                                cellspacing="0">
                                <thead>
                                    <tr role="row">
                                        <th>NAMA DOKTER</th>
                                        <th>JUMLAH PASIEN</th>
                                        <th>RESUME PASIEN</th>
                                        <th>ASMED ANAK</th>
                                        <th>ASMED KANDUNGAN</th>
                                        <th>CPPT</th>
                                        <th>VERIFIKASI CPPT</th>
                                        <th>VERIFIKASI RESUME</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-12">
        <div class="card card-yellow collapsed-card card-ralan">
            <div class="card-header">
                <h3 class="card-title">Rawat Jalan</h3>
                <div class="card-tools">
                    <span  id="bulan"></span>
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="form-group">
                            <label>Tanggal :</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="tanggal" name="tanggal"
                                    autocomplete="off" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive text-sm">
                            <table id="table-pengisian-erm-ralan" class="table table-bordered dataTable" width="100%"
                                cellspacing="0">
                                <thead>
                                    <tr role="row">
                                        <th>NAMA DOKTER</th>
                                        <th>JUMLAH PASIEN</th>
                                        <th>CPPT</th>
                                        <th>ASMED RALAN</th>
                                        <th>E-RESEP</th>
                                    </tr>
                                </thead>
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
    var tgl_kedua = null;
    var tgl_pertama = null;
    var pembiayaan = null;
    var status = null;

    $('.card-ranap #tanggal').daterangepicker({
        locale: {
            language: 'id',
            applyLabel: 'Terapkan',
            cancelLabel: 'Batal',
            format: 'DD/MM/YYYY',
            daysOfWeek: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Ming'],
            monthNames: [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 
                'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ],
        },
        startDate: moment().startOf('month'),
        autoclose: true,
        showDropdowns: true,
        minYear: 2019,
        maxYear: {{ date('Y') + 1 }},
    });

    $('.card-ralan #tanggal').daterangepicker({
        locale: {
            language: 'id',
            applyLabel: 'Terapkan',
            cancelLabel: 'Batal',
            format: 'DD/MM/YYYY',
            daysOfWeek: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Ming'],
            monthNames: [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 
                'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ],
        },
        startDate: moment().startOf('month'),
        autoclose: true,
        showDropdowns: true,
        minYear: 2019,
        maxYear: {{ date('Y') + 1 }},
    });

    function ajaxRanap(tgl_pertama = null, tgl_kedua = null) {
        $("#table-pengisian-erm-ranap").DataTable({
            pageLength: 10,
            lengthMenu: [-1, 10, 25, 50, 75, 100],
            lengthChange: false,
            processing: true,
            serverSide: true,
            ajax: {
                url: `{{ env('APP_URL') }}/api/monitor/pengisian-erm/spesialis/ranap?datatables=1`,
                type: 'GET',
                data: {
                    tgl_registrasi: {
                        start: tgl_pertama,
                        end: tgl_kedua,
                    }
                },
                beforeSend: function(req) {
                    req.setRequestHeader("Accept", "application/json");
                },
            },
            searching: false,
            dom: "<'d-flex align-items-center justify-content-between'<'text-center'l><'text-center'f><'text-center'B>>" +
                "<'row'<'col-sm-12 col-md-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            initComplete: function() {
                toastr.success('Data berhasil dimuat.', 'Success!');
            },
            buttons: [{
                    extend: 'copy',
                    text: '<i class="mr-1 fas fa-copy"></i> Salin',
                    className: 'btn btn-info mb-3',
                    title : 'data-monitoring-rawat-inap-{{ date('dmy') }}'
                },
                {
                    extend: 'csv',
                    text: '<i class="mr-1 fas fa-file-csv"></i> CSV',
                    className: 'btn btn-info mb-3',
                    title : 'data-monitoring-rawat-inap-{{ date('dmy') }}'
                },
                {
                    extend: 'excel',
                    text: '<i class="mr-1 fas fa-file-excel"></i> Excel',
                    className: 'btn btn-info mb-3',
                    title : 'data-monitoring-rawat-inap-{{ date('dmy') }}'
                },
            ],
            columns: [{
                    name: 'nm_dokter',
                    data: 'nm_dokter',
                    render: function(data, type, row) {
                        return '<div class="text-nowrap">' + data + ' <br /> <span class="text-muted">' + row.kd_dokter + '</span></div>';
                    }
                },
                {
                    data: 'jumlah_reg_periksa',
                    name: 'jumlah_reg_periksa'
                },
                {
                    data: 'jResumePasienRanap',
                    name: 'jResumePasienRanap'
                },
                {
                    data: 'jPenilaianMedisRanap',
                    name: 'jPenilaianMedisRanap'
                },
                {
                    data: 'jPenilaianMedisRanapKandungan',
                    name: 'jPenilaianMedisRanapKandungan'
                },
                {
                    data: 'jPemeriksaanRanap',
                    name: 'jPemeriksaanRanap'
                },
                {
                    data: 'jVerifikasiPemeriksaanRanap',
                    name: 'jVerifikasiPemeriksaanRanap'
                },
                {
                    data: 'jVerifikasiResumePasienRanap',
                    name: 'jVerifikasiResumePasienRanap'
                }
            ],
        });
    }

    function ajaxRalan(tgl_pertama = null, tgl_kedua = null) {
        $("#table-pengisian-erm-ralan").DataTable({
            pageLength: 10,
            lengthMenu: [-1, 10, 25, 50, 75, 100],
            lengthChange: false,
            processing: true,
            serverSide: true,
            ajax: {
                url: `{{ env('APP_URL') }}/api/monitor/pengisian-erm/spesialis/ralan?datatables=1`,
                type: 'GET',
                data: {
                    tgl_registrasi: {
                        start: tgl_pertama,
                        end: tgl_kedua,
                    }
                },
                beforeSend: function(req) {
                    req.setRequestHeader("Accept", "application/json");
                },
            },
            searching: false,
            dom: "<'d-flex align-items-center justify-content-between'<'text-center'l><'text-center'f><'text-center'B>>" +
                "<'row'<'col-sm-12 col-md-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            initComplete: function() {
                toastr.success('Data berhasil dimuat.', 'Success!');
            },
            buttons: [{
                    extend: 'copy',
                    text: '<i class="mr-1 fas fa-copy"></i> Salin',
                    className: 'btn btn-info mb-3',
                    title : 'data-monitoring-rawat-inap-{{ date('dmy') }}'
                },
                {
                    extend: 'csv',
                    text: '<i class="mr-1 fas fa-file-csv"></i> CSV',
                    className: 'btn btn-info mb-3',
                    title : 'data-monitoring-rawat-inap-{{ date('dmy') }}'
                },
                {
                    extend: 'excel',
                    text: '<i class="mr-1 fas fa-file-excel"></i> Excel',
                    className: 'btn btn-info mb-3',
                    title : 'data-monitoring-rawat-inap-{{ date('dmy') }}'
                },
            ],
            columns: [{
                    name: 'nm_dokter',
                    data: 'nm_dokter',
                    render: function(data, type, row) {
                        return '<div class="text-nowrap">' + data + ' <br /> <span class="text-muted">' + row.kd_dokter + '</span></div>';
                    }
                },
                {
                    data: 'jumlah_reg_periksa',
                    name: 'jumlah_reg_periksa'
                },
                {
                    data: 'jPemeriksaanRalan',
                    name: 'jPemeriksaanRalan'
                },
                {
                    data: 'jPenilaianMedisRalan',
                    name: 'jPenilaianMedisRalan'
                },
                {
                    data: 'jResepObat',
                    name: 'jResepObat'
                },
            ],
        });
    }

    function loadData(tgl_pertama = null, tgl_kedua = null) {
        setDateFilter(
            moment(tgl_pertama).format('DD MMMM YYYY'),
            moment(tgl_kedua).format('DD MMMM YYYY')
        );
        
        $('#table-pengisian-erm-ranap').DataTable().destroy();
        $('#table-pengisian-erm-ralan').DataTable().destroy();

        ajaxRanap(tgl_pertama, tgl_kedua);
        ajaxRalan(tgl_pertama, tgl_kedua);
    }

    $('.card-ranap #tanggal').on('apply.daterangepicker', function(env, picker) {
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

        // set to bu
        $(".card-ranap #bulan").html(tgl1 + ' s/d ' + tgl2);

        $('#table-pengisian-erm-ranap').DataTable().destroy();
        ajaxRanap(tgl_pertama, tgl_kedua);
    });

    $('.card-ralan #tanggal').on('apply.daterangepicker', function(env, picker) {
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

        $(".card-ralan #bulan").html(tgl1 + ' s/d ' + tgl2);

        $('#table-pengisian-erm-ralan').DataTable().destroy();
        ajaxRalan(tgl_pertama, tgl_kedua);
    });

    function setDateFilter(tgl1, tgl2) {
        $(".card-ranap #bulan").html(tgl1 + ' s/d ' + tgl2);
        $(".card-ralan #bulan").html(tgl1 + ' s/d ' + tgl2);
    }

    loadData(
        moment().startOf('month').format('YYYY-MM-DD'),
        moment().endOf('month').format('YYYY-MM-DD')
    );
</script>
@endpush