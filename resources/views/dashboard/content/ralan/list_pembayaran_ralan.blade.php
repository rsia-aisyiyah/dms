    <div class="col-12 col-sm-12 col-md-4">
        <div class="card card-teal">
            <div class="card-header">
                <p class="card-title border-bottom-0">Cara Bayar Pasien</p>
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
                        <div class="table-responsive">
                            <table class="table table-striped text-sm"  id="table-pembayaran" style="width: 100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Bulan</th>
                                        <th>BPJS</th>
                                        <th>Umum</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@push('scripts')
    <script>
    $(document).ready(function(){

            
    $('#yearpicker').datetimepicker({
        format: "YYYY",
        useCurrent: false,
        viewMode: "years"
    });

    load_data();
    function load_data(tahun) {
    $('#table-pembayaran').DataTable({
        ajax: {
            url:'ralan/bayar/json',
            dataType:'json',
            data: {
                    tahun:tahun,
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
        info: false,
        initComplete: function(settings, json) {
                            toastr.success('Data telah dimuat', 'Berhasil');
                        },
        language: {
                processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i> <span class="sr-only">Loading...</span>',
                zeroRecords: "Tidak Ditemukan Data",
                infoEmpty:      "",
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
            {data:'bulan', name:'bulan'},
            {data:'bpjs', name:'bpjs'},
            {data:'umum', name:'umum'},
            ],
        });
    }

    $('#yearpicker').on('change.datetimepicker', function(){
            var tahun = $(this).val();
            $('#table-pembayaran').DataTable().destroy();
            load_data(tahun);  
    });

    });    
    </script>
@endpush