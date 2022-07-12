<div class="col-12 col-sm-12 col-md-6">
    <div class="card">
        <div class="card-header m-auto">
            <span>Pembiayaan Pasien Ranap </span>
        </div>
        <div class="card-body pt-0">
            <canvas id="diagramPembiayaanRanap" style="max-height: 400px;"></canvas>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        var diagramBayar;

        function pembiayaanPasienRanap(tgl_pertama = '', tgl_kedua = '') {
            var diagramPembiayaanRanap = document.getElementById("diagramPembiayaanRanap");
            $.ajax({
                url: 'beranda/pembiayaan/ranap',
                type: 'GET',
                data: {
                    'tgl_pertama': tgl_pertama,
                    'tgl_kedua': tgl_kedua,
                },
                success: function(data) {
                    var dataPembiayaan = {
                        labels: [""],
                        datasets: [{
                                label: "BPJS Mandiri",
                                backgroundColor: "#007bff",
                                borderWidth: 1,
                                data: [data.mandiri],
                            },
                            {
                                label: "BPJS PBI",
                                backgroundColor: "#ffc107",
                                borderWidth: 1,
                                data: [data.pbi],
                            },
                            {
                                label: "Umum",
                                backgroundColor: '#28a745',
                                borderWidth: 1,
                                data: [data.umum],
                            }
                        ]
                    };
                    diagramBayarRanap = new Chart(diagramPembiayaanRanap, {
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
                            }

                        }
                    });
                }
            });

        }
    </script>
@endpush
