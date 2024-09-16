<div class="modal fade" id="modal-kamar">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Ubah Tarif Kamar</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="post">
                <div class="modal-body">
                    @csrf
                    <!-- <div class="row"> -->
                    <div id="form-group">
                        <label for="kd_bangsal">Kode Bangsal</label>
                        <input type="text" value="" id="kd_bangsal" name="kd_bangsal" class="form-control"
                            readonly>
                    </div>
                    <div id="form-group">
                        <label for="nm_kamar">Nama Kamar</label>
                        <input type="text" value="" id="nm_kamar" class="form-control" readonly>
                    </div>
                    <div id="form-group">
                        <label for="tarif">Tarif Kamar</label>
                        <input type="text" value="" id="tarif" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Status Kamar :</label>
                        <select name="status" id="u_status" class="custom-select form-control-border">
                            <option value="ISI">ISI</option>
                            <option value="KOSONG">KOSONG</option>
                            <option value="DIBERSIHKAN">DIBERSIHKAN</option>
                            <option value="DIBOOKING">DIBOOKING</option>
                        </select>
                    </div>
                    <!-- </div> -->

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" onclick="simpantarif()"
                        data-dismiss="modal">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        function simpantarif() {
            kd_bangsal = $('#kd_bangsal').val();
            tarif = $('#tarif').val();
            status = $('#u_status').val();

            $.ajax({
                url: 'kamar/simpantarif',
                type: 'post',
                data: {
                    _token: "{{ csrf_token() }}",
                    kd_bangsal: kd_bangsal,
                    tarif: tarif,
                    status: status,
                },
                success: function(data) {
                    toastr.info('Tarif kamar ' + kd_bangsal + ' berhasil diubah', 'Berhasil');
                    $('#tabel-tarif-kamar').DataTable().destroy();
                    load_data();
                }
            });
        }
    </script>
@endpush
