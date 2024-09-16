<div>
    <x-card class="card card-outline card-teal">
        <x-card.header>
            <div class="card-title">
                <strong>Demografi Pasien TB</strong>
            </div>
        </x-card.header>
        <x-card.body>
            <canvas id="grafikDemografiTb" style="max-height:40vh; width:80vw"></canvas>
        </x-card.body>
        <x-card.footer>
            <div class="input-group w-25">
                <div class="input-group-append">
                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                </div>
                <label for="bulanDemografiTbGrafik"></label>
                <input type="text" id="bulanDemografiTbGrafik" class="form-control monthPicker "
                       data-toggle="datetimepicker" aria-describedby="bulanDemografiTbGrafik"
                       data-target="#bulanDemografiTbGrafik"
                       autocomplete="off"/>
            </div>
        </x-card.footer>
    </x-card>
</div>

@push('scripts')
    <script>
        const grafikDemografiTb = $('#grafikDemografiTb')
        let grafikDemografiTbInstance = '';
        const bulanDemografiTbGrafik = $('#bulanDemografiTbGrafik');


        
        $(document).ready(function () {
            getGrafikDemografiKecamatanTb();
        })

        bulanDemografiTbGrafik.on('change.datetimepicker', (e) => {
            const value = e.currentTarget.value;
            const month = value.split('-')[1];
            const year = value.split('-')[0];
            getGrafikDemografiKecamatanTb(year, month)
        })
        

        function getGrafikDemografiKecamatanTb(year = '', month = '') {
            $.get(`${url}/grafik/tb/demografi/${year}/${month}`).done((response) => {
                const label = Object.keys(response).map((item) => item);
                let data = Object.values(response).map((item) => item);
                renderGrafikDemografiKecamatanTb(label, data)
            })
        }

        function renderGrafikDemografiKecamatanTb(label, data) {
            if (grafikDemografiTbInstance) {
                grafikDemografiTbInstance.destroy();
            }
            grafikDemografiTbInstance = new Chart(grafikDemografiTb, {
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
                    indexAxis :'y',
                    scales: {
                        // y: {
                        // },
                        x:{
                            beginAtZero: false,
                            grace: '5%',

                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        datalabels: {
                            anchor: 'end',
                            align: 'end',

                        }
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                }

            });
        }
    </script>
@endpush