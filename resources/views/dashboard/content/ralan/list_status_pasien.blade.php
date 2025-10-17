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
                                        <option value="S0003">Anak</option>
                                        <option value="S0001">Kandungan dan Kebidanan</option>
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
        });


        $('#daftar').on('change', function () {
            reloadTbPasienRalan()
        });

        $('#poli').on('change', function () {
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
                            $('#dokter').empty();
                            $('#dokter').append(
                                '<option value="">Semua</option>');
                            $.each(data, function (key, dokter) {
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
    </script>
@endpush
