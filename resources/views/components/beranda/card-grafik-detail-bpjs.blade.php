<div>
    <x-card class="card card-outline card-indigo">
        <x-card.header>
            <div class="card-title">
                <strong>Detail Pembiayaan BPJS</strong>
            </div>
        </x-card.header>
        <x-card.body>
            <canvas id="grafikDetailBpjs" style="max-height:80vh; width:80vw"></canvas>
        </x-card.body>
        <x-card.footer>
            <span class="text-red text-sm font-italic">*Berdasarkan SEP yang terbit</span>
        </x-card.footer>

    </x-card>
</div>

@push('scripts')
    <script>
        let chartDetailPembiayaanBpjs = null;

        function renderDetailPembiayaanBpjs(year, month) {
            const data = @json($data);
            const ctx = document.getElementById('grafikDetailBpjs').getContext('2d');
            chartDetailPembiayaanBpjs = initDetailPembiayaanBpjs(data, ctx);

        }

        function getDetailPembiayaanBpjs(year, month) {

            if (chartDetailPembiayaanBpjs) {
                chartDetailPembiayaanBpjs.destroy();
            }

            return $.get(`${url}/grafik/penjab/bpjs/${year}/${month}`).done((response) => {
                const ctx = document.getElementById('grafikDetailBpjs').getContext('2d');
                chartDetailPembiayaanBpjs = initDetailPembiayaanBpjs(response, ctx);
            })
        }

        function initDetailPembiayaanBpjs(data, ctx) {

            const truncateLabel = (label) => label.length > 9 ? label.substring(0, 9) + '...' : label;
            const formatedLabel = Object.keys(data[0].data).map(truncateLabel);

            const labels = Object.keys(data[0].data);
            const labelMap = formatedLabel.reduce((acc, truncated, index) => {
                acc[truncated] = labels[index];
                return acc;
            }, {});

            const datasets = data.map((item, index) => {

                const tseee = labels.map(label => item.data[label] || 0)
                return {
                    label: item.jnspelayanan,
                    data: labels.map(label => item.data[label] || 0),
                    backgroundColor: item.jnspelayanan === 'Rawat Inap' ? '#ffc107' : '#28a745',
                    borderColor: item.jnspelayanan === 'Rawat Inap'? '#ffc107' : '#28a745',
                    borderWidth: 1
                };
            });

            return new Chart(ctx, {
                type: 'bar',
                responsive : true,
                data: {
                    labels: formatedLabel,
                    datasets: datasets
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            grace : "5%"
                        }
                    },
                    responsive: true,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                title: function(context) {
                                    const truncatedLabel = context[0].label;
                                    return labelMap[truncatedLabel];
                                }
                            }
                        },
                        datalabels: {
                            anchor: 'end',
                            align: 'top',
                            color: 'grey',
                            formatter: function(value) {
                                return value;
                            }
                        }
                    }
                }
            });
        }

        $(document).ready(() => {
            renderDetailPembiayaanBpjs();
        })
    </script>
@endpush
