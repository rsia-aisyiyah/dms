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
                                <label>Status Kamar :</label>
                                <select name="status" id="status" class="custom-select form-control-border">
                                    <option value="">Semua Status</option>
                                    <option value="isi">ISI</option>
                                    <option value="kosong">KOSONG</option>
                                    <option value="dibersihkan">DIBERSIHKAN</option>
                                    <option value="dibooking">DIBOOKING</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Kelas : </label>
                                <select name="kelas" id="kelas" class="custom-select form-control-border">
                                    <option value="">Semua Kelas</option>
                                    <option value="Kelas 1">Kelas 1</option>
                                    <option value="Kelas 2">Kelas 2</option>
                                    <option value="Kelas 3">Kelas 3</option>
                                    <option value="Kelas utama">Kelas utama</option>
                                    <option value="VIP">VIP</option>
                                    <option value="VVIP">VVIP</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive text-sm">
                                <table id="tabel-tarif-kamar" class="table table-hover dataTable" width="100%"
                                    cellspacing="0">
                                    <thead>
                                        <tr role="row">
                                            <th>Nomor Bed</th>
                                            <th>Kode Kamar</th>
                                            <th>Nama Kamar</th>
                                            <th>Kelas</th>
                                            <th>Tarif</th>
                                            <th>Status</th>
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
            @include('dashboard.content.kamar._modalkamar')
        </div>
    @endsection

    @push('scripts')
        <script>
            var kamar,
                status;
            $('#kelas').change(function() {
                $('#tabel-tarif-kamar').DataTable().destroy();
                kamar = $(this).val();
                load_data(status, kamar);
            });
            $('#status').change(function() {
                $('#tabel-tarif-kamar').DataTable().destroy();
                status = $(this).val();
                load_data(status, kamar);
            });

            function ambilTarifKamar(bangsal) {
                $.ajax({
                    url: 'kamar/' + bangsal,
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    dataType: "json",
                    success: function(data) {
                        $('#nm_kamar').val(data[0].bangsal.nm_bangsal);
                        $('#tarif').val(data[0].trf_kamar);
                        $('#kd_bangsal').val(data[0].kd_bangsal);
                        $('#u_status').append('<option selected hidden value="' + data[0].status + ' selected">' +
                            data[0]
                            .status +
                            '</option>')
                    }
                });
            }

            load_data();

            $('#tabel-tarif-kamar tbody').on('click', 'tr', function() {
                var data = $('#tabel-tarif-kamar').DataTable().row(this).data();
                $('#modal-kamar').modal('show');
                ambilTarifKamar(data.kd_bangsal);
            });

            function load_data(status = '', kelas = '') {

                $('#tabel-tarif-kamar').DataTable({
                    ajax: {
                        url: 'kamar/json',
                        data: {
                            status: status,
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
                        search: 'Cari Kamar : '
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
                            data: 'kd_kamar',
                            name: 'kd_kamar',
                        },
                        {
                            data: 'kd_bangsal',
                            name: 'kd_bangsal',
                        },
                        {
                            data: 'nm_bangsal',
                            name: 'nm_bangsal',
                        },
                        {
                            data: 'kelas',
                            name: 'kelas',
                        },
                        {
                            data: 'trf_kamar',
                            render: $.fn.dataTable.render.number('.', 0),
                            className: 'editable',
                            name: 'trf_kamar',
                        },
                        {
                            data: 'status',
                            name: 'status',
                        },
                    ],
                });
            }
        </script>
    @endpush
