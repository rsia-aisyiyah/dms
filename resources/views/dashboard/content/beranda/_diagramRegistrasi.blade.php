<div class="col-12 col-sm-12 col-md-12">
    <div class="card">
        <div class="card-header m-auto">
            <span><a href="#" style=" color:black!important">Cara Daftar Pasien</a> </span>
        </div>
        <div class="card-body">
            <label>Bulan</label>
            <div class="input-group mb-3">
                <div class="input-group-append">
                    <span class="input-group-text" id="tahun-addon"><i class="fas fa-calendar"></i></span>
                </div>
                <input type="text" id="date-registrasi" class="form-control datetimepicker-input"
                    data-toggle="datetimepicker" aria-describedby="tahun-addon" data-target="#date-registrasi"
                    autocomplete="off" />
            </div>
            <canvas id="diagramRegistrasi"
                style="min-height: 250px; height: 250px; max-height: 400px; max-width: 100%;"></canvas>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $('#date-registrasi').datetimepicker({
            format: "YYYY-MM",
            useCurrent: false,
        });

        $('#date-registrasi').on('change.datetimepicker', function() {
            var date = $(this).val().split('-');
            tahun = date[0];
            bulan = date[1];
            diagramRegistrasi.destroy();
            registrasi(tahun, bulan);

        });

        function registrasi(tahun = '', bulan = '') {
            diagramRegistrasi = document.getElementById("diagramRegistrasi");
            $.ajax({
                url: 'beranda/registrasi/',
                data: {
                    'tahun': tahun,
                    'bulan': bulan,
                },
                type: "GET",
                success: function(data) {

                    regLangsung = data.regLangsung;
                    regBooking = data.regBooking;
                    tanggal = data.tanggal;

                    var propLangsung = {
                        label: "Registrasi Langsung",
                        data: regLangsung,
                        backgroundColor: '#36A2EB',
                        beginAtZero: true,
                    };
                    var propBooking = {
                        label: "Registrasi Booking",
                        data: regBooking,
                        backgroundColor: '#FF6384',
                        beginAtZero: true,
                    };

                    var dataRegistrasi = {
                        labels: tanggal,
                        datasets: [propLangsung, propBooking],
                    };

                    var chartOptions = {
                        indexAxis: 'x',
                        responsive: true,
                        scales: {
                            x: {
                                beginAtZero: true,
                                grace: '5%',
                                max: 2000,
                                stacked: true
                            },
                            y: {

                                stacked: true
                            }

                        },
                        plugins: {
                            datalabels: {
                                color: 'white',
                                anchor: 'center',
                                align: 'center',
                                formatter: Math.round,
                                font: {
                                    size: 12,
                                }
                            }
                        }
                    };

                    diagramRegistrasi = new Chart(diagramRegistrasi, {
                        type: 'bar',
                        data: dataRegistrasi,
                        options: chartOptions
                    });
                }
            });
        }
    </script>
@endpush
