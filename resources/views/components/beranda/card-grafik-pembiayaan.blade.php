
@foreach($data as $key => $values)
    <div class="col-lg-3 col-md-12 col-sm-12">
        @php
            if($values['status'] === 'Ralan'){
                $cardColor = 'card-success';
                $cardTitle = 'Rawat Jalan';

            }else{
                $cardColor = 'card-warning';
                $cardTitle = 'Rawat Inap';
            }
        @endphp
        <x-card class="card card-outline {{$cardColor}}">
            <x-card.card-header>
                <div class="card-title">
                    <strong>{{$cardTitle}}</strong>
                </div>
            </x-card.card-header>
            <x-card.card-body>
                <canvas id="grafikPembiayaan{{ $values['status'] }}" style="max-height: 40vh"></canvas>
            </x-card.card-body>
        </x-card>
    </div>
@endforeach
@push('scripts')
    <script>
        let chartPembiayaanInstance = [];

        function renderPembiayaanPasien() {
            const dataPembiayaan = @json($data);
            dataPembiayaan.forEach((item, key) => {
                const ctx = document.getElementById(`grafikPembiayaan${item.status}`).getContext('2d');
                Chart.register(ChartDataLabels);
                chartPembiayaanInstance.push(new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: item.data.map(item => item.png_jawab),
                        datasets: [{
                            data: item.data.map(item => item.jumlah),
                            backgroundColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgb(9,203,143)',

                            ]
                        }]
                    }, options : {
                        plugins: {
                            datalabels: {
                                formatter: (value, ctx) => {
                                    let sum = 0;
                                    let dataArr = ctx.chart.data.datasets[0].data;
                                    dataArr.map(data => {
                                        sum += data;
                                    });
                                    let percentage = (value*100 / sum).toFixed(2)+"%";
                                    return `${value} \n (${percentage})`;
                                },
                                color: '#fff',
                            }
                        }
                    }
                }))
            })
        }

        $(document).ready(function () {
            renderPembiayaanPasien()
        })
    </script>
@endpush