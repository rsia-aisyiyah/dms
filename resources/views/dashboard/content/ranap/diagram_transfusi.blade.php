<div class="row">
    <div class="col-sm-12 col-md-4">
        <div class="card card-teal">
            <div class="card-header">
                <p class="card-title border-bottom-0">Rekap Transfusi Pasien</p>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-6">
                                <label>Tahun</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="tahun-addon"><i class="fas fa-calendar"></i></span>
                                    </div>
                                    <input type="text" id="yearpicker" class="form-control datetimepicker-input" data-toggle="datetimepicker" aria-describedby="tahun-addon" data-target="#yearpicker" autocomplete="off" />
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive text-sm">
                            <table class="table table-striped"  id="tabel-rekap-transfusi" style="width: 100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Bulan</th>
                                        <th>Jumlah Kantong</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>

</script>

@endpush