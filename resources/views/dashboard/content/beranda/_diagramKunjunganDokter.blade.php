<div class="col-12 col-sm-12 col-md-12">
    <div class="card">
        <div class="card-body">
            <label>Bulan</label>
            <div class="input-group mb-3">
                <div class="input-group-append">
                    <span class="input-group-text" id="tahun-addon"><i class="fas fa-calendar"></i></span>
                </div>
                <input type="text" id="date-kunjungan" class="form-control datetimepicker-input"
                    data-toggle="datetimepicker" aria-describedby="tahun-addon" data-target="#date-kunjungan"
                    autocomplete="off" />
            </div>
            <canvas id="diagramKunjunganDokter"
                style="min-height: 250px; height: 250px; max-height: 400px; max-width: 100%;"></canvas>
        </div>
        <div class="card-footer">
            <div class="col-md-6 col-sm-12">
                <span><a href="/dms/ralan" style="color:black!important">Kunjungan Poliklinik Dokter</a> </span>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>

    var dokter1 = {!!json_encode($dokter1)!!};
    var dokter2 = {!!json_encode($dokter2)!!};
    var dokter3 = {!!json_encode($dokter3)!!};
    var dokter4 = {!!json_encode($dokter4)!!};
    var labelHari = {!!json_encode($labelHari)!!}
    var diagramKunjunganDokter;
    var tahun;
    var bulan;

    function loadDiagramKunjunganDokter(dokter1, dokter2, dokter3, dokter4,tahun = '', bulan ='') {
        diagramKunjunganDokter = document.getElementById("diagramKunjunganDokter");
        var propDr1 = {
            label: "dr. Himawan Budityastomo, SpOG",
            data: dokter1,
            backgroundColor: 'tomato',
            beginAtZero: true,
        };
        var propDr2 = {
            label: "dr. Dwi Riyanto, SpA",
            data: dokter2,
            backgroundColor: 'orange',
            beginAtZero: true,
        };
        var propDr3 = {
            label: "dr. Siti Pattihatun Nasyiroh, SpOG",
            data: dokter3,
            backgroundColor: 'pink',
            beginAtZero: true,
        };
        var propDr4 = {
            label: "dr. Rendy Yoga Ardian, Sp.A",
            data: dokter4,
            backgroundColor: 'indianred',
            beginAtZero: true,
        };

        var dataDiagram = {
            labels: labelHari,
            datasets: [propDr1, propDr2, propDr3, propDr4]
        };

        var chartOptions = {
            indexAxis: 'x',
            responsive: true,
            scales: {
                x: {
                    beginAtZero: true,
                    grace: '5%',
                    max: 2000, 
                    stacked: true,
                },
                y:{
                    stacked:true,
                }
                
            }
        };

        diagramKunjunganDokter = new Chart(diagramKunjunganDokter, {
            type: 'bar',
            data: dataDiagram,
            options: chartOptions
        });
    }

    loadDiagramKunjunganDokter(dokter1, dokter2, dokter3, dokter4, '', '');

    $('#date-kunjungan').datetimepicker({
        format: "YYYY-MM",
        useCurrent: false,
        // viewMode: "years"
    });

    $('#date-kunjungan').on('change.datetimepicker', function () {
        var date = $(this).val().split('-');
        tahun = date[0]; 
        bulan = date[1]; 
        $.ajax({
            url: 'beranda/dokter/' + tahun + '/'+bulan,
            type: "GET",
            success: function (data) {
                dokter1 = data.dokter1;
                dokter2 = data.dokter2;
                dokter3 = data.dokter3;
                dokter4 = data.dokter4;
                // dokter5 = data.dokter5;
                labelHari = data.bulan;
                diagramKunjunganDokter.destroy();
                loadDiagramKunjunganDokter(dokter1, dokter2, dokter3, dokter4, tahun, bulan);
            }
        });

    });

</script>
@endpush