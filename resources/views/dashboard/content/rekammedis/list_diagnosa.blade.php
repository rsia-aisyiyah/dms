@extends('dashboard.layouts.main')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-teal">
                <div class="card-header">
                    <p class="card-title border-bottom-0">{{ $title }}</p>
                    <div class="card-tools mr-4" id="bulan">
                        <span>{{ $month }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Tanggal</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="tanggal" name="tanggal" autocomplete="off" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <div class="clearfix mb-2">
                                    <label for="">Status Daftar</label>
                                </div>
                                <div class="icheck-teal d-inline mr-3">
                                    <input type="radio" name="status" id="ralan" value="ralan">
                                    <label for="ralan">Rawat Jalan</label>
                                </div>
                                <div class="icheck-teal d-inline">
                                    <input type="radio" name="status" id="ranap" value="ranap">
                                    <label for="ranap">Rawat Inap</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <label for="">Spesialis</label>
                            <div class="form-group">
                                <select class="custom-select form-control-border" id="spesialis" name="spesialis">
                                    <option value="">Semua Spesialis</option>
                                    <option value="anak">Anak</option>
                                    <option value="kandungan">Kandungan dan Kebidanan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <label for="">Pembiayaan</label>
                            <div class="form-group">
                                <select class="custom-select form-control-border" id="pembiayaan" name="pembiayaan">
                                    <option value="">BPJS & Umum</option>
                                    <option value="bpjs">BPJS</option>
                                    <option value="umum">Umum</option>
                                </select>
                            </div>
                        </div>
                        {{-- <div class="col-sm-4">
                            <label for="">Exclude</label>
                            <div class="form-group">
                                <select class="form-select-2 select2 select2-purple" id="exclude" name="exclude" style="width: 100%" multiple>

                                </select>
                            </div>
                        </div> --}}
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-bordered text-sm" id="table-diagnosa" style="width: 100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Kode Diagnosa</th>
                                            <th>Nama Penyakit</th>
                                            <th>Status Rawat</th>
                                            <th>Pembiayaan</th>
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
        var tgl_pertama = '',
            tgl_kedua = '',
            status = '',
            pembiayaan = '',
            spesialis = '';

        $(document).ready(function() {

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



                $('#bulan').html(tgl1 + ' s/d ' + tgl2);
                $('#table-diagnosa').DataTable().destroy();
                load_data(tgl_pertama, tgl_kedua, cekStatus(), spesialis, pembiayaan);
            });

            load_data();

            function load_data(tgl_pertama, tgl_kedua, status = '', spesialis = '', pembiayaan = '') {

                $('#table-diagnosa').DataTable({
                    ajax: {
                        url: 'rekammedis/json',
                        data: {
                            tgl_pertama: tgl_pertama,
                            tgl_kedua: tgl_kedua,
                            status: status,
                            spesialis: spesialis,
                            pembiayaan: pembiayaan,
                        }
                    },
                    processing: true,
                    serverSide: true,
                    lengthChange: true,
                    ordering: false,
                    scrollY: "350px",
                    scrollX: true,
                    scroller: false,
                    paging: true,
                    dom: 'Bfrtip',
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
                        search: 'Cari Penyakit : ',
                    },
                    buttons: [{
                            extend: 'copy',
                            text: '<i class="fas fa-copy"></i> Salin',
                            className: 'btn btn-info',
                            title: 'laporan-10-besar-penyakit-{{ date('d F Y') }}'
                        },
                        {
                            extend: 'csv',
                            text: '<i class="fas fa-file-csv"></i> CSV',
                            className: 'btn btn-info',
                            title: 'laporan-10-besar-penyakit-{{ date('d F Y') }}'
                        },
                        {
                            extend: 'excel',
                            text: '<i class="fas fa-file-excel"></i> Excel',
                            className: 'btn btn-info',
                            title: 'laporan-10-besar-penyakit-{{ date('d F Y') }}'
                        },
                    ],
                    columns: [{
                            data: 'kd_penyakit',
                            name: 'kd_penyakit'
                        },
                        {
                            data: 'nm_penyakit',
                            name: 'nm_penyakit'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'pembiayaan',
                            name: 'pembiayaan'
                        },
                        {
                            data: 'jumlah',
                            name: 'jumlah'
                        },
                    ],
                });
            }


            $('#ralan').click(function() {
                cekTanggal();
                $('#table-diagnosa').DataTable().destroy();
                load_data(tgl_pertama, tgl_kedua, cekStatus());
            })

            $('#ranap').click(function() {
                cekTanggal();
                $('#table-diagnosa').DataTable().destroy();
                load_data(tgl_pertama, tgl_kedua, cekStatus());
            })

            $('#spesialis').change(function() {
                cekTanggal();
                cekStatus();
                $('#table-diagnosa').DataTable().destroy();
                load_data(tgl_pertama, tgl_kedua, cekStatus(), $(this).val());
            })

            $('#pembiayaan').change(function() {
                cekTanggal();
                cekStatus();
                $('#table-diagnosa').DataTable().destroy();
                load_data(tgl_pertama, tgl_kedua, cekStatus(), $('#spesialis').val(), $(this).val());
            })

            // $('#exclude').select2({
            //     tags: ['R.', 'Z.'],
            //     ajax: {
            //         url: './penyakit/cari',
            //         dataType: 'json',
            //         data: (params) => {
            //             return {
            //                 query: params.term
            //             }

            //         },
            //         processResults: function(data) {
            //             return {
            //                 results: data.map((item) => {
            //                     const items = {
            //                         id: item.kd_penyakit,
            //                         text: `${item.kd_penyakit} - ${item.nm_penyakit}`,
            //                     }
            //                     return items;
            //                 })
            //             }
            //         },
            //         cache: true
            //     }
            // })

        });
    </script>
@endpush
