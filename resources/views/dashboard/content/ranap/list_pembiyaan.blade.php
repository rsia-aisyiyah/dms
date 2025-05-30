    <div class="col-12 col-sm-12 col-md-8">
        <div class="card card-teal">
            <div class="card-header">
                <p class="card-title border-bottom-0">Pasien Rawat Inap</p>
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
                                    <input type="text" id="yearpickerRanap" class="form-control datetimepicker-input" data-toggle="datetimepicker" aria-describedby="tahun-addon" data-target="#yearpickerRanap" autocomplete="off" />
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered text-sm" id="tbPasienRanap" style="width: 100%" cellspacing="0">
                                {{-- <tr>
                                        <th>Bulan</th>
                                        <th>Kandungan</th>
                                        <th>Anak</th>
                                        <th>Dalam</th>
                                    </tr> --}}
                                <thead>
                                    <!-- Grouped Header Row -->
                                    <tr>
                                        <th rowspan="2">Bulan</th>
                                        <th colspan="3" class="text-center">Obgyn</th>
                                        <th colspan="3" class="text-center">Anak</th>
                                        <th colspan="3" class="text-center">Bayi</th>
                                        <th rowspan="2" class="text-center">Total</th>
                                    </tr>
                                    <!-- Sub-headers -->
                                    <tr>
                                        <th class="bg-info text-white">BPJS</th>
                                        <th class="bg-success text-white">Umum</th>
                                        <th class="bg-warning text-dark">T. Obgyn</th>

                                        <th class="bg-info text-white">BPJS</th>
                                        <th class="bg-success text-white">Umum</th>
                                        <th class="bg-warning text-dark">T. Anak</th>

                                        <th class="bg-info text-white">BPJS</th>
                                        <th class="bg-success text-white">Umum</th>
                                        <th class="bg-warning text-dark">T. Bayi</th>

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


                $('#yearpickerRanap').datetimepicker({
                    format: "YYYY",
                    useCurrent: false,
                    viewMode: "years"
                });

                renderTbPasienRanap();

                function renderTbPasienRanap(tahun) {
                    $('#tbPasienRanap').DataTable({
                        ajax: {
                            url: `${url}/ranap/pembiayaan/json`,
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
                        info: false,
                        dom: 'Blfrtip',
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
                                title: 'laporan-jumlah-kunjungan-poli-{{ date('dmy') }}'
                            },
                            {
                                extend: 'csv',
                                text: '<i class="fas fa-file-csv"></i> CSV',
                                className: 'btn btn-info',
                                title: 'laporan-jumlah-kunjungan-poli-{{ date('dmy') }}'
                            },
                            {
                                extend: 'excel',
                                text: '<i class="fas fa-file-excel"></i> Excel',
                                className: 'btn btn-info',
                                title: 'laporan-jumlah-kunjungan-poli-{{ date('dmy') }}'
                            },
                        ],
                        columns: [{
                                data: 'bulan',
                                name: 'bulan'
                            },
                            {
                                data: 'data.kandungan.BPJS',
                                render: (data, type, row, meta) => {
                                    return data ? data : 0;
                                },
                                name: 'data.kandungan.BPJS'

                            },
                            {
                                data: 'data.kandungan.UMUM',
                                render: (data, type, row, meta) => {
                                    return data ? data : 0;

                                },
                                name: 'data.kandungan.UMUM'
                            },
                            {
                                data: 'data.kandungan',
                                render: (data, type, row, meta) => {
                                    const bpjs = data.BPJS ? data.BPJS : 0;
                                    const umum = data.UMUM ? data.UMUM : 0;

                                    return bpjs + umum;
                                },
                                name: 'obgyn.total'
                            },
                            {
                                data: 'data.anak.BPJS',
                                render: (data, type, row, meta) => {
                                    return data ? data : 0;
                                },
                                name: 'data.anak.BPJS'

                            },
                            {
                                data: 'data.anak.UMUM',
                                render: (data, type, row, meta) => {
                                    return data ? data : 0;

                                },
                                name: 'data.anak.UMUM'
                            },
                            {
                                data: 'data.anak',
                                render: (data, type, row, meta) => {
                                    const bpjs = data?.BPJS ? data?.BPJS : 0;
                                    const umum = data?.UMUM ? data?.UMUM : 0;

                                    return bpjs + umum;
                                },
                            },
                            {
                                data: 'data.bayi.BPJS',
                                render: (data, type, row, meta) => {
                                    return data ? data : 0;
                                },
                                name: 'data.bayi.BPJS'

                            },
                            {
                                data: 'data.bayi.UMUM',
                                render: (data, type, row, meta) => {
                                    return data ? data : 0;

                                },
                                name: 'data.bayi.UMUM'
                            },
                            {
                                data: 'data.bayi',
                                render: (data, type, row, meta) => {
                                    const bpjs = data?.BPJS ? data?.BPJS : 0;
                                    const umum = data?.UMUM ? data?.UMUM : 0;

                                    return bpjs + umum;
                                },
                            },
                            {
                                data: '',
                                render: (data, type, row, meta) => {
                                    const kandungan = row.data.kandungan.total;
                                    const anak = row.data.anak.total;
                                    const bayi = row.data.bayi.total;

                                    return kandungan + anak + bayi;
                                },
                            },

                        ],
                    });
                }

                $('#yearpickerRanap').on('change.datetimepicker', function() {
                    var tahun = $(this).val();
                    $('#tbPasienRanap').DataTable().destroy();
                    renderTbPasienRanap(tahun);
                });

            });
        </script>
    @endpush
