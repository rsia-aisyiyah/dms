<div>
    <x-card class="card-outline card-outline card-teal">
        <x-card.header>
            <x-card.title><strong>Skrining Berdasarkan Poli</strong></x-card.title>
        </x-card.header>
        <x-card.body>
            <canvas id="grafikSkriningByPoli" style="max-height:40vh; width:80vw"></canvas>
        </x-card.body>
        <x-card.footer>
            <div class="input-group w-50">
                <div class="input-group-append">
                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                </div>
                <label for="blnSkriningTbPoli"></label>
                <input type="text" id="blnSkriningTbPoli" class="form-control monthPicker"
                       data-toggle="datetimepicker" aria-describedby="blnSkriningTbPoli"
                       data-target="#blnSkriningTbPoli"
                       autocomplete="off" />

            </div>
        </x-card.footer>
    </x-card>
</div>

@push('scripts')
    <script>
        const blnSkriningTbPoli = $('#blnSkriningTbPoli');
        const grafikSkriningByPoli = document.getElementById('grafikSkriningByPoli').getContext('2d');
        let chartSkriningByPoliInstance = '';
        $(document).ready(()=>{
            getGrafikSkriningByPoli();
        })

        const getGrafikSkriningByPoli = (year='', month='') => {
            $.get(`${url}/grafik/tb/skrining/poli/${year}/${month}`).done((response) => {
                const labels = Object.keys(response);
                const data = Object.values(response);

                console.log('data',data)

                const formatedLabel = labels.map((item)=>item.length > 9 ? item.substring(0, 4) + '...' : item)
                renderGrafikSkriningByPoli(data, formatedLabel)
                console.log(labels)
            });
        }

        blnSkriningTbPoli.on('change.datetimepicker', (e) => {
            const value = e.currentTarget.value;
            const splitValue = value.split('-');
            const tahun = splitValue[0];
            const bulan = splitValue[1];
            getGrafikSkriningByPoli(tahun, bulan)
        })

        const renderGrafikSkriningByPoli = (data, label) => {
            if (chartSkriningByPoliInstance) {
                chartSkriningByPoliInstance.destroy();
            }
            chartSkriningByPoliInstance = new Chart(grafikSkriningByPoli, {
                type : 'pie',
                data  : {
                    labels : label,
                    datasets : [{
                        data: data,
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
                        tooltip :{
                          callbacks: {
                              title : function (context){
                                  const label = context[0].label;
                                  let result = label;

                                  $.ajax({
                                      url: `${url}/poli/show/${label}`,
                                      type: 'GET',
                                      dataType: 'json',
                                      async: false,
                                      success: function(response) {
                                          result = response.nm_poli;
                                      },
                                      error: function(xhr, status, error) {
                                          console.error('Error fetching tooltip data:', error);
                                          result = label;
                                      }
                                  });

                                  return result;

                              }
                          }
                        },
                        datalabels: {
                            formatter: function(value, ctx) {
                                let sum = 0;
                                let dataArr = ctx.chart.data.datasets[0].data;
                                dataArr.forEach(data => {
                                    sum += data;
                                });
                                let percentage = (value * 100 / sum).toFixed(1) + "%";
                                return `${value} \n (${percentage})`;
                            }
                        }
                    },
                }
            })
        }


    </script>
@endpush