@extends('dashboard.layouts.main')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-teal">
                <div class="card-header">
                    <p class="card-title border-bottom-0">{{$title}} </p>
                    <div class="card-tools mr-4" id="bulan">
                        <span><strong>{{$month}}</strong></span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Tanggal</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="tanggal" name="tanggal" autocomplete="off"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-bordered"  id="table-dinkes" style="width: 100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Kode Diagnosa</th>
                                            <th>Nama Penyakit</th>
                                            <th>Jumlah</th>
                                            <th>Status Rawat</th>
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
    var tgl_pertama, tgl_kedua;
$(document).ready(function(){

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

        tgl1 = hari1+' '+bulan[bulan1]+' '+tahun1
        tgl2 = hari2+' '+bulan[bulan2]+' '+tahun2



        $('#bulan').html(tgl1+' s/d '+tgl2);
        $('#table-dinkes').DataTable().destroy();
        cekStatus();
        load_data(tgl_pertama, tgl_kedua, status); 
    });

    load_data();
    function load_data(tgl_pertama='', tgl_kedua='', status='') {
        $('#table-dinkes').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url:'dinkes/json',
            data: {
                tgl_pertama:tgl_pertama,
                tgl_kedua:tgl_kedua,
                status:status,
                }
            },
        ordering:false,
        lengthChange: true,
        orderable:false,
        scrollY: "350px",
        scrollX: true,
        scroller: false,
        paging:true,
        dom: 'Bfrtip',
        initComplete: function(settings, json) {
                            toastr.success('Data telah dimuat', 'Berhasil');
                        },
        language: {
                processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i> <span class="sr-only">Loading...</span>',
                zeroRecords: "Tidak Ditemukan Data",
                infoEmpty:      "",
                info: "Menampilkan sebanyak _START_ ke _END_ dari _TOTAL_ data",
                loadingRecords: "Sedang memuat ...",
                infoFiltered:   "(Disaring dari _MAX_ total baris)",
                buttons: {
                            copyTitle: 'Data telah disalin',
                            copySuccess: {
                                            _: '%d baris data telah disalin',
                                        },
                        },
                lengthMenu: '<div class="text-md mt-3">Tampilkan <select>'+
                                '<option value="50">50</option>'+
                                '<option value="100">100</option>'+
                                '<option value="200">200</option>'+
                                '<option value="250">250</option>'+
                                '<option value="500">500</option>'+
                                '<option value="-1">Semua</option>'+
                                '</select> Baris',
                paginate: {
                                "first":      "Pertama",
                                "last":       "Terakhir",
                                "next":       "Selanjutnya",
                                "previous":   "Sebelumnya"
                            },
                            search: 'Cari Penyakit : ',
            },
        buttons: [
            {extend: 'copy', text:'<i class="fas fa-copy"></i> Salin',className:'btn btn-info', title: 'laporan-kunjungan-pasien-rawat-jalan{{date("dmy")}}'},
            {extend: 'csv',  text:'<i class="fas fa-file-csv"></i> CSV',className:'btn btn-info', title: 'laporan-kunjungan-pasien-rawat-jalan{{date("dmy")}}'},
            {extend: 'excel', text:'<i class="fas fa-file-excel"></i> Excel',className:'btn btn-info', title: 'laporan-kunjungan-pasien-rawat-jalan{{date("dmy")}}'},
        ],
        columns:[
            {data:'kd_penyakit', name:'kd_penyakit'},
            {data:'nm_penyakit', name:'nm_penyakit'},
            {data:'jumlah', name:'jumlah'},
            {data:'status', name:'status'},
            ],
        });
    }

    
    $('#ralan').click(function(){
        cekTanggal();
        $('#table-dinkes').DataTable().destroy();
        load_data(tgl_pertama, tgl_kedua, cekStatus());
    })

    $('#ranap').click(function(){
        cekTanggal();
        $('#table-dinkes').DataTable().destroy();
        load_data(tgl_pertama, tgl_kedua, cekStatus());
    })

    $('#spesialis').change(function(){
        cekTanggal();
        cekStatus();
        $('#table-dinkes').DataTable().destroy();
        load_data(tgl_pertama, tgl_kedua, cekStatus(), $(this).val());
    })
});
</script>
    
@endpush