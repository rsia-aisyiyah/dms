@extends('dashboard.layouts.main')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-teal">
                <div class="card-header">
                    <p class="card-title border-bottom-0">{{$title}} </p>
                    <div class="card-tools mr-4" id="bulan">
                        <span><strong>{{$month}}</strong></span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-xl-3 col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <label>Tanggal</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="tanggal" name="tanggal" autocomplete="off"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive text-sm">
                                <table class="table table-striped"  id="table-transfusi" style="width: 100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No Registrasi</th>
                                            <th>Tanggal Transfusi</th>
                                            <th>Nama Pasien</th>
                                            <th>Jenis Transfusi</th>
                                            <th>Jumlah Kantong</th>
                                            <th>Dokter</th>
                                            <th>Spesialis</th>
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
    @include('dashboard.content.ranap.rekap_transfusi')
    @include('dashboard.content.ranap.diagram_transfusi')
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

                $('#bulan').html('<strong>'+tgl1+' s/d '+tgl2+'</strong>');
                $('#table-transfusi').DataTable().destroy();
                load_data(tgl_pertama, tgl_kedua); 
            });

        load_data();
        function load_data(tgl_pertama, tgl_kedua) {
          $('#table-transfusi').DataTable({
            ajax: {
                url:'transfusi/json',
                dataType:'json',
                data: {
                        tgl_pertama:tgl_pertama,
                        tgl_kedua:tgl_kedua,
                    },
                },
            processing: true,
            serverSide: true,
            destroy: false,
            deferRender:true,
            lengthChange: false,
            ordering:false,
            searching : false,
            stateSave: true,
            paging:false,
            dom: 'Blfrtip',
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
                },
            buttons: [
                {extend: 'copy', text:'<i class="fas fa-copy"></i> Salin',className:'btn btn-info', title: 'laporan-jumlah-pasien-bayi-{{date("dmy")}}'},
                {extend: 'csv',  text:'<i class="fas fa-file-csv"></i> CSV',className:'btn btn-info', title: 'laporan-jumlah-pasien-bayi-{{date("dmy")}}'},
                {extend: 'excel', text:'<i class="fas fa-file-excel"></i> Excel',className:'btn btn-info', title: 'laporan-jumlah-pasien-bayi-{{date("dmy")}}'},
            ],
            columns:[
                {data:'no_rawat', name:'no_rawat'},
                {data:'tgl_perawatan', name:'tgl_perawatan'},
                {data:'nm_pasien', name:'nm_pasien'},
                {data:'nm_perawatan', name:'nm_perawatan'},
                {data:'jumlah', name:'jumlah'},
                {data:'dokter', name:'dokter'},
                {data:'spesialis', name:'spesialis'},
                ],
            });
        }

        $('#yearpicker').on('change.datetimepicker', function(){
            var tahun = $(this).val();
            $('#table-transfusi').DataTable().destroy();
            load_data(tahun);  
        });

});
</script>
    
@endpush