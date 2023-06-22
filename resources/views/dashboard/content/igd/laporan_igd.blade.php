@extends('dashboard.layouts.main')

@section('content')
    {{-- datatable pasien IGD --}}
    <div class="row">
        <div class="col-12">
            <div class="card card-teal">
                <div class="card-header">
                    <p class="card-title border-bottom-0">Tabel {{ $title }} </p>
                    <div class="card-tools mr-4" id="bulan">
                        <span><strong>{{ $dateNow }}</strong></span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-4 col-lg-4">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Rawat Jalan</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body p-2">
                                    <p class="m-0 text-center">Jumlah Kunjungan</p>
                                    <p class="m-0 text-center tanggal"><strong>{{ $month }}</strong></p>
                                    <h1 id="ralan" class="mt-0 text-center"></h1>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4 col-lg-4">
                            <div class="card card-danger">
                                <div class="card-header">
                                    <h3 class="card-title">Rawat Inap</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body p-2">
                                    <p class="m-0 text-center">Jumlah Kunjungan</p>
                                    <p class="m-0 text-center tanggal"><strong>{{ $month }}</strong></p>
                                    <h1 id="ranap" class="mt-0 text-center"></h1>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4 col-lg-4">
                            <div class="card card-warning">
                                <div class="card-header">
                                    <h3 class="card-title">HCU</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body p-2">
                                    <p class="m-0 text-center">Jumlah Kunjungan</p>
                                    <p class="m-0 text-center tanggal"><strong>{{ $month }}</strong></p>
                                    <h1 id="hcu" class="mt-0 text-center"></h1>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>Tanggal </label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="tanggal" name="tanggal"
                                        autocomplete="off" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-group">
                                <div class="form-group">
                                    <label>Lanjut</label>
                                    <select class="custom-select form-control-border" id="status_lanjut"
                                        name="status_lanjut">
                                        <option value="">Semua</option>
                                        <option value="ranap">Rawat Inap</option>
                                        <option value="ralan">Rawat Jalan</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-group">
                                <div class="form-group">
                                    <label>Pembiayaan</label>
                                    <select class="custom-select form-control-border" id="pembiayaan"
                                        name="pembiayaan">
                                        <option value="">Semua</option>
                                        <option value="BPJS">BPJS</option>
                                        <option value="UMUM">UMUM</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-group">
                                <div class="form-group">
                                    <label>Spesialis </label>
                                    <select class="custom-select form-control-border" id="spesialis" name="spesialis">
                                        <option value="">Semua</option>
                                        <option value="S0007">Umum</option>
                                        <option value="S0003">Anak</option>
                                        <option value="S0001">Kebidanan dan Kandungan</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <div class="form-group">
                                    <label>Dokter </label>
                                    <select class="custom-select form-control-border" id="dokter" name="dokter">
                                        <option value="">Semua</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive text-sm">
                                <table class="table table-bordered" id="tabel-pasien-igd" style="width: 100%"
                                    cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No Rawat</th>
                                            <th>Tanggal Registrasi</th>
                                            <th>Nama (No. RM)</th>
                                            <th>Alamat</th>
                                            <th>Pembiayaan</th>
                                            <th>Dokter PJ</th>
                                            <th>Spesialis</th>
                                            <th>SEP</th>
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
        var tgl_pertama = '',
            tgl_kedua = '',
            status_lanjut = '',
            spesialis = '',
            pembiayaan = '';
        $(document).ready(function() {

            load_data();
            load_tabel();

            function load_data(tgl_pertama = '', tgl_kedua = '') {
                $.ajax({
                    url: 'igd/json',
                    data: {
                        tgl_pertama: tgl_pertama,
                        tgl_kedua: tgl_kedua
                    },
                    dataType: 'json',
                    success: function(data) {
                        document.getElementById('ranap').innerHTML = data.ranap;
                        document.getElementById('ralan').innerHTML = data.ralan;
                        document.getElementById('hcu').innerHTML = data.hcu;
                        toastr.success('Data telah dimuat', 'Berhasil');
                    }
                });
            }

            function load_tabel(tgl_pertama, tgl_kedua, status_lanjut, spesialis, dokter) {
                $('#tabel-pasien-igd').DataTable({
                    ajax: {
                        url: 'igd/pasien/json',
                        dataType: 'json',
                        data: {
                            tgl_pertama: tgl_pertama,
                            tgl_kedua: tgl_kedua,
                            status_lanjut: status_lanjut,
                            spesialis: spesialis,
                            pembiayaan: pembiayaan,
                            dokter: dokter,
                        },
                    },
                    processing: true,
                    serverSide: true,
                    destroy: false,
                    deferRender: true,
                    lengthChange: true,
                    ordering: false,
                    searching: true,
                    scrollY: 300,
                    scrollX: true,
                    scroller: {
                        loadingIndicator: true
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
                        search: 'Cari Pasien : ',
                    },
                    buttons: [{
                            extend: 'copy',
                            text: '<i class="fas fa-copy"></i> Salin',
                            className: 'btn btn-info',
                            title: 'laporan-kunjungan-pasien-rawat-jalan{-{date("dmy")}}'
                        },
                        {
                            extend: 'csv',
                            text: '<i class="fas fa-file-csv"></i> CSV',
                            className: 'btn btn-info',
                            title: 'laporan-kunjungan-pasien-rawat-jalan-{{ date('dmy') }}'
                        },
                        {
                            extend: 'excel',
                            text: '<i class="fas fa-file-excel"></i> Excel',
                            className: 'btn btn-info',
                            title: 'laporan-kunjungan-pasien-rawat-jalan-{{ date('dmy') }}'
                        },
                    ],
                    columns: [{
                            data: 'no_rawat',
                            name: 'no_rawat'
                        },
                        {
                            data: 'tgl_registrasi',
                            name: 'tgl_registrasi'
                        },
                        {
                            data: 'nm_pasien',
                            name: 'nm_pasien'
                        },
                        {
                            data: 'alamat',
                            name: 'alamat'
                        },
                        {
                            data: 'pembiayaan',
                            name: 'pembiayaan'
                        },
                        {
                            data: 'nm_dokter',
                            name: 'nm_dokter'
                        },
                        {
                            data: 'nm_sps',
                            name: 'nm_sps'
                        },
                        {
                            data: 'bridging_sep',
                            name: 'bridging_sep'
                        },
                    ],
                });
            }

            $('#spesialis').on('change', function() {
                spesialis = $(this).val();
                cekTanggal();

                $('#tabel-pasien-igd').DataTable().destroy();
                load_tabel(tgl_pertama, tgl_kedua, status_lanjut, spesialis, pembiayaan, '');

                if (spesialis) {
                    $.ajax({
                        url: 'poli/' + spesialis,
                        type: "GET",
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        dataType: "json",
                        success: function(data) {
                            console.log(data);
                            if (data) {
                                $('#dokter').empty();
                                $('#dokter').append('<option hidden value="">Pilih Dokter</option>');
                                $.each(data, function(key, dokter) {
                                    $('select[name="dokter"]').append('<option value="' + dokter.kd_dokter + '">' + dokter.nm_dokter + '</option>');
                                });
                            } else {
                                $('#dokter').empty();
                            }
                        }
                    });
                } else {
                    $('#dokter').empty();
                }

            });

            $('#status_lanjut').on('change', function() {
                status_lanjut = $(this).val();
                cekTanggal();
                $('#tabel-pasien-igd').DataTable().destroy();
                load_tabel(tgl_pertama, tgl_kedua, status_lanjut, spesialis, '');
            });

            $('#pembiayaan').on('change', function() {
                pembiayaan = $(this).val();
                cekTanggal();
                $('#tabel-pasien-igd').DataTable().destroy();
                load_tabel(tgl_pertama, tgl_kedua, status_lanjut, spesialis, pembiayaan, '');
            });

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

                $('.tanggal').html('<strong>' + tgl1 + ' s/d ' + tgl2 + '</strong>');
                load_data(tgl_pertama, tgl_kedua);
                $('#tabel-pasien-igd').DataTable().destroy();
                load_tabel(tgl_pertama, tgl_kedua);
            });



        });
    </script>
@endpush
