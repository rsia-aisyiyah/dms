<div class="col-12 col-sm-12 col-md-12">
    <div class="card">
        <div class="card-header m-auto">
            <span>Pembiayaan Pasien</span>
        </div>
        <div class="card-body pt-0">
            <canvas id="diagramPembiayaan" style="max-height: 300px;"></canvas>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        var diagramBayar;

        function pembiayaanPasien(tgl_pertama = '', tgl_kedua = '') {
            Chart.register(ChartDataLabels);
            var diagramPembiayaan = document.getElementById("diagramPembiayaan");
            $.ajax({
                url: 'beranda/pembiayaan',
                type: 'GET',
                data: {
                    'tgl_pertama': tgl_pertama,
                    'tgl_kedua': tgl_kedua,
                },
                success: function(data) {
                    var dataPembiayaan = {
                        labels: ["Rawat Jalan", "Rawat Inap"],
                        datasets: [{
                                label: "BPJS Mandiri",
                                backgroundColor: "#007bff",
                                borderWidth: 1,
                                data: [data.ralan.mandiri, data.ranap.mandiri],
                            },
                            {
                                label: "BPJS PBI",
                                backgroundColor: '#28a745',
                                borderWidth: 1,
                                data: [data.ralan.pbi, data.ranap.pbi],
                            },
                            {
                                label: "Umum",
                                backgroundColor: "#fd7e14",
                                borderWidth: 1,
                                data: [data.ralan.umum, data.ranap.umum],
                            }
                        ]
                    };
                    diagramBayar = new Chart(diagramPembiayaan, {
                        type: 'bar',
                        data: dataPembiayaan,
                        options: {
                            indexAxis: 'y',
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grace: '5%',
                                    max: 2000,
                                }
                            },
                            plugins: {
                                datalabels: {
                                    color: 'white',
                                    anchor: 'center',
                                    align: 'center',
                                    formatter: Math.round,
                                    font: {
                                        size: 11
                                    }
                                }
                            }

                        }
                    });
                }
            });

        }
    </script>
@endpush
