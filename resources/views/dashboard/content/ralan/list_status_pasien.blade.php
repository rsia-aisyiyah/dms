@extends('dashboard.layouts.main')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-teal">
                <div class="card-header">
                    <p class="card-title border-bottom-0">{{ $title }} </p>
                    <div class="card-tools mr-4" id="bulan">
                        <span><strong>{{ $month }}</strong></span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Tanggal :</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="tanggal" name="tanggal"
                                        autocomplete="off" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Status Daftar :</label>
                                <select name="daftar" id="daftar" class="custom-select form-control-border">
                                    <option value="">Baru & Lama</option>
                                    <option value="baru">Baru</option>
                                    <option value="lama">Lama</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Pembiayaan :</label>
                                <select name="pembiayaan" id="pembiayaan" class="custom-select form-control-border">
                                    <option value="">Semua</option>
                                    <option value="bpjs">BPJS</option>
                                    <option value="umum">Umum</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Poli :</label>
                                <select class="custom-select form-control-border" id="poli" name="poli">
                                    <option value="">Semua Poli</option>
                                    <option value="S0003">Anak</option>
                                    <option value="S0001">Kandungan dan Kebidanan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Dokter :</label>
                                <select class="custom-select form-control-border" id="dokter" name="dokter">
                                    <option hidden value="">Dokter Spesialis</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive text-sm">
                                <table class="table table-bordered" id="table-kunjungan" style="width: 100%"
                                    cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Tanggal Registrasi</th>
                                            <th>Nama Pasien</th>
                                            <th>Tanggal Lahir</th>
                                            <th>Alamat</th>
                                            <th>Status Daftar</th>
                                            <th>Pembiayaan</th>
                                            <th>Penanggung Jawab</th>
                                            <th>No. HP</th>
                                            <th>Dokter PJ</th>
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
    <div class="row">
        @include('dashboard.content.ralan.list_poli_ralan')
        @include('dashboard.content.ralan.list_pembayaran_ralan')
        @include('dashboard.content.ralan.list_status_daftar_ralan')
    </div>
    <div class="row">
        <div class="col-12 col-sm-12 col-md-8">
            @include('dashboard.content.ralan.list_dokter_anak_ralan')
        </div>
        <div class="col-12 col-sm-12 col-md-4">
            @include('dashboard.content.ralan.list_jk_ralan')
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        var tgl_pertama = '';
        var tgl_kedua = '';
        var daftar = '';
        var poli = '';
        var kd_dokter = '';
        var pembiayaan = $('#pembiayaan').val();

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
                $('#table-kunjungan').DataTable().destroy();
                load_data(tgl_pertama, tgl_kedua, daftar, poli, kd_dokter, pembiayaan);
            });

            $('#daftar').on('change', function() {
                daftar = $(this).val();
                cekTanggal();
                $('#table-kunjungan').DataTable().destroy();
                load_data(tgl_pertama, tgl_kedua, daftar);
            });

            $('#poli').on('change', function() {
                var poli = $(this).val();
                cekTanggal();
                daftar = $('#daftar').val();
                $('#table-kunjungan').DataTable().destroy();
                load_data(tgl_pertama, tgl_kedua, $('#daftar').val(), poli);

                if (poli) {
                    $.ajax({
                        url: 'poli/' + poli,
                        type: "GET",
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        dataType: "json",
                        success: function(data) {
                            if (data) {
                                $('#dokter').empty();
                                $('#dokter').append(
                                    '<option hidden value="">Pilih Dokter</option>');
                                $.each(data, function(key, dokter) {
                                    $('select[name="dokter"]').append(
                                        '<option value="' + dokter.kd_dokter +
                                        '">' + dokter.nm_dokter + '</option>');
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

            $('#dokter').on('change', function() {
                dokter = $(this).val();
                cekTanggal();
                $('#table-kunjungan').DataTable().destroy();
                console.log(dokter)
                load_data(tgl_pertama, tgl_kedua, $('#daftar').val(), $('#poli').val(), dokter, $('#pembiayaan').val());
            });

            $('#pembiayaan').on('change', function() {
                cekTanggal();
                $('#table-kunjungan').DataTable().destroy();
                load_data(tgl_pertama, tgl_kedua, $('#daftar').val(), $('#poli').val(), $('#dokter').val(),
                    $(this).val());
            });

            load_data();

            function load_data(tgl_pertama, tgl_kedua, daftar, poli, kd_dokter, pembiyaan) {
                $('#table-kunjungan').DataTable({
                    ajax: {
                        url: 'ralan/json',
                        dataType: 'json',
                        data: {
                            tgl_pertama: tgl_pertama,
                            tgl_kedua: tgl_kedua,
                            daftar: daftar,
                            poli: poli,
                            kd_dokter: kd_dokter,
                            pembiayaan: pembiyaan,
                        },
                    },
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    deferRender: true,
                    lengthChange: true,
                    ordering: false,
                    searching: true,
                    stateSave: false,
                    scrollY: 600,
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
                            data: 'tgl_registrasi',
                            name: 'tgl_registrasi'
                        },
                        {
                            data: 'nm_pasien',
                            name: 'nm_pasien'
                        },
                        {
                            data: 'tgl_lahir',
                            name: 'tgl_lahir'
                        },
                        {
                            data: 'alamat',
                            name: 'alamat'
                        },
                        {
                            data: 'stts_daftar',
                            name: 'stts_daftar'
                        },
                        {
                            data: 'png_jawab',
                            name: 'png_jawab'
                        },
                        {
                            data: 'p_jawab',
                            name: 'p_jawab'
                        },
                        {
                            data: 'no_tlp',
                            name: 'no_tlp'
                        },
                        {
                            data: 'nm_dokter',
                            name: 'nm_dokter'
                        },
                    ],
                });
            }
        });
    </script>
@endpush
