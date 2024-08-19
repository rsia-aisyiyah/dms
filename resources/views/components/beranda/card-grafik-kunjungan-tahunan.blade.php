<div class="row">
    <div class="col-lg-6 col-md-12 col-sm-12">
        <x-beranda.kunjungan-tahunan.card-grafik-ranap></x-beranda.kunjungan-tahunan.card-grafik-ranap>
    </div>
    <div class="col-lg-6 col-md-12 col-sm-12">
        <x-beranda.kunjungan-tahunan.card-grafik-ralan></x-beranda.kunjungan-tahunan.card-grafik-ralan>
    </div>
</div>

@push('scripts')
    <script>
        // const grafikTahunanRanap = document.getElementById('grafikKunjunganTahunanRanap');
        const overlayGrafikTahunan = $('.overlayGrafikTahunan');

        function toggleOverlayGrafikTahunan() {
            const isHasClass = overlayGrafikTahunan.hasClass('d-none')
            if (isHasClass) {
                overlayGrafikTahunan.removeClass('d-none')
            } else {
                overlayGrafikTahunan.addClass('d-none')
            }
        }

        $(document).ready((e) => {
            grafikKunjunganTahunan()
        })

        function grafikKunjunganTahunan(year = '') {
            $.get(`${url}/grafik/kunjungan/tahun/${year}`).done((response) => {
                toggleOverlayGrafikTahunan()
                for (const type in response) {
                    const result = response[type];
                    const chartData = renderGrafikKunjunganTahunan(result);
                    createChart(type, chartData);
                }
            });
        }

        function renderGrafikKunjunganTahunan(data) {
            const labels = [];
            const datasets = {};

            for (const month in data) {
                labels.push(month);
                data[month].forEach(entry => {
                    if (!datasets[entry.penjab]) {
                        datasets[entry.penjab] = [];
                    }
                    datasets[entry.penjab].push(entry.jumlah);
                });
            }

            // console.log(getRandomColor());
            const colorMap = {
                "BPJS KESEHATAN / PBI": 'rgba(54, 162, 235, 1)',
                "BPJS KESEHATAN / NON PBI": 'rgba(255, 99, 132, 1)',
                "UMUM": 'rgb(9,203,143)'
            };

            const datasetArray = Object.keys(datasets).map((penjab) => {

                return {
                    label: penjab,
                    data: datasets[penjab],
                    backgroundColor: colorMap[penjab],
                    borderWidth: 1
                }
            });

            return {
                labels,
                datasets: datasetArray
            };
        }

        function createChart(type, chartData) {
            const ctx = document.getElementById(`grafikKunjunganTahunan${type}`).getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartData.labels,
                    datasets: chartData.datasets
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            grace: "5%"
                        }
                    },
                    plugins: {
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
    </script>
@endpush
