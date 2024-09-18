<div class="col-lg-6 col-md-12 col-sm-12">
    <x-card class="card card-outline">
        <x-card.header>
            <div class="card-title">
                <strong>Demografi Kecamatan Rawat Inap</strong>
            </div>
        </x-card.header>
        <x-card.body>
            <canvas class="canvasDemografiRanap" id="grafikDemografiKecamatanRanap"
                    style="height: 50vh; max-height: 50vh"></canvas>
        </x-card.body>
        <x-card.footer>
            <div class="row">
                <div class="col-6">
                    <div class="input-group w-100">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                        </div>
                        <input type="text" id="blnDemografiKecamatanRanapGrafik" class="form-control monthPicker"
                               data-toggle="datetimepicker" aria-describedby="blnDemografiKecamatanRanapGrafik"
                               data-target="#blnDemografiKecamatanRanapGrafik"
                               autocomplete="off"/>
                    </div>
                </div>
            </div>
        </x-card.footer>
    </x-card>
</div>

@push('scripts')

    <script>
        const grafikDemografiKecamatanRanap = document.getElementById('grafikDemografiKecamatanRanap').getContext('2d');
        let grafikDemografiKecamatanRanapInstance = '';
        const blnDemografiKecamatanRanapGrafik = $('#blnDemografiKecamatanRanapGrafik')

        $(document).ready(() => {
            getDemografiKecamatanRanap();
        });

        function getDemografiKecamatanRanap($year = '', $month = '') {
            $.get(`${url}/beranda/demografi/ranap/kecamatan/${$year}/${$month}`).done((response) => {
                const label = Object.keys(response).map((item) => item);
                let data = Object.values(response).map((item) => item);
                renderGrafikDemografiKecamatanRanap(label, data)

            })
        }

        function renderGrafikDemografiKecamatanRanap(label, data) {
            if (grafikDemografiKecamatanRanapInstance) {
                grafikDemografiKecamatanRanapInstance.destroy();

            }

            grafikDemografiKecamatanRanapInstance = new Chart(grafikDemografiKecamatanRanap, {
                type: 'bar',
                data: {
                    labels: label.map((item) => item.substring(0, 3)),
                    datasets: [{
                        label: '',
                        data: data,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(255, 159, 64, 0.2)',
                            'rgba(255, 205, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(201, 203, 207, 0.2)'
                        ],
                        borderColor: [
                            'rgb(255, 99, 132)',
                            'rgb(255, 159, 64)',
                            'rgb(255, 205, 86)',
                            'rgb(75, 192, 192)',
                            'rgb(54, 162, 235)',
                            'rgb(153, 102, 255)',
                            'rgb(201, 203, 207)'
                        ],
                        borderWidth: 1
                    }],

                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                title: function (context) {
                                    const index = context[0].dataIndex;
                                    return label[index];
                                }
                            }
                        }
                    }
                }
            });
        }

        blnDemografiKecamatanRanapGrafik.on('change.datetimepicker', function (e) {
            const year = e.date.year();
            const month = e.date.month() + 1;
            getDemografiKecamatanRanap(year, month);
        })


    </script>
@endpush