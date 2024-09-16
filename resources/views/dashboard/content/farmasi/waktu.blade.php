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
                        <div class="col-xl-6 col-md-12 col-sm-12">
                            <div class="form-group">
                                <label>Filter Data </label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="tanggal" name="tanggal" autocomplete="off" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive text-sm">
                                <table class="table table-bordered" id="tabel-waktu-resep" style="width: 100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No Rawat</th>
                                            <th>Nama Pasien</th>
                                            <th>Poliklinik</th>
                                            <th>Dokter</th>
                                            <th>Jenis Resep</th>
                                            <th>Tanggal</th>
                                            <th>Peresepan</th>
                                            <th>Validasi</th>
                                            <th>Penyerahan</th>
                                            <th>Waktu Tunggu</th>
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
        var tgl_pertama = '';
        var tgl_kedua = '';
        $(document).ready(function() {
            tbWaktuResep();
        });


        function tbWaktuResep(tgl_pertama, tgl_kedua, poli) {
            $('#tabel-waktu-resep').DataTable({
                ajax: {
                    url: '/dms/farmasi/resep/waktu/json',
                    dataType: 'JSON',
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
                        render: function(data, type, row, meta) {
                            return data;
                        },
                        name: 'no_rawat'
                    },
                    {
                        data: null,
                        render: (data, type, row, meta) => {
                            return row.reg_periksa.pasien.nm_pasien;
                        },
                        name: 'nm_pasien',
                    },
                    {
                        data: null,
                        render: (data, type, row, meta) => {
                            return `${row.reg_periksa.poli.nm_poli}`;
                        },
                        name: 'poliklinik',
                    },
                    {
                        data: 'dokter',
                        render: (data, type, row, meta) => {
                            return `${row.dokter.nm_dokter}`;
                        },
                        name: 'dokter',
                    },
                    {
                        data: null,
                        render: (data, type, row, meta) => {
                            console.log(row);
                            umum = Object.keys(row.resep_dokter).length;
                            racikan = Object.keys(row.resep_dokter_racikan).length;
                            // return `${umum}-${racikan}`
                            if (racikan > 0) {
                                return `<button type="button" class="btn btn-primary btn-sm">Racikan</button>`
                            } else {
                                return `<button type="button" class="btn btn-success btn-sm">Non Racikan</button>`
                            }
                        },
                        name: 'jnsResep',

                    },
                    {
                        data: null,
                        render: (data, type, row, meta) => {
                            return formatTanggal(row.tgl_peresepan)
                        },
                        name: 'tgl_peresepan',

                    },
                    {
                        data: null,
                        render: (data, type, row, meta) => {
                            return row.jam_peresepan
                        },
                        name: 'jam_peresepan',
                    },
                    {
                        data: null,
                        render: (data, type, row, meta) => {
                            return row.jam
                        },
                        name: 'jam',
                    },
                    {
                        data: null,
                        render: (data, type, row, meta) => {
                            return row.jam_penyerahan
                        },
                        name: 'jam_penyerahan',
                    },
                    {
                        data: null,
                        render: (data, type, row, meta) => {
                            // return row.jam_penyerahan

                            hours = row.jam_penyerahan.split(':')[0] - row.jam.split(':')[0];
                            minutes = row.jam_penyerahan.split(':')[1] - row.jam.split(':')[1];
                            seconds = row.jam_penyerahan.split(':')[2] - row.jam.split(':')[2];


                            if (minutes < 0) {
                                hours--;
                                minutes = 60 + minutes;
                            }

                            if (seconds < 0) {
                                minutes--;
                                seconds = 60 + seconds;
                            }
                            minutes = minutes.toString().length < 2 ? '0' + minutes : minutes;
                            seconds = seconds.toString().length < 2 ? '0' + seconds : seconds;
                            hours = hours.toString().length < 2 ? '0' + hours : hours;

                            // $('#delay').val(hours + ':' + minutes);
                            return `${hours}:${minutes}:${seconds}`
                        },
                        name: 'waktu_tunggu',
                    },
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
            $('#tabel-waktu-resep').DataTable().destroy();
            tbWaktuResep(tgl_pertama, tgl_kedua, $('#poli').val());

        });
    </script>
@endpush
