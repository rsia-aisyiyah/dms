@extends('dashboard.layouts.main')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-teal">
                <div class="card-header">
                    <h3 class="card-title">{{ $title }}</h3>
                    <div class="card-tools mr-4" id="bulan">
                        <span><strong>{{ $month }}</strong></span>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="">Tanggal</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="tanggal" name="tanggal"
                                        autocomplete="off" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="">Dokter</label>
                                <div class="input-group">
                                    <select name="dokter" id="dokter" class="custom-select form-control-border">
                                        <option value="">Semua Dokter</option>
                                        <option value="1.101.1112">dr. Himawan Budityastomo, SpOG</option>
                                        <option value="1.109.1119">dr. Siti Pattihatun Nasyiroh, SpOG</option>
                                        <option value="1.113.1023">dr. Achmad Dahlan Kadir, Sp.OG</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="">Pembiayaan</label>
                                <div class="input-group">
                                    <select name="pembiayaan" id="pembiayaan" class="custom-select form-control-border">
                                        <option value="">BPJS & UMUM</option>
                                        <option value="bpjs">BPJS</option>
                                        <option value="umum">UMUM</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="text-sm table-responsive">
                                <table id="tabel-persalinan" class="table table-bordered dataTable" width="100%"
                                    cellspacing="0">
                                    <thead>
                                        <tr role="row">
                                            <th>Nomor Rawat</th>
                                            <th>NIK</th>
                                            <th>Nama</th>
                                            <th>Umut</th>
                                            <th>Tanggal Lahir</th>
                                            <th>Suami</th>
                                            <th>Alamat</th>
                                            <th>Kecamatan</th>
                                            <th>Tanggal Persalinan</th>
                                            <th>Jenis Persalinan</th>
                                            <th>Diagnosa</th>
                                            <th>Riwayat Hamil</th>
                                            <th>JK</th>
                                            <th>BB</th>
                                            <th>TB</th>
                                            <th>Dokter</th>
                                            <th>Pembiayaan</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('dashboard.content.persalinan.diagram_persalinan')
    @endsection

    @push('scripts')
        <script>
            var tgl_pertama = '';
            var tgl_kedua = '';


            $(document).ready(function() {

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
                    $('#tabel-persalinan').DataTable().destroy();

                    load_data(tgl_pertama, tgl_kedua);
                });

                load_data();


                function load_data(tgl_pertama, tgl_kedua, dokter, pembiayaan) {

                    var dokter = $('#dokter').val();
                    var pembiayaan = $('#pembiayaan').val();

                    $('#tabel-persalinan').DataTable({
                        processing: true,
                        serverSide: true,
                        destroy: false,
                        searching: true,
                        ajax: {
                            url: 'persalinan/json',
                            data: {
                                tgl_pertama: tgl_pertama,
                                tgl_kedua: tgl_kedua,
                                dokter: dokter,
                                pembiayaan: pembiayaan,
                            }
                        },
                        scrollY: "350px",
                        scrollX: true,
                        scroller: {
                            loadingIndicator: true
                        },
                        language: {
                            processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i> <span class="sr-only">Loading...</span>'
                        },
                        paging: true,
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
                            lengthMenu: '<div class="text-md mt-3">Tampilkan <select>' +
                                '<option value="50">50</option>' +
                                '<option value="100">100</option>' +
                                '<option value="200">200</option>' +
                                '<option value="250">250</option>' +
                                '<option value="500">500</option>' +
                                '<option value="-1">Semua</option>' +
                                '</select> Baris',
                            paginate: {
                                "first": "Pertama",
                                "last": "Terakhir",
                                "next": "Selanjutnya",
                                "previous": "Sebelumnya"
                            },
                            search: 'Cari Dokter : ',
                        },
                        buttons: [{
                                extend: 'copy',
                                text: '<i class="fas fa-copy"></i> Salin',
                                className: 'btn btn-info',
                                title: 'laporan-kunjungan-pasien-rawat-jalan{{ date('dmy') }}'
                            },
                            {
                                extend: 'csv',
                                text: '<i class="fas fa-file-csv"></i> CSV',
                                className: 'btn btn-info',
                                title: 'laporan-kunjungan-pasien-rawat-jalan{{ date('dmy') }}'
                            },
                            {
                                extend: 'excel',
                                text: '<i class="fas fa-file-excel"></i> Excel',
                                className: 'btn btn-info',
                                title: 'laporan-kunjungan-pasien-rawat-jalan{{ date('dmy') }}'
                            },
                        ],
                        columns: [{
                                data: 'no_rawat',
                                render: (data, meta, row, x) => {
                                    return row.no_rawat;
                                },
                                name: 'no_rawat',
                            },
                            {
                                data: '',
                                render: (data, meta, row, x) => {

                                    return row.reg_periksa?.pasien.no_ktp;
                                },
                                name: 'nik',
                            },
                            {
                                data: '',
                                render: (data, meta, row, x) => {

                                    return row.reg_periksa?.pasien?.nm_pasien;
                                },
                                name: 'pasien',
                            },
                            {
                                data: 'umur',
                                name: 'umur',
                            },
                            {
                                data: 'tgl_lahir',
                                name: 'tgl_lahir',
                            },
                            {
                                data: 'suami',
                                name: 'suami',
                            },
                            {
                                data: 'alamat',
                                name: 'alamat',
                            },
                            {
                                data: '',
                                name: 'kecamatan',
                                render: (data, type, row, meta) => {
                                    return row.reg_periksa.pasien.kecamatanpj
                                }
                            },
                            {
                                data: 'tgl_perawatan',
                                name: 'tgl_perawatan',
                            },
                            {
                                data: 'nm_perawatan',
                                name: 'nm_perawatan',
                            },
                            {
                                data: '',
                                name: 'diagnosa',
                                render: (data, type, row, meta) => {
                                    const diagnosa = row.reg_periksa.diagnosa_pasien;
                                    if (diagnosa)
                                        return `${row.reg_periksa.diagnosa_pasien.kd_penyakit} - ${row.reg_periksa.diagnosa_pasien.penyakit.nm_penyakit}`
                                    else
                                        return `-`
                                }
                            },
                            {
                                data: 'status_hamil',
                                name: 'status_hamil',
                            },
                            {
                                data: 'bayi',
                                name: 'bayi',
                            },
                            {
                                data: 'bb',
                                name: 'bb',
                            },
                            {
                                data: 'tb',
                                name: 'tb',
                            },
                            {
                                data: 'dokter',
                                name: 'dokter',
                            },
                            {
                                data: 'pembiayaan',
                                name: 'pembiayaan',
                            },
                        ],
                    });
                }

                $('#dokter').change(function() {
                    if (tgl_pertama == '' && tgl_kedua == '') {

                        var hari1 = moment().startOf('month').date();
                        var hari2 = moment().endOf('month').date();
                        var bulan = moment().month() + 1;
                        var tahun = moment().startOf('month').year();


                        tgl_pertama = tahun + '-' + bulan + '-' + hari1;
                        tgl_kedua = tahun + '-' + bulan + '-' + hari2;

                    }
                    $('#tabel-persalinan').DataTable().destroy();
                    load_data(tgl_pertama, tgl_kedua, $(this).val());
                });

                $('#pembiayaan').change(function() {
                    if (tgl_pertama == '' && tgl_kedua == '') {

                        var hari1 = moment().startOf('month').date();
                        var hari2 = moment().endOf('month').date();
                        var bulan = moment().month() + 1;
                        var tahun = moment().startOf('month').year();


                        tgl_pertama = tahun + '-' + bulan + '-' + hari1;
                        tgl_kedua = tahun + '-' + bulan + '-' + hari2;

                    }
                    $('#tabel-persalinan').DataTable().destroy();
                    load_data(tgl_pertama, tgl_kedua, dokter, $(this).val());
                });
            });
        </script>
    @endpush
