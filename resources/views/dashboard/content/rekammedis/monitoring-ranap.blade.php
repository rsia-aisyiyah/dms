@extends('dashboard.layouts.main')

@section('content')
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
    const token = '{{ Session::get('token') }}';
    function loadData() {
        $('#table-monitoring-ugd').DataTable({
            ajax : {
                url: 'http://localhost/rsiapi/api/monitor/rme/ranap?datatables=true',
                type: 'GET',
                beforeSend: function (request) {
                    request.setRequestHeader("Authorization", "Bearer " + token);
                },
            },
            processing: true,
            searching: true,
            serverSide: true,
            lengthChange: false,
            ordering: false,
            scrollY: "350px",
            scrollX: true,
            paging: true,
            dom: 'Blfrtip',
            initComplete: function(settings, json) {
                toastr.success('Data telah dimuat', 'Berhasil');
            },
            buttons: [{
                    extend: 'copy',
                    text: '<i class="mr-1 fas fa-copy"></i> Salin',
                    className: 'btn btn-info',
                    title : 'data-monitoring-rawat-inap-{{ date('dmy') }}'
                },
                {
                    extend: 'csv',
                    text: '<i class="mr-1 fas fa-file-csv"></i> CSV',
                    className: 'btn btn-info',
                    title : 'data-monitoring-rawat-inap-{{ date('dmy') }}'
                },
                {
                    extend: 'excel',
                    text: '<i class="mr-1 fas fa-file-excel"></i> Excel',
                    className: 'btn btn-info',
                    title : 'data-monitoring-rawat-inap-{{ date('dmy') }}'
                },
            ],
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
                    name: 'rsia_grafik_harian',
                    render: function (data, type, row) {
                        return row.rsia_grafik_harian != null ? '<span class="sr-only">sudah</span><i class="fas fa-check text-success"></i>' : '<span class="sr-only">belum</span><i class="fas fa-times text-danger"></i>';
                    }
                },
                {
                    name: 'rekonsiliasi_obat',
                    render: function (data, type, row) {
                        return row.rekonsiliasi_obat != null ? '<span class="sr-only">sudah</span><i class="fas fa-check text-success"></i>' : '<span class="sr-only">belum</span><i class="fas fa-times text-danger"></i>';
                    }
                },
                {
                    name: 'rsia_skrining_gizi',
                    render: function (data, type, row) {
                        return row.rsia_skrining_gizi != null ? '<span class="sr-only">sudah</span><i class="fas fa-check text-success"></i>' : '<span class="sr-only">belum</span><i class="fas fa-times text-danger"></i>';
                    }
                },
            ],
        });
    }

    loadData();
</script>
@endpush