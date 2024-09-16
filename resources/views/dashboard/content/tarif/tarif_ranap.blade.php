@extends('dashboard.layouts.main')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-teal">
                <div class="card-header">
                    <h3 class="card-title">{{ $title }}</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Kamar :</label>
                                <select name="filter_kamar" id="filter_kamar" class="custom-select form-control-border">
                                    <option value="">Semua Kamar</option>
                                    <option value="-">-</option>
                                    <option value="B0115">Kelas 1</option>
                                    <option value="B0116">Kelas 2</option>
                                    <option value="B0117">Kelas 3</option>
                                    <option value="B0114">VIP</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Kelas :</label>
                                <select name="filter_kelas" id="filter_kelas" class="custom-select form-control-border">
                                    <option value="">Semua Kelas</option>
                                    <option value="-">-</option>
                                    <option value="Kelas 1">Kelas 1</option>
                                    <option value="Kelas 2">Kelas 2</option>
                                    <option value="Kelas 3">Kelas 3</option>
                                    <option value="Kelas VIP">Kelas VIP</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="kategori">Kategori</label>
                                <input type="search" value="" name="filter_kategori" id="filter_kategori"
                                    class="form-control" autocomplete="off" placeholder="Cari Kategori">
                                <div id="filter_listkategori" class=""></div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Pembiayaan :</label>
                                <select name="filter_pembiayaan" id="filter_pembiayaan"
                                    class="custom-select form-control-border">
                                    <option value="">Semua Pembiayaan</option>
                                    <option value="-">-</option>
                                    <option value="umum">Umum</option>
                                    <option value="bpjs">BPJS</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Tambah Tarif : </label><br />
                                <button type="button" class="btn btn-info" data-toggle="modal"
                                    data-target="#modal-tambah">Tambah</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive text-sm">
                                <table id="tabel-tarif-ranap" class="table table-hover dataTable" width="100%"
                                    cellspacing="0">
                                    <thead>
                                        <tr role="row">
                                            <th>Kode Tindakan</th>
                                            <th>Nama Tndk/Prw/Tagihan</th>
                                            <th>Kategori</th>
                                            <th>Jasa RS</th>
                                            <th>Jasa Medis Dokter</th>
                                            <th>Jasa Medis Perawat</th>
                                            <th>BHP/Obat</th>
                                            <th>KSO</th>
                                            <th>Manajemen</th>
                                            <th>Biaya Dr</th>
                                            <th>Biaya Pr</th>
                                            <th>Total Biaya Dr+Pr</th>
                                            <th>Jenis Bayar</th>
                                            <th>Kamar</th>
                                            <th>Kelas</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            @include('dashboard.content.tarif._modal_ubah_tarif_ranap')
            @include('dashboard.content.tarif._modal_tambah_tarif_ranap')
        </div>
    @endsection

    @push('scripts')
        <script>
            var tgl_pertama = '';
            var tgl_kedua = '';
            var material;
            var tarif_perawat;
            var tarif_dokter;
            var kso;
            var menejemen;
            var bhp;
            var total;
            var kategori = '',
                bangsal = '',
                pembiayaan = '',
                kelas = '';

            kategoriPerawatan('#filter_kategori', '#filter_listkategori', 'fix')



            $('#filter_listkategori').on('click', 'li', function() {
                $('#filter_kategori').val($(this).text());
                $('#filter_listkategori').fadeOut();
                $('#tabel-tarif-ranap').DataTable().destroy();
                text = $(this).text().split('-');
                kategori = text[0];
                load_data(kategori, pembiayaan, bangsal, kelas);
            });



            $('#filter_kategori').on("search", function(evt) {
                if ($(this).val().length == 0) {
                    $('#filter_listkategori').fadeOut();
                    $('#tabel-tarif-ranap').DataTable().destroy();
                    kategori = '';
                    load_data(kategori, pembiayaan, bangsal, kelas);
                }
            });


            $('#pembiayaan').on('change', function() {
                pembiayaan = $(this).val();
            });

            load_data();
            loadPoli('filter_poliklinik');

            $('#filter_pembiayaan').change(function() {
                pembiayaan = $(this).val();
                $('#tabel-tarif-ranap').DataTable().destroy();
                load_data(kategori, pembiayaan)
            });
            $('#filter_kamar').change(function() {
                $('#tabel-tarif-ranap').DataTable().destroy();
                bangsal = $(this).val();
                load_data(kategori, pembiayaan, bangsal, kelas);
            });

            $('#filter_kelas').change(function() {
                $('#tabel-tarif-ranap').DataTable().destroy();
                kelas = $(this).val()
                load_data(kategori, pembiayaan, bangsal, kelas);
            });

            $('#tabel-tarif-ranap tbody').on('click', 'tr', function() {
                var data = $('#tabel-tarif-ranap').DataTable().row(this).data();
                $('#modal-ubah').modal('show');
                ambilTarif(data.kd_jenis_prw);
            });
            $('#modal-default').on('hidden.bs.modal', function() {
                $('select[name="poliklinik"] option').detach();
                $('select[name="pembiayaan"] option').detach();
            });

            function load_data(kategori = '', pembiayaan = '', bangsal = '', kelas = '') {

                $('#tabel-tarif-ranap').DataTable({
                    ajax: {
                        url: 'ranap/json',
                        data: {
                            kategori: kategori,
                            pembiayaan: pembiayaan,
                            bangsal: bangsal,
                            kelas: kelas,
                        }
                    },
                    processing: true,
                    searching: true,
                    serverSide: false,
                    lengthChange: true,
                    ordering: true,
                    scrollY: "450px",
                    scrollX: true,
                    paging: true,
                    dom: 'Blfrtip',
                    initComplete: function(settings, json) {
                        toastr.success('Data telah dimuat', 'Berhasil');
                    },
                    scroller: {
                        loadingIndicator: false
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
                        search: 'Cari Tindakan : '
                    },

                    buttons: [{
                            extend: 'copy',
                            text: '<i class="fas fa-copy"></i> Salin',
                            className: 'btn btn-info',
                            title: 'tarif-kamar{{ date('dmy') }}'
                        },
                        {
                            extend: 'csv',
                            text: '<i class="fas fa-file-csv"></i> CSV',
                            className: 'btn btn-info',
                            title: 'tarif-kamar{{ date('dmy') }}'
                        },
                        {
                            extend: 'excel',
                            text: '<i class="fas fa-file-excel"></i> Excel',
                            className: 'btn btn-info',
                            title: 'tarif-kamar{{ date('dmy') }}'
                        },
                    ],
                    columns: [{
                            data: 'kd_jenis_prw',
                            name: 'kd_jenis_prw',
                        },
                        {
                            data: 'nm_perawatan',
                            name: 'nm_perawatan',
                        },
                        {
                            data: 'kd_kategori',
                            name: 'kd_kategori',
                        },
                        {
                            data: 'material',
                            render: $.fn.dataTable.render.number('.', 0),
                            className: 'editable',
                            name: 'material'
                        },
                        {
                            data: 'tarif_tindakandr',
                            render: $.fn.dataTable.render.number('.', 0),
                            className: 'editable',
                            name: 'tarif_tindakandr'
                        },
                        {
                            data: 'tarif_tindakanpr',
                            render: $.fn.dataTable.render.number('.', 0),
                            className: 'editable',
                            name: 'tarif_tindakanpr'
                        },
                        {
                            data: 'bhp',
                            render: $.fn.dataTable.render.number('.', 0),
                            className: 'editable',
                            name: 'bhp'
                        },
                        {
                            data: 'kso',
                            render: $.fn.dataTable.render.number('.', 0),
                            className: 'editable',
                            name: 'kso'
                        },
                        {
                            data: 'menejemen',
                            render: $.fn.dataTable.render.number('.', 0),
                            className: 'editable',
                            name: 'menejemen'
                        },
                        {
                            data: 'total_byrdr',
                            render: $.fn.dataTable.render.number('.', 0),
                            className: 'editable',
                            name: 'total_byrdr'
                        },
                        {
                            data: 'total_byrpr',
                            render: $.fn.dataTable.render.number('.', 0),
                            className: 'editable',
                            name: 'total_byrpr'
                        },
                        {
                            data: 'total_byrdrpr',
                            render: $.fn.dataTable.render.number('.', 0),
                            className: 'editable',
                            name: 'total_byrdrpr'
                        },
                        {
                            data: 'kd_pj',
                            name: 'kd_pj',
                        },
                        {
                            data: 'kd_bangsal',
                            name: 'kd_bangsal',
                        },
                        {
                            data: 'kelas',
                            name: 'kelas',
                        },
                    ],
                });
            }
        </script>
    @endpush
