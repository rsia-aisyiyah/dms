<div>
    <x-card class="card card-outline card-teal">
        <x-card.card-header>
            <div class="card-title">
                <strong>Skirining TB</strong>
            </div>
        </x-card.card-header>
        <x-card.card-body>
            <canvas id="grafikSkriningTb" style="max-height:40vh; width:80vw"></canvas>
        </x-card.card-body>
    </x-card>
</div>

@push('scripts')
    <script>
        const grafikSkriningTb = document.getElementById('grafikSkriningTb').getContext('2d');
        $(document).ready(() => {
            Chart.register(ChartDataLabels);
            getGrafikSkriningTb()
        })

        function getGrafikSkriningTb(year = '') {
            return $.get(`${url}/grafik/tb/skrining/${year}`).done((response) => {
                renderGrafikSkriningTb(response.data, response.label)
                renderGrafikCapaianSkriningTb(response.capaian, response.label)
            })
        }

        function renderGrafikSkriningTb(data, label) {

            return new Chart(grafikSkriningTb, {
                type: 'bar',
                data: {
                    labels: label,
                    datasets: [{
                        label: '',
                        data: data,
                        borderWidth: 1,
                        borderColor: '#36A2EB',
                        backgroundColor: '#9BD0F5',
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: false,
                            grace: '5%',
                        },
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        datalabels: {
                            anchor: 'end',
                            align: 'top',
                            color: 'grey',
                            formatter: function(value) {
                                return value;

                            }
                        }
                    },
                }
            })
        }
    </script>
@endpush
