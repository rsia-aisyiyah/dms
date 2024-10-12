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
                                {{-- <table class="table table-striped" id="table-visit-dokter" style="width: 100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Bulan</th>
                                            <th>dr. Himawan Budityastomo, SpO.G</th>
                                            <th>dr. Dwi Riyanto, Sp.A</th>
                                            <th>dr. Siti Pattihatun Nasyiroh, Sp.OG</th>
                                            <th>dr. Rendy Yoga Ardian, Sp.A</th>
                                            <th>dr. Achmad Dahlan K. Sp.OG</th>
                                            <th>dr. Pravitasari. Sp.OG</th>
                                        </tr>
                                    </thead>
                                </table> --}}
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
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

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

            load_data();

            function load_data(tahun = '') {
                $.ajax({
                    url: "http://localhost/dms/ranap/visit/json",
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
