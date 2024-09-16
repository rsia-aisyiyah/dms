<div>
    <x-card class="card card-outline card-teal">
        <x-card.header>
            <div class="card-title">
                <strong>Skrining TB</strong>
            </div>
        </x-card.header>
        <x-card.body>
            <canvas id="grafikSkriningTb" style="max-height:40vh; width:80vw"></canvas>
        </x-card.body>
        <x-card.footer>
            <div class="input-group w-25">
                <div class="input-group-append">
                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                </div>
                <label for="tahunSkriningTbGrafik"></label>
                <input type="text" id="tahunSkriningTbGrafik" class="form-control yearPicker "
                       data-toggle="datetimepicker" aria-describedby="tahunSkriningTbGrafik"
                       data-target="#tahunSkriningTbGrafik"
                       autocomplete="off"/>
            </div>
        </x-card.footer>
    </x-card>
</div>

@push('scripts')
    <script>
        const grafikSkriningTb = document.getElementById('grafikSkriningTb').getContext('2d');
        let grafikSkriningTbInstance = '';
        const tahunSkriningTbGrafik = $('#tahunSkriningTbGrafik')

        tahunSkriningTbGrafik.on('change.datetimepicker', (e) => {
            const tahun = e.currentTarget.value;
            getGrafikSkriningTb(tahun)
        });

        $(document).ready(() => {
            Chart.register(ChartDataLabels);
            getGrafikSkriningTb()
        })



        function renderGrafikSkriningTb(data, label) {
            if (grafikSkriningTbInstance) {
                grafikSkriningTbInstance.destroy();
            }
            grafikSkriningTbInstance = new Chart(grafikSkriningTb, {
                type: 'bar',
                data: {
                    labels: label,
                    datasets: [{
                        label: '',
                        data: data,
                        borderWidth: 1,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(255, 159, 64, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 205, 86, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(201, 203, 207, 0.2)'
                        ],
                        borderColor: [
                            'rgb(255, 99, 132)',
                            'rgb(255, 159, 64)',
                            'rgb(75, 192, 192)',
                            'rgb(54, 162, 235)',
                            'rgb(255, 205, 86)',
                            'rgb(153, 102, 255)',
                            'rgb(201, 203, 207)'
                        ],
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
                        legend: {
                            display: false
                        },
                        datalabels: {
                            anchor: 'end',
                            align: 'top',
                            color: 'grey',
                            formatter: function (value) {
                                return value;

                            }
                        }
                    },
                }
            })
        }
    </script>
@endpush
