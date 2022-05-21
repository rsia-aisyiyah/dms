<div class="col-12 col-sm-12 col-md-8">
    <div class="card">
      <div class="card-body">
        <canvas id="diagramPembiayaan" style="height:332px; min-height:230px"></canvas>
      </div>
      <div class="card-footer">
        <span> Kunjungan Berdasarkan Cara Pembayaran </span>
      </div>
    </div>
</div>

@push('scripts')
    <script>
      var diagramBayar;
        var diagramPembiayaan = document.getElementById("diagramPembiayaan");
        
        var dataPembiayaan = {
          labels: [""],
          datasets: [{
                        label: "BPJS",
                        backgroundColor: "#007bff",
                        borderWidth: 1,
                        data: [{{$jumlBPJS}}]
                      },
                      {
                        label: "Umum",
                        backgroundColor: '#28a745',
                        borderWidth: 1,
                        data: [{{$jumlUmum}}]
                      }
                    ],
        };


        diagramBayar = new Chart(diagramPembiayaan, {
            type: 'bar',
            data: dataPembiayaan,
            options: {
              indexAxis: 'y', 
              responsive : true,
            }
        });
    </script>
@endpush