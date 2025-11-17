<div class="col-lg-4 col-sm-12 col-md-12">
    <div class="card card-teal">
        <div class="card-header">
            <p class="card-title border-bottom-0">Rekap Kamar</p>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <label>Bulan</label>
                    <div class="input-group mb-3">
                        <div class="input-group-append">
                            <span class="input-group-text" id="tahun-addon"><i
                                        class="fas fa-calendar"></i></span>
                        </div>
                        <input type="text" class="form-control datetimepicker-input monthpicker" id="tanggal-rekap"
                               name="tanggal-rekap" autocomplete="off" data-toggle="datetimepicker"
                               aria-describedby="tahun-addon" data-target="#monthPickerCpptVisit" autocomplete="off">
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped text-sm" id="tabel-rekap-ranap" style="width: 100%"
                               cellspacing="0">
                            <thead>
                            <tr>
                                <th>Kelas</th>
                                <th>Jumlah Bed</th>
                                <th>Σ Lama Inap</th>
                                <th>Σ Pasien</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-4 col-sm-12 col-md-12">
    <div class="card card-teal">
        <div class="card-header">
            <p class="card-title border-bottom-0">Detail Kamar</p>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <div class="input-group mb-3">
                        <div class="input-group-append">
                            <span class="input-group-text" id="tahun-addon"><i
                                        class="fas fa-calendar"></i></span>
                        </div>
                        <input type="text" class="form-control datetimepicker-input monthpicker" id="filterDetailLama"
                               name="filterDetailLama" autocomplete="off" data-toggle="datetimepicker"
                               aria-describedby="tahun-addon" data-target="#monthPickerCpptVisit" autocomplete="off">
                    </div>
                </div>
                <div class="col-6">
                    <select class="form-control" id="filterKelasKamarDetail">
                        <option value="" selected>Semua Kelas</option>
                        <option value="Kelas 1">Kelas 1</option>
                        <option value="Kelas 2">Kelas 2</option>
                        <option value="Kelas 3">Kelas 3</option>
                        <option value="Kelas Utama">Kelas Utama</option>
                    </select>
                </div>
                <div class="col-12">
                    <label>Bulan</label>


                    <div class="table-responsive">
                        <table class="table table-striped text-sm" id="tbDetailLamaInap" style="width: 100%"
                               cellspacing="0">
                            <thead>
                            <tr>
                                <th>Nama Kamar</th>
                                <th>Kelas</th>
                                <th>Σ Lama Inap</th>
                                <th>Σ Pasien</th>
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
        $(document).ready(function () {

            $('.monthpicker').datetimepicker({
                format: "MM-YYYY",
                useCurrent: false,
                viewMode: "months",

            });

            $('#tanggal-rekap').on('change.datetimepicker', function () {
                var date = $(this).val().split('-');
                bulan = date[0];
                tahun = date[1];
                load_data(bulan, tahun);
            });

            $('#filterDetailLama').on('change.datetimepicker', function () {
                var date = $(this).val().split('-');
                bulan = date[0];
                tahun = date[1];
                loadDataLamaInap(bulan, tahun);
            });


            load_data();
            loadDataLamaInap();

            function load_data(bulan = '', tahun = '') {
                $('#tabel-rekap-ranap').DataTable({
                    ajax: {
                        url: 'kamar/rekap',
                        dataType: 'json',
                        data: {
                            tahun: tahun,
                            bulan: bulan,
                        },
                    },
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    deferRender: true,
                    lengthChange: false,
                    ordering: false,
                    searching: false,
                    stateSave: true,
                    paging: false,
                    dom: 'Blfrtip',
                    info: false,
                    initComplete: function (settings, json) {
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
                            data: 'lama',
                            name: 'lama'
                        },
                        {
                            data: 'data',
                            name: 'data'
                        },
                    ],
                });
            }

            function loadDataLamaInap(bulan = '', tahun = '', kelas = '') {
                $('#tbDetailLamaInap').DataTable({
                    ajax: {
                        url: '/dms/kamar/detail/rekap',
                        dataType: 'json',
                        data: {
                            tahun: tahun,
                            bulan: bulan,
                            kelas: kelas,
                        },
                    },
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    deferRender: true,
                    lengthChange: true,
                    ordering: false,
                    searching: false,
                    stateSave: false,
                    paging: true,
                    scrolY: '20vh',
                    scrolX: 'true',
                    dom: 'Blfrtip',
                    info: false,
                    initComplete: function (settings, json) {
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
                    columns: [
                        {
                            data: 'nm_bangsal',
                            name: 'nm_bangsal'
                        }, {
                            data: 'kelas',
                            name: 'kelas'
                        },
                        {
                            data: 'total_lama_inap',
                            name: 'total_lama_inap'
                        }, {
                            data: 'total_pasien',
                            name: 'total_pasien'
                        },

                    ],
                });
            }


            $('#filterKelasKamarDetail').change(function (e) {
                const date = $('#filterDetailLama').val().split('-');
                const kelas = e.currentTarget.value
                let bulan = date[0];
                let tahun = date[1];
                loadDataLamaInap(bulan, tahun, kelas);

            })
        });
    </script>
@endpush
