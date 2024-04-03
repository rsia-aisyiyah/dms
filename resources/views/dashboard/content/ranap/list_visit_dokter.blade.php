@extends('dashboard.layouts.main')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-teal">
                <div class="card-header">
                    <p class="card-title border-bottom-0">{{ $title }} </p>
                    <div class="card-tools mr-4" id="bulan">
                        <span><strong>{{ $month }}</strong></span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-6">
                                    <label>Tahun</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="tahun-addon"><i class="fas fa-calendar"></i></span>
                                        </div>
                                        <input type="text" id="yearpicker" class="form-control datetimepicker-input" data-toggle="datetimepicker" aria-describedby="tahun-addon" data-target="#yearpicker" autocomplete="off" />
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive text-sm">
                                <table class="table table-striped" id="table-visit-dokter" style="width: 100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Bulan</th>
                                            <th>dr. Himawan Budityastomo, SpO.G</th>
                                            <th>dr. Dwi Riyanto, Sp.A</th>
                                            <th>dr. Siti Pattihatun Nasyiroh, Sp.OG</th>
                                            <th>dr. Rendy Yoga Ardian, Sp.A</th>
                                            <th>dr. Achmad Dahlan K. Sp.OG</th>
                                            <th>dr. Pravitasari. Sp.OG</th>
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
        $(document).ready(function() {


            $('#yearpicker').datetimepicker({
                format: "YYYY",
                useCurrent: false,
                viewMode: "years"
            });

            load_data();

            function load_data(tahun) {
                $('#table-visit-dokter').DataTable({
                    ajax: {
                        url: 'visit/json',
                        dataType: 'json',
                        data: {
                            tahun: tahun,
                        },
                    },
                    processing: true,
                    serverSide: true,
                    destroy: false,
                    deferRender: true,
                    lengthChange: false,
                    ordering: false,
                    searching: false,
                    stateSave: true,
                    paging: false,
                    dom: 'Blfrtip',
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
                    },
                    buttons: [{
                            extend: 'copy',
                            text: '<i class="fas fa-copy"></i> Salin',
                            className: 'btn btn-info',
                            title: 'laporan-jumlah-pasien-bayi-{{ date('dmy') }}'
                        },
                        {
                            extend: 'csv',
                            text: '<i class="fas fa-file-csv"></i> CSV',
                            className: 'btn btn-info',
                            title: 'laporan-jumlah-pasien-bayi-{{ date('dmy') }}'
                        },
                        {
                            extend: 'excel',
                            text: '<i class="fas fa-file-excel"></i> Excel',
                            className: 'btn btn-info',
                            title: 'laporan-jumlah-pasien-bayi-{{ date('dmy') }}'
                        },
                    ],
                    columns: [{
                            data: 'bulan',
                            name: 'bulan'
                        },
                        {
                            data: 'dokter1',
                            name: 'dokter1'
                        },
                        {
                            data: 'dokter2',
                            name: 'dokter2'
                        },
                        {
                            data: 'dokter3',
                            name: 'dokter3'
                        },
                        {
                            data: 'dokter4',
                            name: 'dokter4'
                        },
                        {
                            data: 'dokter5',
                            name: 'dokter5'
                        },
                        {
                            data: 'dokter6',
                            name: 'dokter6'
                        },
                    ],
                });
            }

            $('#yearpicker').on('change.datetimepicker', function() {
                var tahun = $(this).val();
                $('#table-visit-dokter').DataTable().destroy();
                load_data(tahun);
            });

        });
    </script>
@endpush
