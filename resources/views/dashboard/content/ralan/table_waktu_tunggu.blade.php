@extends('dashboard.layouts.main')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-teal">
                <div class="card-header">
                    <p class="card-title border-bottom-0">{{ $title }} </p>
                    <div class="card-tools mr-4" id="bulan">
                        {{-- <span><strong>{{ $month }}</strong></span> --}}
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-12 col-sm-12">
                            <div class="form-group">
                                <label>Tanggal</label>
                                <div class="input-group mb-3">
                                    <input type="date" value="{{date('Y-m-d')}}" id="tgl_registrasi1"
                                           name="tgl_registrasi1"
                                           class="form-control"/>
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="tahun-addon">
                                            s.d.
                                        </span>
                                    </div>
                                    <input type="date" value="{{date('Y-m-d')}}" name="tgl_registrasi2"
                                           id="tgl_registrasi2"
                                           class="form-control"/>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-primary" id="btnFilterWaktuTunggu"><i
                                                    class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="sps">Spesialis</label>
                                <div class="input-group">
                                    <select class="form-control select2 w-100" id="sps" name="sps">

                                    </select>

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="dokter">Dokter</label>
                                <div class="input-group">
                                    <select class="form-control select2 w-100" id="dokter" name="dokter">

                                    </select>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-lg-12">
                            <div class="table-responsive text-sm">
                                <table class="table table-bordered table-striped" id="tableWaktuTungguRalan"
                                       style="width: 100%"
                                       cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th rowspan="2">No. Rawat</th>
                                        <th rowspan="2">Pasien</th>
                                        <th rowspan="2">Poliklinik</th>
                                        <th rowspan="2">Dokter</th>
                                        <th rowspan="2">Pembiayan</th>
                                        <th rowspan="2">Tgl. Periksa</th>
                                        <th colspan="3">Waktu Tunggu Poli</th>
                                        <th colspan="3">Waktu Layanan Poli</th>
                                        <th colspan="3">Waktu Tunggu Farmasi</th>
                                        <th colspan="3">Waktu Layanan Farmasi</th>
                                        <th rowspan="2">Total</th>
                                    </tr>
                                    <tr>
                                        <th>Mulai</th>
                                        <th>Selesai</th>
                                        <th>Waktu</th>
                                        <th>Mulai</th>
                                        <th>Selesai</th>
                                        <th>Waktu</th>
                                        <th>Mulai</th>
                                        <th>Selesai</th>
                                        <th>Waktu</th>
                                        <th>Mulai</th>
                                        <th>Selesai</th>
                                        <th>Waktu</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-sm-12 col-lg-12">
            <div class="card card-teal">
                <div class="card-header">
                    <span class="card-title">Rata-rata Waktu Tunggu</span>
                </div>
                <div class="card-body">
                    <div class="input-group mb-2 w-25">
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="fa fa-calendar"></i>
                            </span>
                        </div>
                        <input type="text" id="yearWaktuTunggu" class="form-control yearPicker w-25"
                               data-toggle="datetimepicker" aria-describedby="yearWaktuTunggu"
                               data-target="#yearWaktuTunggu" autocomplete="off" value="{{ date('Y') }}">
                    </div>
                    <table class="table table-striped" id="tableRataWaktuTunggu" style="width: 100%">

                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const tableWaktuTungguRalan = $('#tableWaktuTungguRalan')
        const tableRataWaktuTunggu = $('#tableRataWaktuTunggu')
        const tgl_registrasi1 = $('#tgl_registrasi1')
        const tgl_registrasi2 = $('#tgl_registrasi2')
        const btnFilterWaktuTunggu = $('#btnFilterWaktuTunggu')
        const yearWaktuTunggu = $('#yearWaktuTunggu')
        $(document).ready(function () {
            $('.datepicker').datetimepicker({
                format: "DD-MM-YYYY",
                useCurrent: true,
                locale: 'id',
            });


            $.ajax({
                url: '/dms/spesialis-poli',
                dataType: 'json',
                success: function (data) {
                    // Pastikan data berbentuk array
                    const spesialisOptions = [
                        {
                            id: '',
                            text: ''
                        }, ...data.map(item => ({
                            id: item.kd_sps,
                            text: item.nm_sps
                        }))
                    ]

                    // Inisialisasi Select2
                    $('#sps').select2({
                        placeholder: 'Pilih Kategori Poli',
                        allowClear: true,
                        data: spesialisOptions
                    });
                },
                error: function (xhr, status, error) {
                    console.error('Gagal memuat data spesialis:', error);
                }
            });


            $.ajax({
                url: '/dms/dokter-poliklinik',
                dataType: 'JSON',
                success: function (response) {
                    const dokter = [
                        {id: '', text: ''},
                        ...response.map(item => ({
                            id: item.kd_dokter,
                            text: item.nm_dokter
                        }))
                    ]

                    $('#dokter').select2({
                        placeholder: 'Dokter Spesialis',
                        allowClear: true,
                        data: dokter,
                    })
                }
            })

            // $('#dokter').select2({})
            // loadTableWaktuTugguRalan(tgl_registrasi1.val(), tgl_registrasi2.val())
            loadTableRataWaktuTunggu()
            renderTableWaktuTungguRalan()
        })

        btnFilterWaktuTunggu.on('click', function () {
            const tgl1 = tgl_registrasi1.val()
            const tgl2 = tgl_registrasi2.val()
            renderTableWaktuTungguRalan()
        })

        yearWaktuTunggu.on('change.datetimepicker', function () {
            const tahun = $(this).val();
            loadTableRataWaktuTunggu(tahun);
        })

        $('#sps').on('select2:select', function (evt) {
            const sps = evt.params.data.id;

            $.ajax({
                url: '/dms/dokter-spesialis',
                dataType: 'JSON',
                data: {
                    sps: sps,
                },
                success: function (response) {

                    renderTableWaktuTungguRalan()
                    const dokter = [
                        {id: '', text: ''},
                        ...response.map(item => ({
                            id: item.kd_dokter,
                            text: item.nm_dokter
                        }))
                    ]

                    $('#dokter').empty();

                    $('#dokter').select2({
                        placeholder: 'Dokter Spesialis',
                        allowClear: true,
                        data: dokter
                    })
                }
            })
        })

        $('#dokter').on('select2:select', function (evt) {
            renderTableWaktuTungguRalan()
        })

        $('#dokter').on('select2:unselect', function (evt) {
            renderTableWaktuTungguRalan()
        })

        $('#sps').on('select2:unselect', function (evt) {
            renderTableWaktuTungguRalan()
        })

        function renderTableWaktuTungguRalan() {
            const tgl_registrasi1 = $('#tgl_registrasi1').val()
            const tgl_registrasi2 = $('#tgl_registrasi2').val()
            const dokter = $('#dokter').val()
            const sps = $('#sps').val()

            const params = {
                tgl_registrasi1, tgl_registrasi2, dokter, sps
            }
            Object.keys(params).forEach(name => {
                if (!params[name]) delete params[name]
            })
            loadTableWaktuTugguRalan(params)
        }

        function loadTableWaktuTugguRalan(params = {}) {
            tableWaktuTungguRalan.DataTable({
                "scrollY": 500,
                "scrollX": true,
                "paging": true,
                "serverSide": true,
                "ajax": {
                    "url": `/dms/ralan/waktu-tunggu/get`,
                    data: params
                },
                "columns": [{
                    "data": "no_rawat"
                },
                    {
                        "data": "pasien.nm_pasien"
                    },
                    {
                        "data": "poliklinik.nm_poli"
                    },
                    {
                        "data": "dokter.nm_dokter"
                    },
                    {
                        "data": "penjab.png_jawab"
                    },
                    {
                        "data": "tgl_registrasi"
                    },
                    {
                        "render": (data, meta, row, params) => {
                            return data ? data.tunggu_poli : '-';
                        },
                        "data": "pemeriksaan_ralan"
                    },
                    {
                        "data": "estimasi",
                        "render": (data, meta, row, params) => {
                            return data ? data.jam_periksa : '-';
                        },
                    },
                    {
                        "data": "",
                        "render": (data, meta, row, params) => {
                            if (row.estimasi) {
                                const tgl1 = new Date(row.pemeriksaan_ralan.tunggu_poli);
                                const tgl2 = new Date(row.estimasi.jam_periksa);

                                return timeDiff(tgl1, tgl2);
                            }
                            return '';
                        },
                    },
                    {
                        "data": "estimasi",
                        "render": (data, meta, row, params) => {
                            return data ? data.jam_periksa : '-';
                        },
                    },
                    {
                        "data": "selesai",
                        "render": (data, meta, row, params) => {
                            return data ? data.jam_periksa : '-';
                        },
                    },
                    {
                        "data": "",
                        "render": (data, meta, row, params) => {
                            if (row.selesai) {
                                const tgl1 = new Date(row.estimasi.jam_periksa);
                                const tgl2 = new Date(row.selesai.jam_periksa);

                                return timeDiff(tgl1, tgl2);
                            }
                            return '';
                        },
                    },
                    {
                        "data": "resep_obat",
                        "render": (data, meta, row, params) => {
                            return data ? data.waktu_resep : '-';
                        },
                    },
                    {
                        "data": "resep_obat",
                        "render": (data, meta, row, params) => {
                            return data ? data.waktu_obat === '0000-00-00 00:00:00' ? '-' : data.waktu_obat : '-';
                        },
                    },
                    {
                        "data": "",
                        "render": (data, meta, row, params) => {
                            if (row.resep_obat) {
                                const tgl1 = row.resep_obat.waktu_resep;
                                const tgl2 = row.resep_obat.waktu_obat;

                                return timeDiff(tgl1, tgl2);
                            }
                            return ''
                        },
                    },
                    {
                        "data": "resep_obat",
                        "render": (data, meta, row, params) => {
                            return data ? data.waktu_obat : '-';
                        },
                    },
                    {
                        "data": "resep_obat",
                        "render": (data, meta, row, params) => {
                            return data ? data.selesai_obat : '-';
                        },
                    },
                    {
                        "data": "",
                        "render": (data, meta, row, params) => {
                            if (row.resep_obat) {
                                const tgl1 = row.resep_obat.waktu_obat
                                const tgl2 = row.resep_obat.selesai_obat

                                return timeDiff(tgl1, tgl2);
                            }
                            return ''
                        },
                    },
                    {
                        "data": "",
                        "render": (data, meta, row, params) => {
                            if (row.resep_obat && row.resep_obat.waktu_obat !== '0000-00-00 00:00:00' && row.resep_obat.selesai_obat !== '0000-00-00 00:00:00') {
                                const tgl1 = row.pemeriksaan_ralan.tunggu_poli;
                                const tgl2 = row.resep_obat.selesai_obat;

                                return timeDiff(tgl1, tgl2);
                            } else if (row.selesai) {
                                const tgl1 = row.pemeriksaan_ralan.tunggu_poli;
                                const tgl2 = row.selesai.jam_periksa;
                                return timeDiff(tgl1, tgl2);

                            }

                            return '-'
                        },
                    },

                ]
            });
        }


        function timeDiff(datetime1, datetime2) {
            const a = new Date(datetime1);
            const b = new Date(datetime2);
            if (isNaN(a) || isNaN(b)) {
                return `-`
            }

            const diff = b.getTime() - a.getTime();

            if (diff < 0) {
                return `<span class="text-red" title="Invalid datetime"> 00:00:00</span>`;
            }
            const days = Math.floor(diff / 86400000);
            const hours = Math.floor((diff - days * 86400000) / 3600000); // double digit
            const minutes = Math.floor((diff - days * 86400000 - hours * 3600000) / 60000);
            const seconds = Math.floor((diff - days * 86400000 - hours * 3600000 - minutes * 60000) / 1000);

            return `${hours.toString().length < 2 ? '0' + hours : hours}:${minutes.toString().length < 2 ? '0' + minutes : minutes}:${seconds.toString().length < 2 ? '0' + seconds : seconds}`;
        }

        function sumTime(times) {
            let totalSeconds = times.reduce((acc, time) => {
                let [hh, mm, ss] = time.split(":").map(Number);
                return acc + hh * 3600 + mm * 60 + ss;
            }, 0);

            let hours = Math.floor(totalSeconds / 3600);
            let minutes = Math.floor((totalSeconds % 3600) / 60);
            let seconds = totalSeconds % 60;

            return `${String(hours).padStart(2, "0")}:${String(minutes).padStart(2, "0")}:${String(seconds).padStart(2, "0")}`;
        }


        function loadTableRataWaktuTunggu(year = '') {
            tableRataWaktuTunggu.DataTable({
                "scrollY": 600,
                "scrollX": true,
                "paging": true,
                "serverSide": true,
                "searching": false,
                "lengthChange": false,
                "ajax": {
                    "url": `${url}/ralan/waktu-tunggu/tahun/${year}`,
                },
                "columns": [{
                    data: 'bulan',
                    title: 'Bulan'
                },
                    {
                        data: 'waktu_tunggu_poli',
                        title: 'Waktu Tunggu Poliklinik'
                    },
                    {
                        data: 'waktu_layanan_poli',
                        title: 'Waktu Layanan Poliklinik'
                    },
                    {
                        data: 'waktu_tunggu_obat',
                        title: 'Waktu Tunggu Farmasi'
                    },
                    {
                        data: 'waktu_layanan_obat',
                        title: 'Waktu Layanan Farmasi'
                    },
                    {
                        data: 'total',
                        render: (data, type, row, meta) => {
                            return sumTime([row.waktu_tunggu_poli, row.waktu_tunggu_obat, row.waktu_layanan_poli, row.waktu_layanan_obat]);
                        },
                        title: 'Total'
                    },
                ]
            })
        }
    </script>
@endpush
