@extends('dashboard.layouts.main')

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-body">
        <div class="form-group">
          <div class="row">
            <label>Filter Data </label>
            <div class="input-group">
                <input type="text" class="form-control" id="tanggal" name="tanggal" autocomplete="off"/>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box">
      <span class="info-box-icon bg-blue elevation-1"><i class="fas fa-hospital-user"></i></span>
      <div class="info-box-content">
        <p class="info-box-text mb-0">Total Kunjungan</p>
        <h3 class="info-box-number mt-0 mb-0 p-0">
          {{$total}}
        </h3>
        <small> Pasien</small>
      </div>
    </div>
  </div>
  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box">
      <span class="info-box-icon bg-success elevation-1"><i class="fas fa-hospital-user"></i></span>

      <div class="info-box-content">
        <p class="info-box-text mb-0">Rawat Jalan</p>
        <h3 class="info-box-number mt-0 mb-0 p-0">
          {{$jumlRalan}}
        </h3>
        <small> Pasien</small>
      </div>
    </div>
  </div>
  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box">
      <span class="info-box-icon bg-yellow elevation-1"><i class="fas fa-hospital-user"></i></span>
      <div class="info-box-content">
        <p class="info-box-text mb-0">Rawat Inap</p>
        <h3 class="info-box-number mt-0 mb-0 p-0">
          {{$jumlRanap}}
        </h3>
        <small> Pasien</small>
      </div>
    </div>
  </div>
  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box">
      <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-hospital-user"></i></span>

      <div class="info-box-content">
        <p class="info-box-text mb-0">IGD</p>
        <h3 class="info-box-number mt-0 mb-0 p-0">
          {{$jumlIGD}}
        </h3>
        <small> Pasien</small>
      </div>
    </div>
  </div>
  
</div>
<div class="row">
  @include('dashboard.content.beranda._diagramPembayaran')
  <div class="col-sm-12 col-md-4">
    <div class="card">
      <div class="card-body">
        <canvas id="statusDaftar" style="height:200px; min-height:150px"></canvas>
      </div>
      <div class="card-footer">
        <span>Kunjungan Berdasarkan Status Daftar</span>
      </div>
    </div>
  </div>
</div>
<div class="row">
  @include('dashboard.content.beranda._diagramKunjunganDokter')
</div>
<div class="row">
  @include('dashboard.content.beranda._diagramRalan')
</div>
@endsection

@push('scripts')
<script>
    // pie chart status pasien
    var donutChartCanvas = $('#statusDaftar').get(0);
      var donutData        = {
                                labels: [
                                    'Baru', 
                                    'Lama',
                                ],
                                datasets: [
                                  {
                                    data: [{{$baru}},{{$lama}}],
                                    backgroundColor : ['orange', 'tomato'],
                                  }
                                ]
                              }
                              
    var donutChart = new Chart(donutChartCanvas, {
      type: 'doughnut',
      data: donutData, 
    });  
</script>
@endpush