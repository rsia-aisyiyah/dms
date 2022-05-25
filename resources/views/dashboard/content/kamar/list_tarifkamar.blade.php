@extends('dashboard.layouts.main')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-teal">
            <div class="card-header">
                <h3 class="card-title">{{$title}}</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Status Kamar :</label>
                            <select name="status" id="status" class="custom-select form-control-border">
                                <option value="">Semua Status</option>
                                <option value="isi">Isi</option>
                                <option value="kosong">Kosong</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Kelas : </label>
                            <select name="kelas" id="kelas" class="custom-select form-control-border">
                                <option value="">Semua Kelas</option>
                                <option value="kelas 1">KELAS 1</option>
                                <option value="kelas 2">KELAS 2</option>
                                <option valu e="kelas 3">KELAS 3</option>
                                <option value="kelas utama">KELAS utama</option>
                                <option value="VIP">VIP</option>
                                <option value="VVIP">VVIP</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive text-sm">
                            <table id="tabel-tarif-kamar" class="table table-bordered dataTable" width="100%"
                                cellspacing="0">
                                <thead>
                                    <tr role="row">
                                        <th>Nomor Bed</th>
                                        <th>Kode Kamar</th>
                                        <th>Nama Kamar</th>
                                        <th>Kelas</th>
                                        <th>Tarif</th>
                                        <th>Status Kamar</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection

    @push('scripts')
    <script>
        var tgl_pertama = '';
        var tgl_kedua = '';

        $(document).ready(function () {

        load_data();
        var status = $('#status').val();
        var kelas = $('#kelas').val();

        function load_data(status = '', kelas = '') {


            $('#tabel-tarif-kamar').DataTable({
                ajax: {
                    url: 'kamar/json',
                    data: {
                        status:status,
                        kelas: kelas,
                    }
                },
                processing: true,
                searching: true,
                serverSide: true,
                lengthChange: true,
                ordering: false,
                scrollY: "300px",
                scrollX: true,
                paging: true,
                dom: 'Blfrtip',
                initComplete: function (settings, json) {
                    toastr.success('Data telah dimuat', 'Berhasil');
                },
                scroller: {
                    loadingIndicator: true
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
                },

                buttons: [
                    { extend: 'copy', text: '<i class="fas fa-copy"></i> Salin', className: 'btn btn-info', title: 'laporan-kunjungan-pasien-rawat-jalan{{date("dmy")}}' },
                    { extend: 'csv', text: '<i class="fas fa-file-csv"></i> CSV', className: 'btn btn-info', title: 'laporan-kunjungan-pasien-rawat-jalan{{date("dmy")}}' },
                    { extend: 'excel', text: '<i class="fas fa-file-excel"></i> Excel', className: 'btn btn-info', title: 'laporan-kunjungan-pasien-rawat-jalan{{date("dmy")}}' },
                ],
                columns: [
                    { data: 'kd_kamar', name: 'kd_kamar', },
                    { data: 'kd_bangsal', name: 'kd_bangsal', },
                    { data: 'nm_bangsal', name: 'nm_bangsal', },
                    { data: 'kelas', name: 'kelas', },
                    { data: 'trf_kamar', name: 'trf_kamar', },
                    { data: 'status', name: 'status', },
                ],
            });
        }

        $('#status').change(function () {
            $('#tabel-tarif-kamar').DataTable().destroy();
            load_data($(this).val(), kelas);
        });

        $('#kelas').change(function () {
            $('#tabel-tarif-kamar').DataTable().destroy();
            load_data(status,$(this).val());
        });

});
    </script>
    @endpush