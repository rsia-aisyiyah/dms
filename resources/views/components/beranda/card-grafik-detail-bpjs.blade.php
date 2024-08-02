<div>
    <x-card class="card card-outline card-indigo">
        <x-card.card-header>
            <div class="card-title">
                Detail Pembiayaan BPJS
            </div>
        </x-card.card-header>
        <x-card.card-body>
            <canvas id="grafikDetailBpjs" style="height: 50vh; max-height: 50vh"></canvas>
        </x-card.card-body>

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
                    backgroundColor: index === 0 ? 'rgba(75, 192, 192)' : 'rgba(153, 102, 255)',
                    borderColor: index === 0 ? 'rgba(75, 192, 192)' : 'rgba(153, 102, 255)',
                    borderWidth: 1
                };
            });

            return new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: formatedLabel,
                    datasets: datasets
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    responsive: true,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                title: function(context) {
                                    const truncatedLabel = context[0].label;
                                    const originalLabel = labelMap[truncatedLabel];
                                    return originalLabel;
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
