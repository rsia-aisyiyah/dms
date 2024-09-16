<div class="col-sm-12 col-md-4">
    <div class="card">
        <div class="card-header m-auto">
            <span>Cara Booking</span>
        </div>
        <div class="card-body pt-0">
            <canvas id="booking" style="max-height: 300px;"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script>
    var booking;
    function caraBooking(tgl_pertama = '', tgl_kedua = '') {
        var bookingChart = $('#booking').get(0);
        $.ajax({
            url: 'beranda/booking',
            type: 'GET',
            data: {
                'tgl_pertama': tgl_pertama,
                'tgl_kedua': tgl_kedua,
            },
            success: function (data) {

                offline = data.offline;
                online = data.online;

                var donutData = {
                    labels: [
                        'Offline',
                        'Online',
                    ],
                    datasets: [{
                        data: [offline, online],
                        backgroundColor: ['#36a2eb', '#00bc8c'],
                    }]
                }

                booking = new Chart(bookingChart, {
                    type: 'doughnut',
                    data: donutData,
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