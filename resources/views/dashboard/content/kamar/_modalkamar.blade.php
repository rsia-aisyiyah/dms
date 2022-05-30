<div class="modal fade" id="modal-default">
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
                    <div id="form-group">
                        <label for="kd_bangsal">Kode Bangsal</label>
                        <input type="text" value="" id="kd_bangsal" name="kd_bangsal" class="form-control" readonly>
                    </div>
                    <div id="form-group">
                        <label for="nm_kamar">Nama Kamar</label>
                        <input type="text" value="" id="nm_kamar" class="form-control" readonly>
                    </div>
                    <div id="form-group">
                        <label for="tarif">Tarif Kamar</label>
                        <input type="text" value="" id="tarif" class="form-control">
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" onclick="simpantarif()" data-dismiss="modal">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>