<div class="modal fade text-sm" id="modal-ubah">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Ubah Tarif Layanan</h4>
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
                                <input type="text" value="" id="" name="kd_jenis_prw"
                                    class="form-control kd_jenis_prw" readonly>
                            </div>
                        </div>
                        <div class="col-sm-12 mb-3">
                            <div id="form-group">
                                <label for="nm_perawatan">Nama Tindakan / Perawatan</label>
                                <input type="text" value="" id="" class="form-control nm_perawatan">
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Kelas :</label>
                                <select name="kelas" id="" class="custom-select form-control-border kelas">
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
                                <select name="kategori" id=""
                                    class="custom-select form-control-border kategori">
                                    <option value="" hidden>-</option>
                                    <option value="PK">PK</option>
                                    <option value="PR">PR</option>

                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="pembiayaan">Pembiayaan</label>
                                <select class="custom-select form-control-border pembiayaan" id=""
                                    name="pembiayaan">
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div id="form-group">
                                <label for="bagian_rs">Jasa Rumah Sakit</label>
                                <input type="text" value="" id="" name="bagian_rs"
                                    class="form-control bagian_rs" onkeypress="return hanyaAngka(event)"
                                    onblur="cekKosong(this);totalTarif()" autocomplete="off" onfocus="removeZero(this)">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div id="form-group">
                                <label for="tarif_perujuk">Jasa Perujuk</label>
                                <input type="text" value="" id="" name="tarif_perujuk"
                                    class="form-control tarif_perujuk" onkeypress="return hanyaAngka(event)"
                                    onblur="cekKosong(this);totalTarif()" onfocus="removeZero(this)" autocomplete="off">
                            </div>
                        </div>
                        <div class=" col-sm-6">
                            <div id="form-group">
                                <label for="tarif_tindakandr">Jasa Dokter</label>
                                <input type="text" value="" id="" name="tarif_dokter"
                                    class="form-control tarif_dokter" onkeypress="return hanyaAngka(event)"
                                    onblur="cekKosong(this);totalTarif()" onfocus="removeZero(this)" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div id="form-group">
                                <label for="tarif_tindakan_petugas">Jasa Perawat</label>
                                <input type="text" value="" name="tarif_petugas" id=""
                                    name="tarif_tindakan_petugas" class="form-control tarif_petugas"
                                    onkeypress="return hanyaAngka(event)" onblur="cekKosong(this);totalTarif()"
                                    onfocus="removeZero(this)" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div id="form-group">
                                <label for="bhp">BHP/Obat</label>
                                <input type="text" value="" name="bhp" id=""
                                    class="form-control bhp" onkeypress="return hanyaAngka(event)"
                                    onblur="cekKosong(this);totalTarif()" onfocus="removeZero(this)"
                                    autocomplete="off">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div id="form-group">
                                <label for="kso">KSO</label>
                                <input type="text" value="" name="kso" id=""
                                    class="form-control kso" onkeypress="return hanyaAngka(event)"
                                    onblur="cekKosong(this);totalTarif()" onfocus="removeZero(this)"
                                    autocomplete="off">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div id="form-group">
                                <label for="menejemen">Manajemen</label>
                                <input type="text" name="menejemen" value="" id=""
                                    class="form-control menejemen" onkeypress="return hanyaAngka(event)"
                                    onblur="cekKosong(this);totalTarif()" onfocus="removeZero(this)"
                                    autocomplete="off">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div id="form-group">
                                <label for="total">Total</label>
                                <input type="text" value="" id="" class="form-control total_byr"
                                    name="total_byr" onkeypress="return hanyaAngka(event)"
                                    onblur="cekKosong(this);totalTarif()" onfocus="removeZero(this)"
                                    autocomplete="off">
                                <span for="" class="text-xs text-red">*Kosongkan kolom total untuk jumlah
                                    otomatis</span>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary " onclick="ubahTarif()"
                        data-dismiss="modal">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        function totalTarif() {
            bagian_rs = parseInt($(".bagian_rs").val());
            tarif_perujuk = parseInt($(".tarif_perujuk").val());
            tarif_petugas = parseInt($(".tarif_petugas").val());
            tarif_dokter = parseInt($(".tarif_dokter").val());
            kso = parseInt($(".kso").val());
            menejemen = parseInt($(".menejemen").val());
            bhp = parseInt($(".bhp").val());

            let total_byr = bagian_rs + tarif_dokter + tarif_perujuk + tarif_petugas + kso + menejemen + bhp;

            console.log(tarif_dokter);

            if ($(".total_byr").val() == '0') {
                $(".total_byr").val(total_byr);
            }
        }



        function ambilTarif(id) {
            $.ajax({
                url: 'lab/' + id,
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(data) {
                    $('.kd_jenis_prw').val(data[0].kd_jenis_prw);
                    $('.nm_perawatan').val(data[0].nm_perawatan);
                    $('.kelas').append('<option selected hidden value="' + data[0].kelas + '">' + data[0]
                        .kelas + '</option>');
                    $('.pembiayaan').append('<option selected hidden value="' + data[0].penjab.kd_pj +
                        '">' +
                        data[0].penjab.png_jawab + '</option>');
                    $('.kategori').append('<option selected hidden value="' + data[0].kategori + '">' +
                        data[0]
                        .kategori + '</option>');
                    $('.bagian_rs').val(data[0].bagian_rs);
                    $('.tarif_perujuk').val(data[0].tarif_perujuk);
                    $('.bhp').val(data[0].bhp);
                    $('.tarif_dokter').val(data[0].tarif_tindakan_dokter);
                    $('.tarif_petugas').val(data[0].tarif_tindakan_petugas);
                    $('.kso').val(data[0].kso);
                    $('.menejemen').val(data[0].menejemen);
                    $('.total_byr').val(data[0].total_byr);
                }
            })

            loadPenjab();

        }



        function ubahTarif() {
            kd_jenis_prw = $('.kd_jenis_prw').val();
            nm_perawatan = $('.nm_perawatan').val();
            kamar = $('.kamar').val();
            kelas = $('.kelas').val();
            kd_pj = $('.pembiayaan').val();
            kategori = $('.kategori').val();
            bagian_rs = $('.bagian_rs').val();
            tarif_perujuk = $('.tarif_perujuk').val();
            tarif_petugas = $('.tarif_petugas').val();
            tarif_dokter = $('.tarif_dokter').val();
            kso = $('.kso').val();
            menejemen = $('.menejemen').val();
            bhp = $('.bhp').val();
            total_byr = $('.total_byr').val();

            $.ajax({
                url: 'lab/ubah',
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
                    toastr.info('Tarif ' + kd_jenis_prw + ' berhasil diubah', 'Berhasil');
                    $('#tabel-tarif-lab').DataTable().destroy();
                    load_data();
                }
            });
        }
    </script>
@endpush
