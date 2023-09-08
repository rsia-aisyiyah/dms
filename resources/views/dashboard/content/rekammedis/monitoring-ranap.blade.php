@extends('dashboard.layouts.main')

@section('content')
<style>
    .container-fluid h1 {
        display: none;
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
                    <div class="col-sm-3 col-md-4">
                        <div class="form-group">
                            <label>Tanggal :</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="tanggal" name="tanggal"
                                    autocomplete="off" />
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3 col-md-4">
                        <div class="form-group">
                            <label>Pembiayaan :</label>
                            <select name="pembiayaan" id="pembiayaan" class="custom-select form-control-border">
                                <option value="all">BPJS & Umum</option>
                                <option value="bpjs">BPJS</option>
                                <option value="umum">UMUM</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3 col-md-4">
                        <div class="form-group">
                            <label>Status :</label>
                            <select name="status" id="status" class="custom-select form-control-border">
                                <option value="all">Semua</option>
                                <option value="pulang">Pulang</option>
                                <option value="belum">Belum Pulang</option>
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
                                        <th>GENERAL CONSENT</th>
                                        <th>ASMED ANAK</th>
                                        <th>ASMED KANDUNGAN</th>
                                        <th>TRANSFER PASIEN</th>
                                        <th>SOAP</th>
                                        <th>TULBAKON</th>
                                        <th>VERIFIKASI (CPPT)</th>
                                        <th>EWS</th>
                                        <th>GRAFIK SUHU</th>
                                        <th>REKONSILIASI OBAT</th>
                                        <th>SKRINING GIZI</th>
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
    const token = '{{ Session::get('token') }}';
    const apiUrl = "{{ env('API_URL') }}";

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

    function loadData(tgl_pertama = null, tgl_kedua = null, pembiayaan = null, status = null) {
        $('#table-monitoring-ugd').DataTable({
            fixedHeader: true,
            pageLength: 10,
            lengthMenu: [10, 25, 50, 75, 100],
            scrollY: 300,
            processing: true,
            serverSide: true,
            ajax : {
                url: apiUrl + 'monitor/rme/ranap?datatables=true',
                type: 'GET',
                data: {
                    pembiayaan: pembiayaan,
                    status: status,
                    tgl: {
                        start: tgl_pertama,
                        end: tgl_kedua,
                    },
                },
                beforeSend: function (request) {
                    request.setRequestHeader("Authorization", "Bearer " + token);
                },
            },
            searching: true,
            lengthChange: true,
            ordering: false,
            scrollX: true,
            // dom: 'Blfrtip',
            dom: "<'d-flex align-items-center justify-content-between'<'text-center'l><'text-center'f><'text-center'B>>" +
                "<'row'<'col-sm-12 col-md-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            deferRender: true,
            // scrollY: 300,
            // scroller: true,
            initComplete: function(settings, json) {
                toastr.success('Data telah dimuat', 'Berhasil');
            },
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
            columns: [
                {
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
                    data: 'no_rawat',
                },
                {
                    name: 'penjab.png_jawab',
                    visible: false,
                    data: 'penjab.png_jawab'
                },
                {
                    name: 'rsia_general_consent',
                    render: function (data, type, row) {
                        return row.rsia_general_consent != null ? '<span class="sr-only">sudah</span><i class="fas fa-check text-success"></i>' : '<span class="sr-only">belum</span><i class="fas fa-times text-danger"></i>';
                    }
                },
                {
                    render: function (data, type, row) {
                        if (row.penilaian_medis_ralan_anak != null){
                            return '<span class="sr-only">sudah</span><i class="fas fa-check text-success"></i>';
                        } else {
                            return '<span class="sr-only">belum</span><i class="fas fa-times text-danger"></i>';
                        }
                    }
                },
                {
                    render: function (data, type, row) {
                        if (row.penilaian_medis_ralan_kandungan != null){
                            return '<span class="sr-only">sudah</span><i class="fas fa-check text-success"></i>';
                        } else {
                            return '<span class="sr-only">belum</span><i class="fas fa-times text-danger"></i>';
                        }
                    }
                },
                {
                    name: 'transfer_pasien_antar_ruang',
                    render: function (data, type, row) {
                        return row.transfer_pasien_antar_ruang != null ? '<span class="sr-only">sudah</span><i class="fas fa-check text-success"></i>' : '<span class="sr-only">belum</span><i class="fas fa-times text-danger"></i>';
                    }
                },
                {
                    name: 'pemeriksaan_ranap',
                    render: function (data, type, row) {
                        return row.pemeriksaan_ranap != null ? '<span class="sr-only">sudah</span><i class="fas fa-check text-success"></i>' : '<span class="sr-only">belum</span><i class="fas fa-times text-danger"></i>';
                    }
                },
                {
                    name: 'rsia_verif_pemeriksaan_ranap',
                    render: function (data, type, row) {
                        return row.rsia_verif_pemeriksaan_ranap != null ? '<span class="sr-only">sudah</span><i class="fas fa-check text-success"></i>' : '<span class="sr-only">belum</span><i class="fas fa-times text-danger"></i>';
                    }
                },
                {
                    name: 'rsia_verif_pemeriksaan_ranap',
                    render: function (data, type, row) {
                        return row.rsia_verif_pemeriksaan_ranap != null ? '<span class="sr-only">sudah</span><i class="fas fa-check text-success"></i>' : '<span class="sr-only">belum</span><i class="fas fa-times text-danger"></i>';
                    }
                },
                {
                    name: 'pemeriksaan_ranap',
                    render: function (data, type, row) {
                        return row.pemeriksaan_ranap != null ? '<span class="sr-only">sudah</span><i class="fas fa-check text-success"></i>' : '<span class="sr-only">belum</span><i class="fas fa-times text-danger"></i>';
                    }
                },
                {
                    name: 'grafik_harian',
                    render: function (data, type, row) {
                        return row.grafik_harian != null ? '<span class="sr-only">sudah</span><i class="fas fa-check text-success"></i>' : '<span class="sr-only">belum</span><i class="fas fa-times text-danger"></i>';
                    }
                },
                {
                    name: 'rekonsiliasi_obat',
                    render: function (data, type, row) {
                        return row.rekonsiliasi_obat != null ? '<span class="sr-only">sudah</span><i class="fas fa-check text-success"></i>' : '<span class="sr-only">belum</span><i class="fas fa-times text-danger"></i>';
                    }
                },
                {
                    name: 'skrining_gizi',
                    render: function (data, type, row) {
                        return row.skrining_gizi != null ? '<span class="sr-only">sudah</span><i class="fas fa-check text-success"></i>' : '<span class="sr-only">belum</span><i class="fas fa-times text-danger"></i>';
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
        if ($(this).val() == 'all') {
            pembiayaan = null;
        } else {
            pembiayaan = $(this).val();
        }

        loadData(tgl_pertama, tgl_kedua, pembiayaan);
    });

    $('#status').change(function() {
        $('#table-monitoring-ugd').DataTable().destroy();
        if ($(this).val() == 'all') {
            status = null;
        } else {
            status = $(this).val();
        }

        loadData(tgl_pertama, tgl_kedua, pembiayaan, status);
    });

    loadData();
</script>
@endpush