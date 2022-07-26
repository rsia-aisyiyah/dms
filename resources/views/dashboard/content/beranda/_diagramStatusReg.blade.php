<div class="col-sm-12 col-md-4">
    <div class="card">
        <div class="card-header m-auto">
            <span>Status Periksa</span>
        </div>
        <div class="card-body pt-0">
            <canvas id="stts" style="max-height: 300px;"></canvas>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        var statusPeriksa;

        function statusReg(tgl_pertama = '', tgl_kedua = '') {
            var sttsReg = $('#stts').get(0);
            $.ajax({
                url: 'beranda/periksa',
                type: 'GET',
                data: {
                    'tgl_pertama': tgl_pertama,
                    'tgl_kedua': tgl_kedua,
                },
                success: function(data) {
                    console.log(data);
                    sudah = data.sudah;
                    batal = data.batal;

                    var dataStts = {
                        labels: [
                            'Sudah',
                            'Batal',
                        ],
                        datasets: [{
                            data: [sudah, batal],
                            backgroundColor: ['#20c997', '#e83e8c'],
                        }]
                    }

                    statusPeriksa = new Chart(sttsReg, {
                        type: 'doughnut',
                        data: dataStts,
                        options: {
                            plugins: {
                                datalabels: {
                                    color: 'white',
                                    anchor: 'center',
                                    align: 'center',
                                    formatter: Math.round,
                                    font: {
                                        size: 11
                                    }
                                }
                            }
                        }
                    });
                }
            });

        }
    </script>
@endpush
