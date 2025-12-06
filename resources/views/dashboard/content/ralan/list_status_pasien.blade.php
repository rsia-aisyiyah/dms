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
                    <form action="" id="filterPasienRajal">
                        <div class="row">
                            <div class="col-lg-4 col-xl-3 col-sm-12">
                                <div class="form-group">
                                    <label>Tanggal :</label>
                                    <div class="input-group">
                                        <input type="date" class="form-control" id="tgl_pertama" name="tgl_pertama"
                                               autocomplete="off"/>
                                        <div class="input-group-append">
                                            <span class="input-group-text">s.d</span>
                                        </div>
                                        <input type="date" class="form-control" id="tgl_kedua" name="tgl_kedua"
                                               autocomplete="off"/>

                                        <div class="input-group-append">
                                            <button type="button" onclick="reloadTbPasienRalan()"
                                                    class="btn btn-success">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-1 col-xl-2 col-sm-12">
                                <div class="form-group">
                                    <label>Status Daftar :</label>
                                    <select name="daftar" id="daftar" class="custom-select form-control-border">
                                        <option value="">Baru & Lama</option>
                                        <option value="baru">Baru</option>
                                        <option value="lama">Lama</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-12">
                                <div class="form-group">
                                    <label>Pembiayaan :</label>
                                    <select name="pembiayaan" id="pembiayaan" class="custom-select form-control-border">
                                        <option value="">Semua</option>
                                        <option value="bpjs">BPJS</option>
                                        <option value="umum">Umum</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-12">
                                <div class="form-group">
                                    <label>Poli :</label>
                                    <select class="custom-select form-control-border" id="poli" name="poli">
                                        <option value="">Semua Poli</option>
                                        <option value="S0007">Umum</option>
                                        <option value="S0003">Anak</option>
                                        <option value="S0001">Kandungan dan Kebidanan</option>
                                        <option value="S0005">Penyakit Dalam</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-12">
                                <div class="form-group">
                                    <label>Dokter :</label>
                                    <select class="custom-select form-control-border" id="dokter" name="dokter">
                                        <option hidden value="">Dokter Spesialis</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </form>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive text-sm">
                                <table class="table table-bordered" id="table-kunjungan-pasien-rajal"
                                       style="width: 100%"
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
            <div class="card card-teal">
                <div class="card-header">
                    <p class="card-title border-bottom-0">Pemeriksaan Rawat Jalan</p>
                </div>
                <div class="card-body">
                    <form action="" id="filterPemeriksaanRajal">
                        <div class="row">
                            <div class="col-lg-4 col-xl-3 col-sm-12">
                                <div class="form-group">
                                    <label>Tanggal :</label>
                                    <div class="input-group">
                                        <input type="date" class="form-control" id="tgl1" name="tgl1"
                                               autocomplete="off"/>
                                        <div class="input-group-append">
                                            <span class="input-group-text">s.d</span>
                                        </div>
                                        <input type="date" class="form-control" id="tgl2" name="tgl2"
                                               autocomplete="off"/>
                                        <div class="input-group-append">

                                            <button type="button" onclick="reloadTbPemeriksaanRalan()"
                                                    class="btn btn-success">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-12">
                                <div class="form-group">
                                    <label>Poli :</label>
                                    <select class="custom-select form-control-border" id="poli" name="poli">
                                        <option value="">Semua Poli</option>
                                        <option value="S0007">Umum</option>
                                        <option value="S0003">Anak</option>
                                        <option value="S0001">Kandungan dan Kebidanan</option>
                                        <option value="S0005">Penyakit Dalam</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-12">
                                <div class="form-group">
                                    <label>Dokter :</label>
                                    <select class="custom-select form-control-border" id="dokter" name="dokter">
                                        <option hidden value="">Dokter Spesialis</option>
                                    </select>
                                </div>
                            </div>


                        </div>
                    </form>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive text-sm">
                                <table class="table table-bordered" id="tbPameriksaanRalan"
                                       style="width: 100%"
                                       cellspacing="0">
                                    <thead>

                                    </thead>
                                    <tbody>

                                    </tbody>
                                    <tfoot>

                                    </tfoot>
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
        <div class="col-12 col-lg-12 col-md-8">
            @include('dashboard.content.ralan.list_dokter_anak_ralan')
        </div>
        <div class="col-12 col-lg-12 col-md-4">
            @include('dashboard.content.ralan.list_jk_ralan')
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // var tgl_pertama = '';
        // var tgl_kedua = '';
        // var daftar = '';
        // var poli = '';
        // var kd_dokter = '';
        // var pembiayaan = $('#pembiayaan').val();

        $(document).ready(function () {

            loadTbPasienRajal();
            loadTbPemeriksaanRalan();
        });


        $('#daftar').on('change', function () {
            reloadTbPasienRalan()
        });

        $('#filterPasienRajal').find('#poli').on('change', function () {
            reloadTbPasienRalan()
            if (poli) {
                $.ajax({
                    url: 'poli/' + $(this).val(),
                    type: "GET",
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    dataType: "json",
                    success: function (data) {
                        if (data) {
                            $('#filterPasienRajal').find('#dokter').empty();
                            $('#filterPasienRajal').find('#dokter').append(
                                '<option value="">Semua</option>');
                            $.each(data, function (key, dokter) {
                                $('select[name="dokter"]').append(
                                    '<option value="' + dokter.kd_dokter +
                                    '">' + dokter.nm_dokter + '</option>');
                            });
                        } else {
                            $('#filterPasienRajal').find('#dokter').empty();
                        }
                    }
                });
            } else {
                $('#filterPasienRajal').find('#dokter').empty();
            }

        });
        $('#filterPemeriksaanRajal').find('#poli').on('change', function () {
            reloadTbPemeriksaanRalan()
            if (poli) {
                $.ajax({
                    url: 'poli/' + $(this).val(),
                    type: "GET",
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    dataType: "json",
                    success: function (data) {
                        if (data) {
                            $('#filterPemeriksaanRajal').find('#dokter').empty();
                            $('#filterPemeriksaanRajal').find('#dokter').append(
                                '<option value="">Semua</option>');
                            $.each(data, function (key, dokter) {
                                $('select[name="dokter"]').append(
                                    '<option value="' + dokter.kd_dokter +
                                    '">' + dokter.nm_dokter + '</option>');
                            });
                        } else {
                            $('#filterPemeriksaanRajal').find('#dokter').empty();
                        }
                    }
                });
            } else {
                $('#filterPemeriksaanRajal').find('#dokter').empty();
            }

        });

        $('#filterPemeriksaanRajal').find('#poli').on('change', function () {
            reloadTbPemeriksaanRalan()
        });
        $('#filterPemeriksaanRajal').find('#dokter').on('change', function () {
            reloadTbPemeriksaanRalan()
        });

        $('#dokter').on('change', function () {
            reloadTbPasienRalan()
        });

        $('#pembiayaan').on('change', function () {
            reloadTbPasienRalan()
        });

        function reloadTbPasienRalan() {
            const $filterPasienRajal = $('#filterPasienRajal')

            const tgl_pertama = $filterPasienRajal.find('#tgl_pertama').val()
            const tgl_kedua = $filterPasienRajal.find('#tgl_kedua').val()
            const dokter = $filterPasienRajal.find('#dokter').val()
            const poli = $filterPasienRajal.find('#poli').val()
            const pembiayaan = $filterPasienRajal.find('#pembiayaan').val()
            const stts_daftar = $filterPasienRajal.find('#daftar').val()

            const params = {
                tgl_pertama, tgl_kedua, dokter, poli, pembiayaan, stts_daftar
            }
            Object.keys(params).forEach(name => {
                if (!params[name]) delete params[name]
            })

            loadTbPasienRajal(params)
        }

        function reloadTbPemeriksaanRalan() {
            const $filterPemeriksaanRajal = $('#filterPemeriksaanRajal')

            const tgl1 = $filterPemeriksaanRajal.find('#tgl1').val()
            const tgl2 = $filterPemeriksaanRajal.find('#tgl2').val()
            const poli = $filterPemeriksaanRajal.find('#poli').val()
            const dokter = $filterPemeriksaanRajal.find('#dokter').val()
            const params = {
                tgl1, tgl2, poli, dokter
            }
            Object.keys(params).forEach(name => {
                if (!params[name]) delete params[name]
            })

            loadTbPemeriksaanRalan(params)
        }


        function loadTbPasienRajal(params = {}) {

            const table = new DataTable('#table-kunjungan-pasien-rajal', {
                ajax: {
                    url: 'ralan/json',
                    dataType: 'json',
                    data: params,
                },
                processing: true,
                serverSide: true,
                destroy: true,
                deferRender: true,
                lengthChange: true,
                ordering: false,
                searching: true,
                stateSave: false,
                scrollY: '44vh',
                scrollX: true,
                scroller: {
                    loadingIndicator: true
                },
                paging: true,
                dom: 'Blfrtip',
                initComplete: function (settings, json) {
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


        function loadTbPemeriksaanRalan(params = {}) {

            const table = new DataTable('#tbPameriksaanRalan', {
                ajax: {
                    url: '/dms/ralan/pemeriksaan-ralan',
                    dataType: 'json',
                    data: params,
                },
                processing: true,
                serverSide: true,
                destroy: true,
                deferRender: true,
                lengthChange: true,
                ordering: false,
                searching: true,
                stateSave: false,
                scrollY: '44vh',
                scrollX: true,
                // scroller: {
                //     loadingIndicator: true
                // },
                paging: true,
                dom: 'Blfrtip',
                initComplete: function () {
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
                        '<option value="10" selected>10</option>' +
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
                    search: 'Cari Pemeriksaan : ',
                },
                buttons: [{
                    extend: 'copy',
                    text: '<i class="fas fa-copy"></i> Salin',
                    className: 'btn btn-info',
                    title: 'laporan-pemeriksaan-rawat-jalan{{ date('dmy') }}'
                },
                    {
                        extend: 'csv',
                        text: '<i class="fas fa-file-csv"></i> CSV',
                        className: 'btn btn-info',
                        title: 'laporan-pemeriksaan-rawat-jalan{{ date('dmy') }}'
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'btn btn-info',
                        title: 'laporan-pemeriksaan-rawat-jalan{{ date('dmy') }}'
                    },
                ],
                columns: [
                    {
                        data: 'tgl_perawatan',
                        name: 'tgl_perawatan',
                        title: 'Tgl. Perawatan',
                    }, {
                        data: 'jam_rawat',
                        name: 'jam_rawat',
                        title: 'Jam',
                    },
                    {
                        data: 'no_rawat',
                        name: 'no_rawat',
                        title: 'No. Rawat',
                    },
                    {
                        data: 'reg_periksa.no_rkm_medis',
                        name: 'reg_periksa.no_rkm_medis',
                        title: 'No. Rkm Medis'
                    },
                    {
                        data: 'reg_periksa.pasien.nm_pasien',
                        name: 'reg_periksa.pasien.nm_pasien',
                        title: 'Nama'
                    },
                    {
                        data: 'suhu_tubuh',
                        name: 'suhu_tubuh',
                        title: 'Suhu',
                    },
                    {
                        data: 'tensi',
                        name: 'tensi',
                        title: 'Tensi',
                    },
                    {
                        data: 'nadi',
                        name: 'nadi',
                        title: 'Nadi'
                    },
                    {
                        data: 'respirasi',
                        name: 'respirasi',
                        title: 'Respirasi'
                    },
                    {
                        data: 'tinggi',
                        name: 'tinggi',
                        title: 'Tinggi (cm)'
                    },
                    {
                        data: 'berat',
                        name: 'berat',
                        title: 'Berat (Kg)'
                    },
                    {
                        data: 'spo2',
                        name: 'spo2',
                        title: 'SpO2 (%)'
                    },
                    {
                        data: 'gcs',
                        name: 'gcs',
                        title: 'GCS (EVM)'
                    },
                    {
                        data: 'kesadaran',
                        name: 'kesadaran',
                        title: 'Kesadaran'
                    },
                    {
                        data: 'keluhan',
                        name: 'keluhan',
                        title: 'Subjek'
                    },
                    {
                        data: 'pemeriksaan',
                        name: 'pemeriksaan',
                        title: 'Objek'
                    },
                    {
                        data: 'alergi',
                        name: 'alergi',
                        title: 'Alergi'
                    },
                    {
                        data: 'lingkar_perut',
                        name: 'lingkar_perut',
                        title: 'LP'
                    },
                    {
                        data: 'penilaian',
                        name: 'penilaian',
                        title: 'Asesmen'
                    },
                    {
                        data: 'rtl',
                        name: 'rtl',
                        title: 'Plan'
                    },
                    {
                        data: 'instruksi',
                        name: 'instruksi',
                        title: 'Instruksi'
                    },
                    {
                        data: 'petugas.nama',
                        name: 'petugas.nama',
                        title: 'Petugas'
                    },
                    {
                        data: 'petugas.nip',
                        name: 'petugas.nip',
                        title: 'NIP'
                    },
                    {
                        data: 'dokter.spesialis.nm_sps',
                        name: 'dokter.spesialis.nm_sps',
                        title: 'Spesialis'
                    },


                ],
            });
        }
    </script>
@endpush
