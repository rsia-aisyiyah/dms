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
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Tanggal :</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="tanggal" name="tanggal" autocomplete="off"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive text-sm">
                                <table class="table table-bordered"  id="table-pasien-tb" style="width: 100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Tanggal Registrasi</th>
                                            <th>No. Rawat</th>
                                            <th>No. RM</th>
                                            <th>Nama Pasien</th>
                                            <th>Tgl Lahir</th>
                                            <th>Umur</th>
                                            <th>NIK</th>
                                            <th>Jenis Kelamin</th>
                                            <th>Alamat</th>
                                            <th>Kode Penyakit</th>
                                            <th>Nama Penyakit</th>
                                            <th>Status Rawat</th>
                                            <th>Poli</th>
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
    $(document).ready(function(){

        $('#tanggal').daterangepicker({
        locale : {
            language: 'id' ,
            applyLabel: 'Terapkan',
            cancelLabel: 'Batal',
            format:'DD/MM/YYYY',
            daysOfWeek: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Ming'],
            monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
        },
            startDate: moment().startOf('month'),
            autoclose:true,
            showDropdowns: true,
            minYear: 2019,
            maxYear: {{date('Y')+1}},
    });

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
        $('#table-pasien-tb').DataTable().destroy();
        load_data(tgl_pertama, tgl_kedua); 
    });

        load_data();
        function load_data(tgl_pertama='', tgl_kedua='') {
            $('#table-pasien-tb').DataTable({
            ajax: {
                url:'pasientb/json',
                data: {
                    tgl_pertama:tgl_pertama,
                    tgl_kedua:tgl_kedua,
                    }
                },
            processing: true,
            searching : false,
            serverSide: true,
            lengthChange: true,
            ordering: false,
            scrollY: "350px",
            scrollX: true,
            scroller: false,
            paging:false,
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
                {data:'tgl_registrasi', name:'tgl_registrasi'},
                {data:'no_rawat', name:'no_rawat'},
                {data:'no_rkm_medis', name:'no_rkm_medis'},
                {data:'nm_pasien', name:'nm_pasien'},
                {data:'tgl_lahir', name:'tgl_lahir'},
                {data:'umurdaftar', name:'umurdaftar'},
                {data:'no_ktp', name:'no_ktp'},
                {data:'jk', name:'jk'},
                {data:'alamat', name:'alamat'},
                {data:'kd_penyakit', name:'kd_penyakit'},
                {data:'nm_penyakit', name:'nm_penyakit'},
                {data:'stts_daftar', name:'stts_daftar'},
                {data:'nm_poli', name:'nm_poli'},
                ],
            });
        }

        
    });
</script>
    
@endpush