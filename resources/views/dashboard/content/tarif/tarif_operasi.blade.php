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
                                <select name="kategori" id="filter_kategori" class="custom-select form-control-border">
                                    <option value="Kebidanan">Kebidanan</option>
                                    <option value="Operasi">Operasi</option>
                                </select>
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
                                <label>Tambah Tarif : </label> <br />
                                <button type="button" class="btn btn-info" data-toggle="modal"
                                    data-target="#modal-tambah">Tambah</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive text-sm">
                                <table id="tabel-tarif-operasi" class="table table-hover table-striped dataTable"
                                    width="100%" cellspacing="0">
                                    <thead>
                                        <tr role="row">
                                            <th>Kode Paket</th>
                                            <th>Paket Operasi</th>
                                            <th>Kategori</th>
                                            <th>Operator 1</th>
                                            <th>Operator 2</th>
                                            <th>Operator 3</th>
                                            <th>Asisten OP 1</th>
                                            <th>Asisten OP 2</th>
                                            <th>Asisten OP 3</th>
                                            <th>Instrumen</th>
                                            <th>dr Anak</th>
                                            <th>Perawat Resus</th>
                                            <th>dr. Anestesi</th>
                                            <th>Ast. Anestesi 1</th>
                                            <th>Ast. Anestesi 2</th>
                                            <th>Bidan 1</th>
                                            <th>Bidan 2</th>
                                            <th>Bidan 3</th>
                                            <th>Perwat Luar</th>
                                            <th>Sewa OK/VK</th>
                                            <th>Alat</th>
                                            <th>Akomodasi</th>
                                            <th>Bagian RS</th>
                                            <th>Onloop 1</th>
                                            <th>Onloop 2</th>
                                            <th>Onloop 3</th>
                                            <th>Onloop 4</th>
                                            <th>Onloop 5</th>
                                            <th>Sarpras</th>
                                            <th>dr PJ Anak</th>
                                            <th>dr Umum</th>
                                            <th>Total</th>
                                            <th>Jenis Bayar</th>
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
    </div>
    <div class="row">
        @include('dashboard.content.tarif._modal_ubah_tarif_operasi')
        @include('dashboard.content.tarif._modal_tambah_tarif_operasi')
    </div>
@endsection

