@extends('dashboard.layouts.main')

@section('content')
<style>
    .container-fluid h1 {
        display: none;
    }
    .content-header {
        padding : 0px;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="card card-teal">
            <div class="card-header">
                <h3 class="card-title">Data Pasien</h3>
                <div class="card-tools" id="bulan">
                    {{-- <span><strong>{{ $month }}</strong></span> --}}
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-3 col-md-6">
                        <div class="form-group">
                            <label>Tanggal :</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="tanggal" name="tanggal"
                                    autocomplete="off" />
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3 col-md-6">
                        <div class="form-group">
                            <label>Pembiayaan :</label>
                            <select name="pembiayaan" id="pembiayaan" class="custom-select form-control-border">
                                <option value="all">BPJS & Umum</option>
                                <option value="bpjs">BPJS</option>
                                <option value="umum">UMUM</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive text-sm">
                            <table id="table-monitoring-ugd" class="table table-bordered dataTable" width="100%"
                                cellspacing="0">
                                <thead>
                                    <tr role="row">
                                        <th>NAMA PASIEN</th>
                                        <th class="sr-only">NO RAWAT</th>
                                        <th class="sr-only">PENJAB</th>
                                        <th>SEP</th>
                                        <th>CPPT RJ</th>
                                        <th>RM TRIASE UGD</th>
                                        <th>GENERAL CONSENT</th>
                                        <th>ASKEP UGD</th>
                                        <th>ASKEP KEBIDANAN</th>
                                        <th>ASMEND UGD</th>
                                        <th>FORM RESEP</th>
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
    var tgl_kedua = '';
    var tgl_pertama = '';
    const token = '{{ Session::get('token') }}';

    $('#tanggal').daterangepicker({
        locale: {
            language: 'id',
            applyLabel: 'Terapkan',
            cancelLabel: 'Batal',
            format: 'DD/MM/YYYY',
            daysOfWeek: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Ming'],
            monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
                'September', 'Oktober', 'November', 'Desember'
            ],
        },
        startDate: moment().startOf('month'),
        autoclose: true,
        showDropdowns: true,
        minYear: 2019,
        maxYear: {{ date('Y') + 1 }},
    });

    function loadData(tgl_pertama = null, tgl_kedua = null, pembiayaan = null) {
        $('#table-monitoring-ugd').DataTable({
            fixedHeader: true,
            lengthMenu: [10, 25, 50, 75, 100],
            scrollY: 500,
            processing: true,
            serverSide: true,
            pageLength: 10,
            ajax : {
                url:  `{{ env('APP_URL') }}/api/monitor/rme/ugd?datatables=true`,
                type: 'GET',
                data: {
                    tgl_registrasi: {
                        start: tgl_pertama,
                        end: tgl_kedua,
                    },
                    pembiayaan: pembiayaan,
                },
                beforeSend: function (request) {
                    request.setRequestHeader("Authorization", "Bearer " + token);
                },
            },
            responsive: true,
            searching: true,
            lengthChange: true,
            ordering: false,
            scrollX: true,
            dom: "<'d-flex align-items-center justify-content-between'<'text-center'l><'text-center'f><'text-center'B>>" +
                "<'row'<'col-sm-12 col-md-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            // scrollY: 300,
            // deferRender: true,
            // scroller: {
            //     loadingIndicator: true
            // },
            language: {
                processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i> <span class="sr-only">Loading...</span>',
                zeroRecords: "Tidak Ditemukan Data",
                infoEmpty: "",
                info: "Menampilkan sebanyak _START_ ke _END_ dari _TOTAL_ data",
                loadingRecords: "Sedang memuat ...",
                infoFiltered: "(Disaring dari _MAX_ total baris)",
                buttons: {
                    copyTitle: 'Data telah disalin',
                    copySuccess: {
                        _: '%d baris data telah disalin',
                    },
                },
                paginate: {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                },
            },
            initComplete: function(settings, json) {
                toastr.success('Data telah dimuat', 'Berhasil');
            },
            buttons: [{
                    extend: 'copy',
                    text: '<i class="mr-1 fas fa-copy"></i> Salin',
                    className: 'btn btn-info mb-3',
                    title : 'data-monitoring-ugd-{{ date('dmy') }}'
                },
                {
                    extend: 'csv',
                    text: '<i class="mr-1 fas fa-file-csv"></i> CSV',
                    className: 'btn btn-info mb-3',
                    title : 'data-monitoring-ugd-{{ date('dmy') }}'
                },
                {
                    extend: 'excel',
                    text: '<i class="mr-1 fas fa-file-excel"></i> Excel',
                    className: 'btn btn-info mb-3',
                    title : 'data-monitoring-ugd-{{ date('dmy') }}'
                },
            ],
            columnDefs: [{
                "defaultContent": "-",
                "targets": "_all"
            }],
            columns: [{
                    name: 'pasien.nm_pasien',
                    render: function (data, type, row) {
                        const penjab = row.penjab.png_jawab;
                        const pjb = penjab.includes('BPJS') ? 'BPJS' : "UMUM";
                        const pjbClass = penjab.includes('BPJS') ? 'badge badge-success' : "badge badge-warning";

                        return `<div><b>${row.pasien.nm_pasien}</b><br /><span class="sr-only">-</span>${row.no_rawat}<br /><span class="sr-only">-</span><span class="${pjbClass}">${pjb}</span></div>`;
                    }
                },
                {
                    name: 'no_rawat',
                    visible: false,
                    data: 'no_rawat'
                },
                {
                    name: 'penjab.png_jawab',
                    visible: false,
                    data: 'penjab.png_jawab'
                },
                {
                    name: 'bridging_sep',
                    render: function (data, type, row) {
                        return row.bridging_sep != null ? '<span class="sr-only">sudah</span><i class="fas fa-check text-success"></i>' : '<span class="sr-only">belum</span><i class="fas fa-times text-danger"></i>';
                    }
                },
                {
                    name: 'pemeriksaan_ralan',
                    render: function (data, type, row) {
                        return row.pemeriksaan_ralan != null ? '<span class="sr-only">sudah</span><i class="fas fa-check text-success"></i>' : '<span class="sr-only">belum</span><i class="fas fa-times text-danger"></i>';
                    }
                },
                {
                    name: 'triase_igd',
                    render: function (data, type, row) {
                        return row.data_triase_igd != null ? '<span class="sr-only">sudah</span><i class="fas fa-check text-success"></i>' : '<span class="sr-only">belum</span><i class="fas fa-times text-danger"></i>';
                    }
                },
                {
                    name: 'general_consent',
                    render: function (data, type, row) {
                        return row.rsia_general_consent != null ? '<span class="sr-only">sudah</span><i class="fas fa-check text-success"></i>' : '<span class="sr-only">belum</span><i class="fas fa-times text-danger"></i>';
                    }
                },
                {
                    name: 'penilaian_awal_igd',
                    render: function (data, type, row) {
                        return row.penilaian_awal_keperawatan_igd != null ? '<span class="sr-only">sudah</span><i class="fas fa-check text-success"></i>' : '<span class="sr-only">belum</span><i class="fas fa-times text-danger"></i>';
                    }
                },
                {
                    name: 'penilaian_awal_kebidanan',
                    render: function (data, type, row) {
                        return row.penilaian_awal_keperawatan_kebidanan != null ? '<span class="sr-only">sudah</span><i class="fas fa-check text-success"></i>' : '<span class="sr-only">belum</span><i class="fas fa-times text-danger"></i>';
                    }
                },
                {
                    name: 'penilaian_medis_igd',
                    render: function (data, type, row) {
                        return row.penilaian_medis_igd != null ? '<span class="sr-only">sudah</span><i class="fas fa-check text-success"></i>' : '<span class="sr-only">belum</span><i class="fas fa-times text-danger"></i>';
                    }
                },
                {
                    name: 'resep_obat',
                    render: function (data, type, row) {
                        return row.resep_obat != null ? '<span class="sr-only">sudah</span><i class="fas fa-check text-success"></i>' : '<span class="sr-only">belum</span><i class="fas fa-times text-danger"></i>';
                    }
                },
            ],
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

        $('#bulan').html('<strong>' + tgl1 + ' s/d ' + tgl2 + '</strong>');
        $('#table-monitoring-ugd').DataTable().destroy();

        loadData(tgl_pertama, tgl_kedua);
    });

    $('#pembiayaan').change(function() {
        $('#table-monitoring-ugd').DataTable().destroy();

        var pembiayaan = $(this).val();
        loadData(tgl_pertama, tgl_kedua, pembiayaan);
    });

    loadData();
</script>
@endpush