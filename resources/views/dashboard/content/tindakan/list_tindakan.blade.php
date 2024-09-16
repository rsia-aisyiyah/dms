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
                                            <span class="input-group-text" id="tahun-addon"><i
                                                    class="fas fa-calendar"></i></span>
                                        </div>
                                        <input type="text" id="yearpicker" class="form-control datetimepicker-input"
                                            data-toggle="datetimepicker" aria-describedby="tahun-addon"
                                            data-target="#yearpicker" autocomplete="off"
                                            placeholder="{{ date('Y') }}" />
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="">Dokter</label>
                                        <div class="input-group">
                                            <select name="dokter" id="dokter" class="custom-select form-control-border">
                                                <option value="">Semua Dokter</option>

                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive text-sm">
                                <table class="table table-striped" id="table-tindakan" style="width: 100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Bulan</th>
                                            <th>Jumlah</th>
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
        var tahun = {{ date('Y') }};
        var dokter;
        $('#yearpicker').on('change.datetimepicker', function() {
            tahun = $(this).val();
            $('#table-tindakan').DataTable().destroy();
            load_data($(this).val(), dokter);
        });
        $('#dokter').change(function(e) {
            dokter = $(this).val();
            $('#table-tindakan').DataTable().destroy();
            console.log(dokter);
            load_data(tahun, $(this).val());
        })

        function load_data(tahun = "", dokter = "") {

            $('#table-tindakan').DataTable({
                ajax: {
                    url: 'tindakan/json',
                    dataType: 'json',
                    data: {
                        tahun: tahun,
                        dokter: dokter,
                    },
                },
                processing: true,
                serverSide: true,
                destroy: false,
                deferRender: true,
                lengthChange: false,
                ordering: false,
                searching: false,
                stateSave: true,
                paging: false,
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
                },
                buttons: [{
                        extend: 'copy',
                        text: '<i class="fas fa-copy"></i> Salin',
                        className: 'btn btn-info',
                        title: 'laporan-tindakan-dokter-{{ date('dmy') }}'
                    },
                    {
                        extend: 'csv',
                        text: '<i class="fas fa-file-csv"></i> CSV',
                        className: 'btn btn-info',
                        title: 'laporan-tindakan-dokter-{{ date('dmy') }}'
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'btn btn-info',
                        title: 'laporan-tindakan-dokter-{{ date('dmy') }}'
                    },
                ],
                columns: [{
                        data: 'bulan',
                        name: 'bulan'
                    },
                    {
                        data: 'jumlah',
                        name: 'jumlah'
                    }

                ],
            });
        }

        $(document).ready(function() {

            $.ajax({
                url: 'dokter',
                dataType: 'JSON',
                success: function(response) {
                    $.each(response, function(key, res) {
                        $('#dokter').append($("<option></option>")
                            .attr("value", res.kd_dokter)
                            .text(res.nm_dokter));
                    })
                }
            })

            $('#yearpicker').datetimepicker({
                format: "YYYY",
                useCurrent: true,
                viewMode: "years"
            });

            load_data();
        });
    </script>
@endpush