@push('scripts')
    <script>
        var pembiayaan, kategori, kelas;

        load_data();


        $('#filter_pembiayaan').change(function() {
            pembiayaan = $(this).val();
            $('#tabel-tarif-operasi').DataTable().destroy();
            load_data(kategori, pembiayaan, kelas)
        });

        $('#filter_kelas').change(function() {
            $('#tabel-tarif-operasi').DataTable().destroy();
            kelas = $(this).val()
            load_data(kategori, pembiayaan, kelas);
        });

        $('#filter_kategori').change(function() {
            $('#tabel-tarif-operasi').DataTable().destroy();
            kategori = $(this).val()
            load_data(kategori, pembiayaan, kelas);
        });

        $('#tabel-tarif-operasi tbody').on('click', 'tr', function() {
            var data = $('#tabel-tarif-operasi').DataTable().row(this).data();
            $('#modal-ubah').modal('show');
            ambilTarifOperasi(data.kode_paket);
        });

        function load_data(kategori = '', pembiayaan = '', kelas = '') {

            $('#tabel-tarif-operasi').DataTable({
                ajax: {
                    url: 'operasi/json',
                    data: {
                        kategori: kategori,
                        pembiayaan: pembiayaan,
                        kelas: kelas,
                    },
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
                        data: 'kode_paket',
                        name: 'kode_paket',
                    },
                    {
                        data: 'nm_perawatan',
                        name: 'nm_perawatan',
                    },
                    {
                        data: 'kategori',
                        name: 'kategori',
                    },
                    {
                        data: 'operator1',
                        render: $.fn.dataTable.render.number('.', 0),
                        className: 'editable',
                        name: 'operator1'
                    },
                    {
                        data: 'operator2',
                        render: $.fn.dataTable.render.number('.', 0),
                        className: 'editable',
                        name: 'operator2'
                    },
                    {
                        data: 'operator3',
                        render: $.fn.dataTable.render.number('.', 0),
                        className: 'editable',
                        name: 'operator3'
                    },
                    {
                        data: 'asisten_operator1',
                        render: $.fn.dataTable.render.number('.', 0),
                        className: 'editable',
                        name: 'asisten_operator1'
                    },
                    {
                        data: 'asisten_operator2',
                        render: $.fn.dataTable.render.number('.', 0),
                        className: 'editable',
                        name: 'asisten_operator2'
                    },
                    {
                        data: 'asisten_operator3',
                        render: $.fn.dataTable.render.number('.', 0),
                        className: 'editable',
                        name: 'asisten_operator3'
                    },
                    {
                        data: 'instrumen',
                        render: $.fn.dataTable.render.number('.', 0),
                        className: 'editable',
                        name: 'instrumen'
                    },
                    {
                        data: 'dokter_anak',
                        render: $.fn.dataTable.render.number('.', 0),
                        className: 'editable',
                        name: 'dokter_anak'
                    },
                    {
                        data: 'perawaat_resusitas',
                        render: $.fn.dataTable.render.number('.', 0),
                        className: 'editable',
                        name: 'perawaat_resusitas'
                    },
                    {
                        data: 'dokter_anestesi',
                        render: $.fn.dataTable.render.number('.', 0),
                        className: 'editable',
                        name: 'dokter_anestesi'
                    },
                    {
                        data: 'asisten_anestesi',
                        render: $.fn.dataTable.render.number('.', 0),
                        className: 'editable',
                        name: 'asisten_anestesi'
                    },
                    {
                        data: 'asisten_anestesi2',
                        render: $.fn.dataTable.render.number('.', 0),
                        className: 'editable',
                        name: 'asisten_anestesi2'
                    },
                    {
                        data: 'bidan',
                        render: $.fn.dataTable.render.number('.', 0),
                        className: 'editable',
                        name: 'bidan'
                    },
                    {
                        data: 'bidan2',
                        render: $.fn.dataTable.render.number('.', 0),
                        className: 'editable',
                        name: 'bidan2'
                    },
                    {
                        data: 'bidan3',
                        render: $.fn.dataTable.render.number('.', 0),
                        className: 'editable',
                        name: 'bidan3'
                    },
                    {
                        data: 'perawat_luar',
                        render: $.fn.dataTable.render.number('.', 0),
                        className: 'editable',
                        name: 'perawat_luar'
                    },
                    {
                        data: 'sewa_ok',
                        render: $.fn.dataTable.render.number('.', 0),
                        className: 'editable',
                        name: 'sewa_ok'
                    },
                    {
                        data: 'alat',
                        render: $.fn.dataTable.render.number('.', 0),
                        className: 'editable',
                        name: 'alat'
                    },
                    {
                        data: 'akomodasi',
                        render: $.fn.dataTable.render.number('.', 0),
                        className: 'editable',
                        name: 'akomodasi'
                    },
                    {
                        data: 'bagian_rs',
                        render: $.fn.dataTable.render.number('.', 0),
                        className: 'editable',
                        name: 'bagian_rs'
                    },
                    {
                        data: 'omloop',
                        render: $.fn.dataTable.render.number('.', 0),
                        className: 'editable',
                        name: 'omloop'
                    },
                    {
                        data: 'omloop2',
                        render: $.fn.dataTable.render.number('.', 0),
                        className: 'editable',
                        name: 'omloop2'
                    },
                    {
                        data: 'omloop3',
                        render: $.fn.dataTable.render.number('.', 0),
                        className: 'editable',
                        name: 'omloop3'
                    },
                    {
                        data: 'omloop4',
                        render: $.fn.dataTable.render.number('.', 0),
                        className: 'editable',
                        name: 'omloop4'
                    },
                    {
                        data: 'omloop5',
                        render: $.fn.dataTable.render.number('.', 0),
                        className: 'editable',
                        name: 'omloop5'
                    },
                    {
                        data: 'sarpras',
                        render: $.fn.dataTable.render.number('.', 0),
                        className: 'editable',
                        name: 'sarpras'
                    },
                    {
                        data: 'dokter_pjanak',
                        render: $.fn.dataTable.render.number('.', 0),
                        className: 'editable',
                        name: 'dokter_pjanak'
                    },
                    {
                        data: 'dokter_umum',
                        render: $.fn.dataTable.render.number('.', 0),
                        className: 'editable',
                        name: 'dokter_umum'
                    },
                    {
                        data: 'total',
                        render: $.fn.dataTable.render.number('.', 0),
                        className: 'editable',
                        name: 'total'
                    },
                    {
                        data: 'kd_pj',
                        name: 'kd_pj',
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
