@extends('dashboard.layouts.main')

@section('content')
    <div class="row">
        <div class="col-sm-3">
            <div class="card bg-gradient-success">
                <div class="card-header">
                    RESEP LENGKAP
                </div>
                <div class="card-body">
                    <p class="h1 lengkap mb-0"></p>
                    <p class="jml-lengkap mb-0"></p>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card bg-gradient-primary">
                <div class="card-header">
                    OBAT TANPA E-RESEP
                </div>
                <div class="card-body">
                    <p class="h1 tanpa mb-0"></p>
                    <p class="jml-tanpa mb-0"></p>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card bg-gradient-warning">
                <div class="card-header">
                    OBAT TIDAK DIAMBIL
                </div>
                <div class="card-body">
                    <p class="h1 tidak mb-0"></p>
                    <p class="jml-tidak mb-0"></p>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card bg-gradient-danger">
                <div class="card-header">
                    TANPA RESEP & OBAT
                </div>
                <div class="card-body">
                    <p class="h1 kosong mb-0"></p>
                    <p class="jml-kosong mb-0"></p>
                </div>
            </div>
        </div>
    </div>
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
                        <div class="col-xl-6 col-md-12 col-sm-12">
                            <div class="form-group">
                                <label>Filter Data </label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="tanggal" name="tanggal" autocomplete="off" />
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-xl-6 col-md-12 col-sm-12">
                            <div class="form-group">
                                <label>Poli </label>
                                <select class="custom-select form-control-border" id="poli" name="poli">
                                    <option value="">Semua Poli</option>
                                    <option value="anak">Anak</option>
                                    <option value="kandungan">Kebidanan dan Kandungan</option>
                                </select>
                            </div>
                        </div> --}}
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive text-sm">
                                <table class="table table-bordered" id="tabel-resep" style="width: 100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No Rawat</th>
                                            <th>Nama Pasien</th>
                                            <th>Poliklinik</th>
                                            <th>Dokter</th>
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
    </div>
@endsection


@push('scripts')
    <script>
        var tgl_pertama;
        var tgl_kedua;
        $(document).ready(function() {
            load_data();
            hitungEresep();
        });

        function hitungEresep(tgl_pertama, tgl_kedua, poli) {
            $.ajax({
                url: 'resep/hitung',
                data: {
                    'tgl_pertama': tgl_pertama,
                    'tgl_kedua': tgl_kedua,
                    'poli': poli,
                },
                success: function(response) {
                    console.log(response)

                    total = response.total;
                    kosong = response.kosong;
                    lengkap = response.lengkap;
                    tidak = response.tidakAmbil;
                    tanpa = response.tanpaResep;

                    persenKosong = parseFloat(kosong / total) * 100;
                    persenLengkap = parseFloat(lengkap / total) * 100;
                    persenTanpa = parseFloat(tanpa / total) * 100;
                    persenTidak = parseFloat(tidak / total) * 100;


                    console.log('Tanpa', persenTidak)
                    $('.kosong').text(persenKosong.toFixed(1) + ' %');
                    $('.jml-kosong').text('(' + kosong + ')');
                    $('.lengkap').text(persenLengkap.toFixed(1) + ' %');
                    $('.jml-lengkap').text('(' + lengkap + ')');
                    $('.tidak').text(persenTidak.toFixed(1) + ' %');
                    $('.jml-tidak').text('(' + tidak + ')');
                    $('.tanpa').text(persenTanpa.toFixed(1) + ' %');
                    $('.jml-tanpa').text('(' + tanpa + ')');
                }
            });
        }

        function load_data(tgl_pertama, tgl_kedua, poli) {
            $('#tabel-resep').DataTable({
                ajax: {
                    url: 'resep/ambil',
                    dataType: 'json',
                    data: {
                        tgl_pertama: tgl_pertama,
                        tgl_kedua: tgl_kedua,
                        poli: poli,
                    },
                },
                processing: true,
                serverSide: true,
                destroy: false,
                deferRender: true,
                lengthChange: true,
                ordering: false,
                searching: true,
                stateSave: true,
                scrollY: 500,
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
                        title: 'laporan-resep{-{date("dmy")}}'
                    },
                    {
                        extend: 'csv',
                        text: '<i class="fas fa-file-csv"></i> CSV',
                        className: 'btn btn-info',
                        title: 'laporan-resep-{{ date('dmy') }}'
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'btn btn-info',
                        title: 'laporan-resep{{ date('dmy') }}'
                    },
                ],
                columns: [{
                        data: 'no_rawat',
                        render: function(data, type, row) {
                            console.log(row);
                            return data;
                        },
                        name: 'no_rawat'
                    },
                    {
                        data: 'nm_pasien',
                        name: 'nm_pasien',
                    },
                    {
                        data: 'poliklinik',
                        name: 'poliklinik',
                    },
                    {
                        data: 'dokter',
                        name: 'dokter',
                    },
                    {
                        data: 'status',
                        render: function(data, type, row) {
                            html = '';
                            if (data == 'LENGKAP') {
                                html = '<button type="button" class="btn btn-sm btn-success" style="width:150px">' + data + '</button>'
                            } else if (data == 'OBAT TANPA E-RESEP') {
                                html = '<button type="button" class="btn btn-sm btn-primary" style="width:150px">' + data + '</button>'
                            } else if (data == 'TIDAK DIAMBIL') {
                                html = '<button type="button" class="btn btn-sm btn-warning" style="width:150px">' + data + '</button>'
                            } else {
                                html = '<button type="button" class="btn btn-sm btn-danger" style="width:150px">' + data + '</button>'
                            }

                            return html;
                        },
                        name: 'status',
                    }
                ],
            });
        }

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
            $('#tabel-resep').DataTable().destroy();
            load_data(tgl_pertama, tgl_kedua, $('#poli').val());
            hitungEresep(tgl_pertama, tgl_kedua, $('#poli').val());
        });

        $('#poli').change(function() {
            console.log(tgl_pertama)
            cekTanggal();
            $('#tabel-resep').DataTable().destroy();
            load_data(tgl_pertama, tgl_kedua, $(this).val());
        })
    </script>
@endpush
