<div class="col-12 col-sm-12 col-md-5">
    <div class="card card-teal">
        <div class="card-header">
            <p class="card-title border-bottom-0">Rekap Kamar</p>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <label>Tanggal Pulang</label>
                    <div class="input-group mb-3">
                        <div class="input-group-append">
                            <span class="input-group-text" id="tahun-addon"><i
                                    class="fas fa-calendar"></i></span>
                        </div>
                        <input type="text" class="form-control" id="tanggal-rekap" name="tanggal" autocomplete="off">
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped text-sm" id="tabel-rekap-ranap" style="width: 100%"
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

            dateRange('#tanggal-rekap');

            load_data();

            function load_data(tahun) {
                $('#tabel-rekap-ranap').DataTable({
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

            $('#tanggal-rekap').on('change.datetimepicker', function() {
                var tahun = $(this).val();
                $('#tabel-rekap-ranap').DataTable().destroy();
                load_data(tahun);
            });

        });
    </script>
@endpush
