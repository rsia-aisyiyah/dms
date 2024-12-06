<div>
    <x-card class="card card-outline card-teal">
        <x-card.header>
            <div class="card-title">
                <strong>Data Skrining TB</strong>
            </div>
        </x-card.header>
        <x-card.body>
            <div class="table-responsive text-sm">
                <table class="table table-bordered table-striped table-hover table-sm" id="tableSkriningTb"
                    style="width: 100%">

                </table>
            </div>
        </x-card.body>
        <x-card.footer>
            <div class="row">
                <div class="col-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="radioBlnSkriningTb" id="radioBlnSkriningTb1"
                            checked="">
                        <label class="form-check-label" for="radioBlnSkriningTb1">Data Bulanan</label>
                    </div>
                    <div class="input-group">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                        </div>
                        <input type="text" id="blnSkriningTb" class="form-control monthPicker"
                            data-toggle="datetimepicker" aria-describedby="blnSkriningTb"
                            data-target="#blnSkriningTb"
                            autocomplete="off" />

                    </div>
                </div>
                <div class="col-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="radioBlnSkriningTb" id="radioBlnSkriningTb2">
                        <label class="form-check-label" for="radioBlnSkriningTb2">Data Tahunan</label>
                    </div>
                    <div class="input-group">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                        </div>
                        <input type="text" id="tahunSkriningTb" class="form-control yearPicker"
                            data-toggle="datetimepicker" aria-describedby="tahunSkriningTb"
                            data-target="#tahunSkriningTb"
                            autocomplete="off" />

                    </div>
                </div>
            </div>
        </x-card.footer>
    </x-card>
</div>

@push('scripts')
    <script>
        const tahunSkriningTb = $('#tahunSkriningTb');
        const blnSkriningTb = $('#blnSkriningTb');
        const radioBlnSkriningTb = $('input[name="radioBlnSkriningTb"]');

        tahunSkriningTb.on('change.datetimepicker', (e) => {
            const tahun = e.currentTarget.value;
            loadTableSkriningTb(tahun)
        })
        blnSkriningTb.on('change.datetimepicker', (e) => {
            const value = e.currentTarget.value;
            const splitValue = value.split('-');
            const tahun = splitValue[0];
            const bulan = splitValue[1];
            loadTableSkriningTb(tahun, bulan)
        })

        $(document).ready(() => {
            loadTableSkriningTb()
            radioBlnSkriningTb.trigger('change')
        })

        radioBlnSkriningTb.change(function() {
            if ($('#radioBlnSkriningTb1').is(':checked')) {
                $('#blnSkriningTb').prop('disabled', false); // Enable monthly input
                $('#tahunSkriningTb').prop('disabled', true); // Disable yearly input
            } else if ($('#radioBlnSkriningTb2').is(':checked')) {
                $('#blnSkriningTb').prop('disabled', true); // Disable monthly input
                $('#tahunSkriningTb').prop('disabled', false); // Enable yearly input
            }
        });

        function loadTableSkriningTb(year = '', month = '') {
            $('#tableSkriningTb').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                scrollY: '50vh',
                scrollX: true,
                deferRender: true,
                ordering: false,
                dom: '<"top"lBf>rt<"bottom"ip><"clear">',
                language: {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i> <span class="sr-only">Loading...</span>',
                    zeroRecords: "Tidak Ditemukan Data",
                    infoEmpty: "",
                    info: "Menampilkan sebanyak _START_ ke _END_ dari _TOTAL_ data",
                    loadingRecords: "Sedang memuat ...",
                    infoFiltered: "(Disaring dari _MAX_ total baris)",
                    lengthMenu: "Tampilkan _MENU_ baris",
                    buttons: {
                        copyTitle: 'Data telah disalin',
                        copySuccess: {
                            _: '%d baris data telah disalin',
                        },
                    },
                    // lengthMenu: '<div class="text-md mt-3">Tampilkan <select>' +
                    //     '<option value="50" selected>50</option>' +
                    //     '<option value="100">100</option>' +
                    //     '<option value="500">500</option>' +
                    //     '<option value="-1">Semua</option>' +
                    //     '</select> Baris',
                    paginate: {
                        "first": "Awal",
                        "last": "Akhir",
                        "next": ">",
                        "previous": "<"
                    },
                    search: 'Cari Penyakit : ',
                },
                lengthMenu: [
                    [50, 100, 200, 250, 500, -1],
                    ['50', '100', '200', '250', '500', 'Semua']
                ],
                ajax: {
                    url: `${url}/datatable/tb/skrining/${year}/${month}`,
                    type: 'GET',
                },
                buttons: [{
                        extend: 'copy',
                        text: '<i class="fas fa-copy"></i> Salin',
                        className: 'btn btn-info mb-2',
                        title: 'tabel-skrining-tb-{{ date('dmy') }}'
                    },
                    {
                        extend: 'csv',
                        text: '<i class="fas fa-file-csv"></i> CSV',
                        className: 'btn btn-info mb-2',
                        title: 'tabel-skrining-tb-{{ date('dmy') }}'
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'btn btn-info mb-2',
                        title: 'tabel-skrining-tb-{{ date('dmy') }}'
                    },
                ],
                columns: [{
                        title: 'Tgl. Skrining',
                        name: 'tanggal',
                        data: 'tanggal',
                    },
                    {
                        title: 'No. Rawat',
                        data: 'no_rawat',
                        name: 'no_rawat',
                    }, {
                        title: 'Pasien',
                        data: 'pasien.nm_pasien',
                        name: 'pasien.nm_pasien',
                    },
                    {
                        title: 'Tgl. Lahir',
                        name: 'tgl_pasien',
                        data: 'pasien.tgl_lahir'
                    },
                    {
                        title: 'Umur',
                        name: 'pasien',
                        data: function(data, type, row) {
                            const {
                                reg_periksa
                            } = data;
                            return `${reg_periksa.umurdaftar} ${reg_periksa.sttsumur}`
                        },
                    },
                    {
                        title: 'Alamat',
                        name: 'pasien',
                        data: function(data, type, row) {
                            const {
                                kecamatan,
                                kelurahan,
                                kabupaten
                            } = data.pasien

                            return `${kelurahan.nm_kel !== '-' ? `${kelurahan.nm_kel},` : ''} ${kecamatan.nm_kec}, ${kabupaten.nm_kab}`
                        },
                    },
                    {
                        title: 'Poliklinik',
                        name: 'poliklinik.nm_poli',
                        data: 'poliklinik.nm_poli'
                    },
                    {
                        title: 'Lanjut',
                        name: 'reg_periksa.status_lanjut',
                        data: (data, type, row) => {
                            const status = data.reg_periksa.status_lanjut;
                            const labelStatus = status === 'Ranap' ? 'text-danger' : 'text-success';
                            return `<span class="badge ${labelStatus}">${status}</span>`
                        }
                    },
                    {
                        title: 'Petugas',
                        name: 'pegawai',
                        data: 'pegawai.nama'
                    },
                ]

            })
        }
    </script>
@endpush
