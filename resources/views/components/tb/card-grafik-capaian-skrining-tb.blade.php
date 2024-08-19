<div>
    <x-card class="card card-outline card-teal">
        <x-card.card-header>
            <div class="card-title">
                <strong>Capaian Skirining TB</strong>
            </div>
        </x-card.card-header>
        <x-card.card-body>
            <canvas id="grafikCapaianSkriningTb" style="max-height:40vh; width:80vw"></canvas>
        </x-card.card-body>
        <x-card.card-footer>
            <span><i class="text-danger text-sm">Jumlah Skrining/Total Kunjungan * 100%</i></span>
        </x-card.card-footer>
    </x-card>
</div>
@push('scripts')
    <script>
        const grafikCapaianSkriningTb = document.getElementById('grafikCapaianSkriningTb');

        function renderGrafikCapaianSkriningTb(capaian, label) {
            return new Chart(grafikCapaianSkriningTb, {
                type: 'pie',
                data: {
                    labels: label,
                    datasets: [{
                        data: capaian,
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
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        datalabels: {
                            formatter: function(value) {
                                return `${value}%`;
                            }
                        }
                    },
                }
            })
        }
    </script>
@endpush
