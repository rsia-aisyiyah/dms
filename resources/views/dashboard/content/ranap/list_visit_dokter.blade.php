@extends('dashboard.layouts.main')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-teal">
                <div class="card-header">
                    <p class="card-title border-bottom-0">{{ $title }} </p>
                    <div class="card-tools mr-4" id="bulan">
                        <span><strong>{{ $month }}</strong></span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-6">
                                    <label>Tahun</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="tahun-addon"><i class="fas fa-calendar"></i></span>
                                        </div>
                                        <input type="text" id="yearpicker" class="form-control datetimepicker-input" data-toggle="datetimepicker" aria-describedby="tahun-addon" data-target="#yearpicker" autocomplete="off" />
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive text-sm">
                                <table id="visitTable" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Dokter</th>
                                            <th>Januari</th>
                                            <th>Februari</th>
                                            <th>Maret</th>
                                            <th>April</th>
                                            <th>Mei</th>
                                            <th>Juni</th>
                                            <th>Juli</th>
                                            <th>Agustus</th>
                                            <th>September</th>
                                            <th>Oktober</th>
                                            <th>November</th>
                                            <th>Desember</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <button id="exportVisit" class="btn btn-success"><i class="fas fa-file-excel mr-2"></i>Export ke XLS</button>
                </div>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <div class="col-sm-8">
            <div class="card card-teal">
                <div class="card-header">
                    <p class="card-title border-bottom-0">{{ $title }} </p>
                    <div class="card-tools mr-4" id="bulan">
                        <span><strong>{{ $month }}</strong></span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-3">
                                    <label>Bulan</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="tahun-addon"><i class="fas fa-calendar"></i></span>
                                        </div>
                                        <input type="text" id="monthPickerCpptVisit" class="form-control datetimepicker-input monthpicker" data-toggle="datetimepicker" aria-describedby="tahun-addon" data-target="#monthPickerCpptVisit" autocomplete="off" />
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive text-sm">
                                <table class="table table-striped table-bordered" id="table-cppt-visit">

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
        function parseTanggalVisit(tgl, jam) {
            if (tgl === "0000-00-00" && jam === "00:00:00") {
                return new Date(); // current date and time
            }
            return new Date(`${tgl}T${jam}`)
        }



        function isCpptInKamar(examTime, kamarInap) {
            const invalidTanggal = kamarInap.tgl_keluar === "0000-00-00" && kamarInap.jam_keluar === "00:00:00"
            if (invalidTanggal) {
                return false;
            }
            const roomStart = parseTanggalVisit(kamarInap.tgl_masuk, kamarInap.jam_masuk);
            const roomEnd = parseTanggalVisit(kamarInap.tgl_keluar, kamarInap.jam_keluar);
            return examTime >= roomStart && examTime <= roomEnd;
        }

        function loadTableCpptVisit(month = '', year = '') {
            $('#table-cppt-visit').DataTable({
                ajax: {
                    url: `${url}/ranap/visit/cppt/json`,
                    data: {
                        month: month,
                        year: year,
                    },
                },
                processing: true,
                serverSide: true,
                destroy: true,
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
                        name: 'no_rawat',
                        data: 'no_rawat',
                        render: (data, type, row, meta) => {
                            return data;
                        },
                        title: 'No. Rawat',
                    },
                    {
                        name: 'nama',
                        data: 'reg_periksa.pasien.nm_pasien',
                        render: (data, type, row, meta) => {
                            return data;
                        },
                        title: 'Nama',
                    },
                    {
                        name: 'tanggal',
                        data: 'tgl_perawatan',
                        render: (data, type, row, meta) => {
                            return formatTanggal(data);
                        },
                        title: 'Tgl. Pemeriksaan',
                    },
                    {
                        name: 'jam',
                        data: 'jam_rawat',
                        render: (data, type, row, meta) => {
                            return data;
                        },
                        title: 'Jam',
                    },
                    {
                        name: 'dokter',
                        data: 'petugas.nama',
                        render: (data, type, row, meta) => {
                            return data;
                        },
                        title: 'Dokter DPJP',
                    },
                    {
                        name: 'kamar_inap',
                        data: 'kamar_inap',
                        render: (data, type, row, meta) => {
                            let kamar = '';
                            if (data) {
                                kamar = data;
                            } else {
                                kamar = row.reg_periksa.ranap_gabung.kamarInap
                            }
                            const examTime = parseTanggalVisit(row.tgl_perawatan, row.jam_rawat);
                            for (const room of kamar) {
                                if (isCpptInKamar(examTime, room)) {
                                    examinedRoom = room.kamar.bangsal.nm_bangsal;
                                    break;
                                }
                            }
                            return examinedRoom;
                        },
                        title: 'Kamar',
                    },
                ],
            });
        }
        $(document).ready(function() {
            loadTableCpptVisit()

            $("#exportVisit").click(function(e) {
                const table = $('#visitTable');
                if (table && table.length) {
                    var preserveColors = (table.hasClass('table2excel_with_colors') ? true : false);
                    $(table).table2excel({
                        exclude: ".noExl",
                        name: "Excel Document Name",
                        filename: "dataVisiteDokter" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
                        fileext: ".xls",
                        exclude_img: true,
                        exclude_links: true,
                        exclude_inputs: true,
                        preserveColors: preserveColors
                    });
                }
            });

            $('#yearpicker').datetimepicker({
                format: "YYYY",
                useCurrent: false,
                viewMode: "years"
            });

            $('.yearpicker').datetimepicker({
                format: "YYYY",
                useCurrent: false,
                viewMode: "years",

            });
            $('.monthpicker').datetimepicker({
                format: "MM-YYYY",
                useCurrent: false,
                viewMode: "months",

            });




            $('#monthPickerCpptVisit').on('change.datetimepicker', function(e) {
                const month = e.date.month() + 1;
                const year = e.date.year();
                loadTableCpptVisit(month, year)

            })
            load_data();

            function load_data(tahun = '') {
                $.ajax({
                    url: `${url}/ranap/visit/json`,
                    method: "GET",
                    data: {
                        tahun: tahun
                    },
                    dataType: "json",
                    success: function(data) {
                        var tableBody = $("#visitTable tbody");
                        tableBody.empty();

                        var doctorData = {};

                        $.each(data, function(month, visits) {
                            $.each(visits, function(doctor, count) {
                                if (!doctorData[doctor]) {
                                    doctorData[doctor] = {
                                        "Januari": 0,
                                        "Februari": 0,
                                        "Maret": 0,
                                        "April": 0,
                                        "Mei": 0,
                                        "Juni": 0,
                                        "Juli": 0,
                                        "Agustus": 0,
                                        "September": 0,
                                        "Oktober": 0,
                                        "November": 0,
                                        "Desember": 0
                                    };
                                }
                                doctorData[doctor][month] = count;
                            });
                        });

                        $.each(doctorData, function(doctor, months) {
                            var row = "<tr>" +
                                "<td>" + doctor + "</td>" +
                                "<td>" + months.Januari + "</td>" +
                                "<td>" + months.Februari + "</td>" +
                                "<td>" + months.Maret + "</td>" +
                                "<td>" + months.April + "</td>" +
                                "<td>" + months.Mei + "</td>" +
                                "<td>" + months.Juni + "</td>" +
                                "<td>" + months.Juli + "</td>" +
                                "<td>" + months.Agustus + "</td>" +
                                "<td>" + months.September + "</td>" +
                                "<td>" + months.Oktober + "</td>" +
                                "<td>" + months.November + "</td>" +
                                "<td>" + months.Desember + "</td>" +
                                "</tr>";
                            tableBody.append(row);
                        });
                    },
                    error: function(error) {
                        console.log("Error fetching data", error);
                    }
                });
            }

            $('#yearpicker').on('change.datetimepicker', function() {
                var tahun = $(this).val();
                $('#table-visit-dokter').DataTable().destroy();
                load_data(tahun);
            });

        });
    </script>
@endpush
