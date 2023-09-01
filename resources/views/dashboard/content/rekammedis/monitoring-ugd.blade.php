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
    const token = '{{ Session::get('token') }}';
    function loadData() {
        $('#table-monitoring-ugd').DataTable({
            ajax : {
                url: 'http://localhost/rsiapi/api/monitor/rme/ugd?datatables=true',
                type: 'GET',
                beforeSend: function (request) {
                    request.setRequestHeader("Authorization", "Bearer " + token);
                },
            },
            responsive: true,
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
                    title : 'data-monitoring-ugd-{{ date('dmy') }}'
                },
                {
                    extend: 'csv',
                    text: '<i class="mr-1 fas fa-file-csv"></i> CSV',
                    className: 'btn btn-info',
                    title : 'data-monitoring-ugd-{{ date('dmy') }}'
                },
                {
                    extend: 'excel',
                    text: '<i class="mr-1 fas fa-file-excel"></i> Excel',
                    className: 'btn btn-info',
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

    loadData();
</script>
@endpush