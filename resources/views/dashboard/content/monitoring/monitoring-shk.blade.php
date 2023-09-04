@extends('dashboard.layouts.main')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-teal">
            <div class="card-header">
                <h3 class="card-title">Data Pasien</h3>
                <div class="card-tools" id="bulan">
                    {{-- <span><strong>{{ $month }}</strong></span> --}}
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-3 col-md-4">
                        <div class="form-group">
                            <label>Tanggal :</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="tanggal" name="tanggal"
                                    autocomplete="off" />
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3 col-md-4">
                        <div class="form-group">
                            <label>Pembiayaan :</label>
                            <select name="pembiayaan" id="pembiayaan" class="custom-select form-control-border">
                                <option value="all">BPJS & Umum</option>
                                <option value="bpjs">BPJS</option>
                                <option value="umum">UMUM</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3 col-md-4">
                        <div class="form-group">
                            <label>Status SHK :</label>
                            <select name="shk" id="shk" class="custom-select form-control-border">
                                <option value="all">Semua</option>
                                <option value="sudah">Sudah</option>
                                <option value="belum">Belum</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive text-sm">
                            <table id="table-monitoring-shk" class="table table-bordered dataTable" width="100%"
                                cellspacing="0">
                                <thead>
                                    <tr role="row">
                                        <th>NAMA PASIEN</th>
                                        <th>DOKTER</th>
                                        <th>KONTROL</th>
                                        <th>SHK</th>
                                        <th>KETERANGAN SHK</th>
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
    const apiUrl = "{{ env('API_URL') }}";
    
    var table;
    var shk = null;
    var tgl_kedua = null;
    var tgl_pertama = null;
    var pembiayaan = null;

    $(document).ready(function() {
        $('#tanggal').daterangepicker({
            locale: {
                language: 'id',
                applyLabel: 'Terapkan',
                cancelLabel: 'Batal',
                format: 'DD/MM/YYYY',
                daysOfWeek: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Ming'],
                monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
                    'September', 'Oktober', 'November', 'Desember'
                ],
            },
            startDate: moment().startOf('month'),
            autoclose: true,
            showDropdowns: true,
            minYear: 2019,
            maxYear: {{ date('Y') + 1 }},
        });
        
        function loadData(tgl_pertama = null, tgl_kedua = null, shk = null, pembiayaan = null) {
            $('#table-monitoring-shk').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: apiUrl + 'monitor/resume/ranap?datatables=true',
                    data: {
                        shk: shk,
                        pembiayaan: pembiayaan,
                        tgl_registrasi: {
                            start: tgl_pertama,
                            end: tgl_kedua,
                        },
                    },
                    beforeSend: function (request) {
                        request.setRequestHeader("Authorization", "Bearer " + '{{ Session::get('token') }}');
                    },
                },
                columns: [
                    { 
                        // data: 'reg_periksa.pasien.nm_pasien', 
                        name: 'reg_periksa.pasien.nm_pasien',
                        render: function(data, type, row) {
                            const penjab = row.reg_periksa.penjab.png_jawab;
                            const pjb = penjab.includes('BPJS') ? 'BPJS' : "UMUM";
                            const pjbClass = penjab.includes('BPJS') ? 'badge badge-success' : "badge badge-warning";
                            console.log(row);

                            return `<div>
                                <b>${row.reg_periksa.pasien.nm_pasien}</b><br />
                                <span class="sr-only">-</span>${row.no_rawat}<br />
                                <span class="sr-only">-</span>${row.reg_periksa.pasien.no_tlp}<br />
                                <span class="sr-only">-</span>${row.reg_periksa.pasien.alamat}<br />
                                <span class="sr-only">-</span><span class="${pjbClass}">${pjb}</span>
                            </div>`;
                        }
                    },
                    { data: 'dokter.nm_dokter', name: 'dokter.nm_dokter' },
                    { data: 'kontrol', name: 'kontrol' },
                    { 
                        name: 'shk',
                        render: function(data, type, row) {
                            const shk = row.shk;
                            const shkClass = shk.toLowerCase() == 'sudah' ? 'badge badge-primary text-normal' : "badge badge-danger text-normal";

                            return `<span class="${shkClass}">${shk}</span>`;
                        }
                    },
                    { data: 'shk_keterangan', name: 'shk_keterangan' },
                ],
                columnDefs: [
                    { className: 'text-center', targets: [2, 3] },
                ],
                paging: true,
                searching: true,
                info: true,
                ordering: false,
                bLengthChange: false,
                bFilter: false,
                bInfo: false,
                bPaginate: false,
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
    
            console.log(tgl1);
            console.log(tgl2);

            $('#bulan').html('<strong>' + tgl1 + ' s/d ' + tgl2 + '</strong>');
            $('#table-monitoring-shk').DataTable().destroy();
    
            loadData(tgl_pertama, tgl_kedua, shk, pembiayaan);
        });
    
        $('#shk').change(function() {
            $('#table-monitoring-shk').DataTable().destroy();
    
            shk = $(this).val();
            loadData(tgl_pertama, tgl_kedua, shk, pembiayaan);
        });

        $('#pembiayaan').change(function() {
            $('#table-monitoring-shk').DataTable().destroy();
    
            pembiayaan = $(this).val();
            loadData(tgl_pertama, tgl_kedua, shk, pembiayaan);
        });

        loadData(tgl_pertama, tgl_kedua, shk, pembiayaan);
    });

</script>
@endpush