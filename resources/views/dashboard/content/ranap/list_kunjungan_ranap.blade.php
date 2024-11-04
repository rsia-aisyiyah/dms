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
                        <div class="col-xl-3 col-md-12 col-sm-12">
                            <div class="form-group">
                                <label>Filter Data </label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="tanggal" name="tanggal"
                                        autocomplete="off" />
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-12 col-sm-12">
                            <div class="form-group">
                                <label>Status Daftar </label>
                                <select class="custom-select form-control-border" id="daftar" name="daftar">
                                    <option value="">Baru & Lama</option>
                                    <option value="baru">Baru</option>
                                    <option value="lama">Lama</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-12 col-sm-12">
                            <div class="form-group">
                                <label>Pembiayaan </label>
                                <select class="custom-select form-control-border" id="pembiayaan" name="pembiayaan">
                                    <option value="">Umum & BPJS</option>
                                    <option value="umum">Umum</option>
                                    <option value="bpjs">BPJS</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-12 col-sm-12">
                            <div class="form-group">
                                <label>Spesialis </label>
                                <select class="custom-select form-control-border" id="poli" name="poli">
                                    <option value="">Anak & Kebidanan</option>
                                    <option value="S0003">Anak</option>
                                    <option value="S0001">Kebidanan dan Kandungan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-12 col-sm-12">
                            <div class="form-group">
                                <label>Dokter </label>
                                <select class="custom-select form-control-border" id="dokter" name="dokter">
                                    <option value="">Semua Dokter</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive text-sm">
                                <table class="table table-bordered table-striped" id="tabel-ranap" style="width: 100%"
                                    cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No Rawat</th>
                                            <th>Tanggal Registrasi</th>
                                            <th>No. RM</th>
                                            <th>Nama</th>
                                            <th>JK</th>
                                            <th>Umur</th>
                                            <th>Tgl. Lahir</th>
                                            <th>Alamat</th>
                                            <th>No. HP</th>
                                            <th>Status Daftar</th>
                                            <th>DPJP</th>
                                            <th>Spesialis</th>
                                            <th>Kamar</th>
                                            <th>Dx. Masuk</th>
                                            <th>Dx. AKhir</th>
                                            <th>Pembiayaan</th>
                                            <th>Tanggal Masuk</th>
                                            <th>Tanggal Keluar</th>
                                            <th>Tanggal SEP</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('dashboard.content.ranap.list_pembayaran_ranap')
        @include('dashboard.content.ranap.list_pembiyaan')
        @include('dashboard.content.ranap.list_rekap_tahunan')
        @include('dashboard.content.ranap.list_jk_ranap')
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
                $('#tabel-ranap').DataTable().destroy();
                load_data(tgl_pertama, tgl_kedua, daftar, poli, kd_dokter, pembiayaan);
            });

            $('#daftar').on('change', function() {
                daftar = $(this).val();
                cekTanggal();
                $('#tabel-ranap').DataTable().destroy();
                load_data(tgl_pertama, tgl_kedua, daftar);
            });

            $('#poli').on('change', function() {
                poli = $(this).val();
                cekTanggal();
                daftar = $('#daftar').val();
                $('#tabel-ranap').DataTable().destroy();

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
                $('#tabel-ranap').DataTable().destroy();
                load_data(tgl_pertama, tgl_kedua, $('#daftar').val(), $('#poli').val(), dokter);
            });

            $('#pembiayaan').on('change', function() {
                cekTanggal();
                $('#tabel-ranap').DataTable().destroy();
                load_data(tgl_pertama, tgl_kedua, $('#daftar').val(), $('#poli').val(), $('#dokter').val(),
                    $(this).val());
            });

            load_data();

            function load_data(tgl_pertama, tgl_kedua, daftar, poli, kd_dokter, pembiyaan) {
                $('#tabel-ranap').DataTable({
                    ajax: {
                        url: `${url}/ranap/json`,
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
                    destroy: false,
                    deferRender: true,
                    lengthChange: true,
                    ordering: false,
                    searching: true,
                    stateSave: false,
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
                            name: 'no_rawat'
                        },
                        {
                            data: 'tgl_registrasi',
                            name: 'tgl_registrasi'
                        },
                        {
                            data: 'no_rkm_medis',
                            name: 'no_rkm_medis'
                        },
                        {
                            data: 'nm_pasien',
                            name: 'nm_pasien'
                        },
                        {
                            data: 'jk',
                            name: 'jk',
                        },
                        {
                            data: 'umurdaftar',
                            name: 'umurdaftar',
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
                            data: 'no_tlp',
                            name: 'no_tlp'
                        },
                        {
                            data: 'stts_daftar',
                            name: 'stts_daftar'
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
                            data: 'kamar',
                            name: 'kamar',
                        },
                        {
                            data: 'diagnosa_awal',
                            name: 'diagnosa_awal'
                        },
                        {
                            data: 'diagnosa_akhir',
                            name: 'diagnosa_akhir'
                        },
                        {
                            data: 'pembiayaan',
                            name: 'pembiayaan'
                        },
                        {
                            data: 'tgl_masuk',
                            name: 'tgl_masuk'
                        },
                        {
                            data: 'tgl_keluar',
                            name: 'tgl_keluar'
                        },
                        {
                            data: 'tglsep',
                            name: 'tglsep'
                        },
                    ],
                });
            }
        });
    </script>
@endpush
