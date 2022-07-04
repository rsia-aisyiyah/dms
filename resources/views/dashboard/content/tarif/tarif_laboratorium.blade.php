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
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Kategori :</label>
                                <select name="filter_kategori" id="filter_kategori"
                                    class="custom-select form-control-border">
                                    <option value="">Semua Kategori</option>
                                    <option value="-">-</option>
                                    <option value="PK">PK</option>
                                    <option value="PR">PR</option>
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
                        <div class="col-sm-4">
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
                                <table id="tabel-tarif-lab" class="table table-hover dataTable" width="100%"
                                    cellspacing="0">
                                    <thead>
                                        <tr role="row">
                                            <th>Kode Tindakan</th>
                                            <th>Nama Pemeriksaan</th>
                                            <th>Jasa RS</th>
                                            <th>Jasa Medis Perujuk</th>
                                            <th>Jasa Medis Dokter</th>
                                            <th>Jasa Medis Perawat</th>
                                            <th>BHP/Obat</th>
                                            <th>KSO</th>
                                            <th>Manajemen</th>
                                            <th>Total</th>
                                            <th>Jenis Bayar</th>
                                            <th>Kelas</th>
                                            <th>Kategori</th>
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
            @include('dashboard.content.tarif._modal_tambah_tarif_lab')
            @include('dashboard.content.tarif._modal_ubah_tarif_lab')
        </div>
    @endsection

    @push('scripts')
        <script>
            var kategori = '',
                pembiayaan = '',
                kelas = '';

            function hanyaAngka(evt) {
                var charCode = (evt.which) ? evt.which : event.keyCode
                if (charCode > 31 && (charCode < 48 || charCode > 57))

                    return false;
                return true;
            }

            $('#filter_pembiayaan').on("change", function(evt) {
                pembiayaan = $(this).val();
                $('#tabel-tarif-lab').DataTable().destroy();
                load_data(pembiayaan, kategori, kelas);
            });

            $('#filter_kelas').on("change", function(evt) {
                kelas = $(this).val();
                $('#tabel-tarif-lab').DataTable().destroy();
                load_data(pembiayaan, kategori, kelas);
            });

            $('#filter_kategori').on("change", function(evt) {
                kategori = $(this).val();
                $('#tabel-tarif-lab').DataTable().destroy();
                load_data(pembiayaan, kategori, kelas);
            });



            load_data();

            $('#tabel-tarif-lab tbody').on('click', 'tr', function() {
                var data = $('#tabel-tarif-lab').DataTable().row(this).data();
                $('#modal-ubah').modal('show');
                ambilTarif(data.kd_jenis_prw);
            });
            $('.modal').on('hidden.bs.modal', function() {
                $('select[name="pembiayaan"] option').detach();
            });

            function load_data(pembiayaan = '', kategori = '', kelas = '') {
                $('#tabel-tarif-lab').DataTable({
                    ajax: {
                        url: 'lab/json',
                        data: {
                            pembiayaan: pembiayaan,
                            kategori: kategori,
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
                            data: 'bagian_rs',
                            render: $.fn.dataTable.render.number('.', 0),
                            className: 'editable',
                            name: 'material'
                        },
                        {
                            data: 'tarif_perujuk',
                            render: $.fn.dataTable.render.number('.', 0),
                            className: 'editable',
                            name: 'tarif_tindakandr'
                        },
                        {
                            data: 'tarif_tindakan_dokter',
                            render: $.fn.dataTable.render.number('.', 0),
                            className: 'editable',
                            name: 'tarif_tindakandr'
                        },
                        {
                            data: 'tarif_tindakan_petugas',
                            render: $.fn.dataTable.render.number('.', 0),
                            className: 'editable',
                            name: 'tarif_tindakandr'
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
                            data: 'total_byr',
                            render: $.fn.dataTable.render.number('.', 0),
                            className: 'editable',
                            name: 'total_byrdr'
                        },
                        {
                            data: 'penjab',
                            name: 'penjab',
                        },
                        {
                            data: 'kelas',
                            name: 'kelas',
                        },
                        {
                            data: 'kategori',
                            name: 'kategori',
                        },
                    ],
                });
            }
        </script>
    @endpush
