@extends('dashboard.layouts.main')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-teal">
            <div class="card-header">
                <p class="card-title border-bottom-0">{{$title}} </p>
                <div class="card-tools mr-4" id="bulan">
                    <span style="font-size: 1.2em"><strong>{{$month}}</strong></span>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Tanggal : </label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="tanggal" name="tanggal"
                                    autocomplete="off" />
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <label>Kode Diagnosa :</label>
                        <div class="input-group">
                            <input type="search" class="form-control" id="diagnosa" name="diagnosa" autocomplete="off"
                                placeholder="Cari Kode Diagnosa" />
                            <div id="listDiagnosa"></div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <label>Status Rawat :</label>
                        <div class="form-group clearfix mt-2">
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
                    <div class="col-sm-3">
                        <label>Status Rawat :</label>
                        <div class="form-group">
                            <select class="custom-select form-control-border" id="pembiayaan" name="pembiayaan">
                                <option value="">Semua Pembiayaan</option>
                                <option value="bpjs">BPJS</option>
                                <option value="umum">Umum</option>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="row mb-2">
                    <div class="col">
                        <div class="item">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive text-sm">
                            <table class="table table-bordered" id="tabel-penyakit" style="width: 100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>No RM</th>
                                        <th>No Registrasi</th>
                                        <th>Nama Pasien</th>
                                        <th>Tgl Lahir</th>
                                        <th>Umur</th>
                                        <th>NIK</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Alamat</th>
                                        <th>Status Daftar</th>
                                        <th>Kode Diagnosa</th>
                                        <th>Nama Penyakit</th>
                                        <th>Status Rawat</th>
                                        <th>Pembiayaan</th>
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

    var tgl_pertama = '', tgl_kedua = '', status = '', diagnosa = '', pembiayaan = '';
    var diagnosa;
    var item = [];

    function tambahDiagnosa(diagnosa) {
        item.push(diagnosa);
        console.log(item);
        refreshDiagnosa();

    }
    function refreshDiagnosa() {
        $('.item').html('<span class="text-red">Diagnosa yang dicari : </span>');
        $.each(item, function (index, value) {
            html = '<span class="mr-2">' + value + '<button class="ml-1 btn btn-xs btn-outline-danger" type="button" onclick="hapusDiagnosa(' + index + ')">x</button></span>';
            $('.item').append(html)
        })
    }
    function hapusDiagnosa(index) {
        item.splice(index, 1);
        refreshDiagnosa();
        $('#tabel-penyakit').DataTable().destroy();
        load_data(tgl_pertama, tgl_kedua, cekStatus(), item, pembiayaan);
    }

    function load_data(tgl_pertama, tgl_kedua, status, diagnosa, pembiayaan) {
        $('#tabel-penyakit').DataTable({
            ajax: {
                url: 'penyakit/json',
                data: {
                    tgl_pertama: tgl_pertama,
                    tgl_kedua: tgl_kedua,
                    status: status,
                    diagnosa: diagnosa,
                    pembiayaan: pembiayaan,
                }
            },
            processing: true,
            serverSide: true,
            destroy: false,
            deferRender: true,
            lengthChange: true,
            ordering: false,
            searching: true,
            stateSave: true,
            scrollY: 300,
            scrollX: true,
            scroller: {
                loadingIndicator: true
            },
            paging: true,
            dom: 'Blfrtip',
            initComplete: function (settings, json) {
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
            buttons: [
                { extend: 'copy', className: 'btn btn-info', title: 'daftar-10-besar-penyakit{{date("dmy")}}' },
                { extend: 'csv', className: 'btn btn-info', title: 'daftar-10-besar-penyakit{{date("dmy")}}' },
                { extend: 'excel', className: 'btn btn-info', title: 'daftar-10-besar-penyakit{{date("dmy")}}' },
                {
                    extend: 'pdf', className: 'btn btn-info', title: 'daftar-10-besar-penyakit{{date("dmy")}}', exportOptions: {
                        modifier: {
                            search: 'applied',
                            order: 'applied'
                        }
                    }
                },
                { extend: 'print', className: 'btn btn-info', title: 'daftar-10-besar-penyakit{{date("dmy")}}' },
            ],
            columns: [
                { data: 'tanggal', name: 'tanggal' },
                { data: 'no_rkm_medis', name: 'no_rkm_medis' },
                { data: 'no_rawat', name: 'no_rawat' },
                { data: 'nm_pasien', name: 'nm_pasien' },
                { data: 'tgl_lahir', name: 'tgl_lahir' },
                { data: 'umur', name: 'umur' },
                { data: 'no_ktp', name: 'no_ktp' },
                { data: 'jk', name: 'jk' },
                { data: 'alamat', name: 'alamat' },
                { data: 'status_daftar', name: 'status_daftar' },
                { data: 'kd_penyakit', name: 'kd_penyakit' },
                { data: 'nm_penyakit', name: 'nm_penyakit' },
                { data: 'status', name: 'status' },
                { data: 'pembiayaan', name: 'pembiayaan' },
            ],
        });
    }
    $(document).ready(function () {
        refreshDiagnosa();
        $('#tanggal').on('apply.daterangepicker', function (env, picker) {
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
            $('#tabel-penyakit').DataTable().destroy();

            load_data(tgl_pertama, tgl_kedua, cekStatus(), item, pembiayaan);
        });

        $('#diagnosa').keyup(function () {
            var query = $(this).val();
            if (query != '') {
                $.ajax({
                    url: 'cari',
                    method: "GET",
                    data: { query: query },
                    success: function (data) {
                        $('#listDiagnosa').fadeIn();
                        $('#listDiagnosa').html(data);

                    }
                });
            }
        });

        $("#diagnosa").on("search", function (evt) {
            if ($(this).val().length == 0) {
                $('#tabel-penyakit').DataTable().destroy();
                load_data(tgl_pertama, tgl_kedua, cekStatus(), '', pembiayaan);
            }
        });


        $('#listDiagnosa').on('click', 'li', function () {
            $('#diagnosa').val('');
            $('#listDiagnosa').fadeOut();
            let text = $(this).text();
            const txtArray = text.split(" - ");
            cekTanggal();
            diagnosa = txtArray[0];

            tambahDiagnosa(diagnosa);

            $('#tabel-penyakit').DataTable().destroy();
            load_data(tgl_pertama, tgl_kedua, cekStatus(), item);
        });

        $(document).click(function () {
            $('#listDiagnosa').fadeOut();
        });

        $('#ralan').click(function () {
            cekTanggal();
            $('#tabel-penyakit').DataTable().destroy();
            load_data(tgl_pertama, tgl_kedua, cekStatus(), item, pembiayaan);
        });

        $('#ranap').click(function () {
            cekTanggal();
            $('#tabel-penyakit').DataTable().destroy();
            load_data(tgl_pertama, tgl_kedua, cekStatus(), item, pembiayaan);
        });

        $('#pembiayaan').change(function () {
            $('#tabel-penyakit').DataTable().destroy();
            pembiayaan = $(this).val();
            load_data(tgl_pertama, tgl_kedua, cekStatus(), item, pembiayaan);
        })

        load_data();

    });
</script>

@endpush