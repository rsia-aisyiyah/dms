<div class="row">
    @foreach ($data as $key => $values)
        <div class="col-lg-6 col-md-12 col-sm-12">
            @php
                if ($values['status'] === 'Ralan') {
                    $cardColor = 'card-success';
                    $cardTitle = 'Rawat Jalan';
                } else {
                    $cardColor = 'card-warning';
                    $cardTitle = 'Rawat Inap';
                }
            @endphp
            <x-card class="card card-outline {{ $cardColor }}">
                <x-card.card-header>
                    <div class="card-title">
                        <strong>{{ $cardTitle }}</strong>
                    </div>
                </x-card.card-header>
                <x-card.card-body>
                    <canvas id="grafikPembiayaan{{ $values['status'] }}" style="height: 50vh; max-height: 50vh"></canvas>
                </x-card.card-body>

            </x-card>
        </div>
    @endforeach
</div>
@push('scripts')
    <script>
        let chartPembiayaanInstance = [];

        function renderPembiayaanPasien() {
            const dataPembiayaan = @json($data);

            if (dataPembiayaan.length) {
                dataPembiayaan.forEach((item, key) => {
                    const ctx = document.getElementById(`grafikPembiayaan${item.status}`).getContext('2d');
                    Chart.register(ChartDataLabels);
                    chartPembiayaanInstance.push(initGrafikPembiayaan(item, ctx));
                });
            }
        }

        function getPembiayaanPasien() {
            const valueInput = document.getElementById(`blnPembiayaan`).value;
            const [year, month] = valueInput.split('-');

            getDetailPembiayaanBpjs(year, month)

            $.get(`${url}/grafik/penjab/${year}/${month}`).done((response) => {
                response.forEach((item) => {
                    const existingChartIndex = chartPembiayaanInstance.findIndex(chart => chart.canvas.id === `grafikPembiayaan${item.status}`);

                    if (existingChartIndex !== -1) {
                        chartPembiayaanInstance[existingChartIndex].destroy();
                        chartPembiayaanInstance.splice(existingChartIndex, 1); // Remove the destroyed chart from the array
                    }

                    const ctx = document.getElementById(`grafikPembiayaan${item.status}`).getContext('2d');
                    Chart.register(ChartDataLabels);
                    chartPembiayaanInstance.push(initGrafikPembiayaan(item, ctx));
                });
            });
        }

        function initGrafikPembiayaan(item, ctx) {
            return new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: item.data.map(dataItem => dataItem.png_jawab),
                    datasets: [{
                        data: item.data.map(dataItem => dataItem.jumlah),
                        backgroundColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgb(9,203,143)',
                        ]
                    }]
                },
                options: {
                    plugins: {
                        datalabels: {
                            formatter: (value, ctx) => {
                                let sum = 0;
                                let dataArr = ctx.chart.data.datasets[0].data;
                                dataArr.forEach(data => {
                                    sum += data;
                                });
                                let percentage = (value * 100 / sum).toFixed(2) + "%";
                                return `${value} \n (${percentage})`;
                            },
                            color: '#fff',
                        }
                    }
                }
            });
        }

        $(document).ready(function() {
            renderPembiayaanPasien();
        });
    </script>
@endpush
