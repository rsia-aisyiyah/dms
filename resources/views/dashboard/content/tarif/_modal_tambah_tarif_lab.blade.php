<div class="modal fade text-sm" id="modal-tambah">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah Tarif Layanan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="get">
                <div class="modal-body modal-bottom">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                            <div id="form-group">
                                <label for="kd_jenis_prw">Kode Tindakan</label>
                                <input type="text" value="" id="kd_jenis_prw" name="kd_jenis_prw"
                                    class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-sm-12 mb-3">
                            <div id="form-group">
                                <label for="nm_perawatan">Nama Tindakan / Perawatan</label>
                                <input type="text" value="" id="nm_perawatan" class="form-control">
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Kelas :</label>
                                <select name="kelas" id="kelas" class="custom-select form-control-border">
                                    <option value="-">-</option>
                                    <option value="Kelas 1">Kelas 1</option>
                                    <option value="Kelas 2">Kelas 2</option>
                                    <option value="Kelas 3">Kelas 3</option>
                                    <option value="Kelas VIP">Kelas VIP</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Kategori :</label>
                                <select name="kategori" id="kategori" class="custom-select form-control-border">
                                    {{-- <option value="" hidden>-</option> --}}
                                    <option value="PK" selected>PK</option>
                                    <option value="PR">PR</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="pembiayaan">Pembiayaan</label>
                                <select class="custom-select form-control-border" id="pembiayaan" name="pembiayaan">
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div id="form-group">
                                <label for="bagian_rs">Jasa Rumah Sakit</label>
                                <input type="text" value="" name="bagian_rs" id="bagian_rs"
                                    class="form-control hitung" onkeypress="return hanyaAngka(event)"
                                    onblur="cekKosong(this);jumlahTarif()" onfocus="removeZero(this)"
                                    autocomplete="off">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div id="form-group">
                                <label for="tarif_perujuk">Jasa Perujuk</label>
                                <input type="text" value="" name="tarif_perujuk" id="tarif_perujuk"
                                    class="form-control hitung" onkeypress="return hanyaAngka(event)"
                                    onblur="cekKosong(this);jumlahTarif()" onfocus="removeZero(this)"
                                    autocomplete="off">
                            </div>
                        </div>
                        <div class=" col-sm-6">
                            <div id="form-group">
                                <label for="tarif_dokter">Jasa Dokter</label>
                                <input type="text" value="" id="tarif_tindakan_dokter" name="tarif_dokter"
                                    class="form-control hitung" onkeypress="return hanyaAngka(event)"
                                    onblur="cekKosong(this);jumlahTarif()" onfocus="removeZero(this)"
                                    autocomplete="off">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div id="form-group">
                                <label for="tarif_petugas">Jasa Perawat</label>
                                <input type="text" value="" id="tarif_tindakan_petugas" name="tarif_petugas"
                                    class="form-control hitung" onkeypress="return hanyaAngka(event)"
                                    onblur="cekKosong(this);jumlahTarif()" onfocus="removeZero(this)"
                                    autocomplete="off">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div id="form-group">
                                <label for="bhp">BHP/Obat</label>
                                <input type="text" value="" id="bhp" name="bhp"
                                    class="form-control hitung" onkeypress="return hanyaAngka(event)"
                                    onblur="cekKosong(this);jumlahTarif()" onfocus="removeZero(this)"
                                    autocomplete="off">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div id="form-group">
                                <label for="kso">KSO</label>
                                <input type="text" value="" id="kso" name="kso"
                                    class="form-control hitung" onkeypress="return hanyaAngka(event)"
                                    onblur="cekKosong(this);jumlahTarif()" onfocus="removeZero(this)"
                                    autocomplete="off">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div id="form-group">
                                <label for="menejemen">Manajemen</label>
                                <input type="text" value="" id="menejemen" name="menejemen"
                                    class="form-control hitung" onkeypress="return hanyaAngka(event)"
                                    onblur="cekKosong(this);jumlahTarif()" onfocus="removeZero(this)"
                                    autocomplete="off">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div id="form-group">
                                <label for="total">Total</label>
                                <input type="text" value="" id="total_byr" class="form-control hitung"
                                    name="total_byr" onkeypress="return hanyaAngka(event)" onblur="cekKosong(this)"
                                    onfocus="removeZero(this)" autocomplete="off">
                                <span for="" class="text-xs text-red">*Kosongkan kolom total untuk jumlah
                                    otomatis</span>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary " onclick="tambahTarif()"
                        data-dismiss="modal">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        function jumlahTarif() {
            bagian_rs = parseInt($("#bagian_rs").val());
            tarif_perujuk = parseInt($("#tarif_perujuk").val());
            tarif_petugas = parseInt($("#tarif_tindakan_petugas").val());
            tarif_dokter = parseInt($("#tarif_tindakan_dokter").val());
            kso = parseInt($("#kso").val());
            menejemen = parseInt($("#menejemen").val());
            bhp = parseInt($("#bhp").val());

            let total_byr = bagian_rs + tarif_dokter + tarif_perujuk + tarif_petugas + kso + menejemen + bhp;
            // if($("#total_byr").val()=='0'){
            $("#total_byr").val(total_byr);
            // }
        }

        function tambahTarif() {
            kd_jenis_prw = $('#kd_jenis_prw').val();
            nm_perawatan = $('#nm_perawatan').val();
            kamar = $('#kamar').val();
            kelas = $('#kelas').val();
            kd_pj = $('#pembiayaan').val();
            kategori = $('#kategori').val();
            bagian_rs = $('#bagian_rs').val();
            tarif_perujuk = $('#tarif_perujuk').val();
            tarif_petugas = $('#tarif_tindakan_petugas').val();
            tarif_dokter = $('#tarif_tindakan_dokter').val();
            kso = $('#kso').val();
            menejemen = $('#menejemen').val();
            bhp = $('#bhp').val();
            total_byr = $('#total_byr').val();

            $.ajax({
                url: 'lab/tambah',
                type: 'post',
                data: {
                    _token: "{{ csrf_token() }}",
                    kd_jenis_prw: kd_jenis_prw,
                    nm_perawatan: nm_perawatan,
                    bagian_rs: bagian_rs,
                    bhp: bhp,
                    tarif_perujuk: tarif_perujuk,
                    tarif_tindakan_petugas: tarif_petugas,
                    tarif_tindakan_dokter: tarif_dokter,
                    kso: kso,
                    menejemen: menejemen,
                    total_byr: total_byr,
                    kd_pj: kd_pj,
                    kelas: kelas,
                    kategori: kategori,
                },
                success: function(data) {
                    toastr.info('Tarif ' + kd_jenis_prw + ' berhasil ditambah', 'Berhasil');
                    $('#tabel-tarif-lab').DataTable().destroy();
                    load_data();
                }
            });
        }
        $('#modal-tambah').on('shown.bs.modal', function() {
            $('.hitung').val(0);
            loadPenjab();
            $.ajax({
                url: 'lab/akhir',
                success: function(data) {
                    $("input[name='kd_jenis_prw']").val(data);
                }
            }, );

        });
    </script>
@endpush
