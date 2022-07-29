<div class="col-12 col-sm-12 col-md-4">
    <div class="card card-teal">
        <div class="card-header">
            <p class="card-title border-bottom-0">Rekap Kamar Tahunan</p>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-6">
                            <label>Tahun</label>
                            <div class="input-group mb-3">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="tahun-addon"><i
                                            class="fas fa-calendar"></i></span>
                                </div>
                                <input type="text" id="tahun-rekap" class="form-control datetimepicker-input"
                                    data-toggle="datetimepicker" aria-describedby="tahun-addon"
                                    data-target="#tahun-rekap" autocomplete="off" />
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped text-sm" id="tabel-rekap-tahunan" style="width: 100%"
                            cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Kelas</th>
                                    <th>Jumlah Bed</th>
                                    <th>Jumlah Rawat</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        $(document).ready(function() {


            $('#tahun-rekap').datetimepicker({
                format: "YYYY",
                useCurrent: false,
                viewMode: "years"
            });

            load_data();

            function load_data(tahun) {
                $('#tabel-rekap-tahunan').DataTable({
                    ajax: {
                        url: 'kamar/rekap',
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
                    info: false,
                    initComplete: function(settings, json) {
                        toastr.success('Data telah dimuat', 'Berhasil');
                    },
                    language: {
                        processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i> <span class="sr-only">Loading...</span>',
                        zeroRecords: "Tidak Ditemukan Data",
                        infoEmpty: "",
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
                            title: 'rekap-kamar-tahunan-{{ date('dmy') }}'
                        },
                        {
                            extend: 'csv',
                            text: '<i class="fas fa-file-csv"></i> CSV',
                            className: 'btn btn-info',
                            title: 'rekap-kamar-tahunan-{{ date('dmy') }}'
                        },
                        {
                            extend: 'excel',
                            text: '<i class="fas fa-file-excel"></i> Excel',
                            className: 'btn btn-info',
                            title: 'rekap-kamar-tahunan-{{ date('dmy') }}'
                        },
                    ],
                    columns: [{
                            data: 'kelas',
                            name: 'kelas'
                        },
                        {
                            data: 'jumlahKelas',
                            name: 'jumlahKelas'
                        },
                        {
                            data: 'data',
                            name: 'data'
                        },
                    ],
                });
            }

            $('#tahun-rekap').on('change.datetimepicker', function() {
                var tahun = $(this).val();
                $('#tabel-rekap-tahunan').DataTable().destroy();
                load_data(tahun);
            });

        });
    </script>
@endpush
