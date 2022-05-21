<div class="col-12 col-sm-12 col-md-12">
    <div class="card">
        <div class="card-body">
            <label>Tahun</label>
            <div class="input-group mb-3">
                <div class="input-group-append">
                    <span class="input-group-text" id="tahun-addon"><i class="fas fa-calendar"></i></span>
                </div>
                <input type="text" id="tahun-ralan-poli" class="form-control datetimepicker-input"
                    data-toggle="datetimepicker" aria-describedby="tahun-addon" data-target="#tahun-ralan-poli"
                    autocomplete="off" />
            </div>
            <canvas id="diagramRalanPoli"
                style="min-height: 250px; height: 250px; max-height: 400px; max-width: 100%;"></canvas>
        </div>
        <div class="card-footer">
            <div class="col-md-6 col-sm-12">
                <span> <a href="/dms/ralan" style="color:black!important">Kunjungan Poliklinik Spesialis</a> </span>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>

    var anak = {!!json_encode($dataPoliAnak)!!};
    var obgyn = {!!json_encode($dataPoliObgyn)!!};
    var diagramRalan;
    var tahun;

    function loadDiagramRalan(anak, obgyn, tahun = '') {
        var diagramRalanPoli = document.getElementById("diagramRalanPoli");
        var propAnak = {
            label: "Anak",
            data: anak,
            backgroundColor: '#C70039',
            beginAtZero: true,
        };
        var propObgyn = {
            label: "Kandungan",
            data: obgyn,
            backgroundColor: '#0047AB',
            lineTension: 0.4,
            beginAtZero: true,
        };

        var dataDiagram = {
            labels: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"],
            datasets: [propAnak, propObgyn]
        };

        var chartOptions = {
            indexAxis: 'x',
            responsive: true,
            scales: {
                x: {
                    beginAtZero: true,
                    grace: '5%',
                    max: 2000
                }
            }
        };

        diagramRalan = new Chart(diagramRalanPoli, {
            type: 'bar',
            data: dataDiagram,
            options: chartOptions
        });
    }

    loadDiagramRalan(anak, obgyn, '');

    $('#tahun-ralan-poli').datetimepicker({
        format: "YYYY",
        useCurrent: false,
        viewMode: "years"
    });

    $('#tahun-ralan-poli').on('change.datetimepicker', function () {
        var tahun = $('#tahun-ralan-poli').val();
        $.ajax({
            url: 'ralan/diagram/poli/' + tahun,
            type: "GET",
            success: function (data) {
                anak = data.anak;
                obgyn = data.obgyn;
                diagramRalan.destroy();
                loadDiagramRalan(anak, obgyn, tahun);
            }
        });

    });

</script>
@endpush