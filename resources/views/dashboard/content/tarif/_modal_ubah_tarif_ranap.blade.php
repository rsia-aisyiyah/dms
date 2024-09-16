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
                        <div class="col-sm-6">
                            <div id="form-group">
                                <label for="kd_jenis_prw">Kode Tindakan</label>
                                <input type="text" value="" id="" name="kd_jenis_prw"
                                    class="form-control kd_jenis_prw" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="kategori">Kategori</label>
                                <input type="search" value="" name="kategori" id=""
                                    class="form-control kategori" autocomplete="off" required>
                                <div id="listKategori"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Kamar :</label>
                                <select name="kamar" id="" class="custom-select form-control-border kamar">
                                    <option value="-">-</option>
                                    <option value="B0115">Kelas 1</option>
                                    <option value="B0116">Kelas 2</option>
                                    <option value="B0117">Kelas 3</option>
                                    <option value="B0114">VIP</option>
                                </select>
                            </div>
                        </div>
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
                                <label for="pembiayaan">Pembiayaan</label>
                                <select class="custom-select form-control-border pembiayaan" id=""
                                    name="pembiayaan">
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div id="form-group">
                                <label for="nm_perawatan">Nama Tindakan / Perawatan</label>
                                <input type="text" value="" id="" class="form-control nm_perawatan">
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div id="form-group">
                                <label for="material">Jasa Rumah Sakit</label>
                                <input type="text" value="" id="" class="form-control material"
                                    onblur="hitungUbahTarif();cekKosong(this)" onkeypress="return hanyaAngka(event)"
                                    onfocus="removeZero(this)" autocomplete="off">
                            </div>
                        </div>
                        <div class=" col-sm-4">
                            <div id="form-group">
                                <label for="tarif_tindakandr">Jasa Dokter</label>
                                <input type="text" value="" id=""
                                    class="form-control tarif_tindakandr" onblur="hitungUbahTarif();cekKosong(this)"
                                    onkeypress="return hanyaAngka(event)" onfocus="removeZero(this)" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div id="form-group">
                                <label for="tarif_tindakanpr">Jasa Perawat</label>
                                <input type="text" value="" id=""
                                    class="form-control tarif_tindakanpr" onblur="hitungUbahTarif();cekKosong(this)"
                                    onkeypress="return hanyaAngka(event)" onfocus="removeZero(this)"
                                    autocomplete="off">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div id="form-group">
                                <label for="bhp">BHP/Obat</label>
                                <input type="text" value="" id="" class="form-control bhp"
                                    onblur="hitungUbahTarif();cekKosong(this)" onkeypress="return hanyaAngka(event)"
                                    onfocus="removeZero(this)" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div id="form-group">
                                <label for="kso">KSO</label>
                                <input type="text" value="" id="" class="form-control kso"
                                    onblur="hitungUbahTarif();cekKosong(this)" onkeypress="return hanyaAngka(event)"
                                    onfocus="removeZero(this)" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div id="form-group">
                                <label for="menejemen">Manajemen</label>
                                <input type="text" value="" id="" class="form-control menejemen"
                                    onblur="hitungUbahTarif();cekKosong(this)" onkeypress="return hanyaAngka(event)"
                                    onfocus="removeZero(this)" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div id="form-group">
                                <label for="total_byrdr">Total Jasa Dokter</label>
                                <input type="text" value="" id="" class="form-control total_byrdr"
                                    readonly>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div id="form-group">
                                <label for="total_byrpr">Total Jasa Perawat</label>
                                <input type="text" value="" id="" class="form-control total_byrpr"
                                    readonly>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div id="form-group">
                                <label for="total_byrdrpr">Total Jasa Dokter + Perawat</label>
                                <input type="text" value="" id=""
                                    class="form-control total_byrdrpr" readonly>
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
        function ambilTarif(id) {
            var total_item = '';
            $.ajax({
                url: 'ranap/' + id,
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(data) {
                    kategori = data[0].kategori_perawatan.kd_kategori + " - " + data[0].kategori_perawatan
                        .nm_kategori
                    $('.kd_jenis_prw').val(data[0].kd_jenis_prw);
                    $('.kategori').val(data[0].kategori_perawatan.kd_kategori + " - " + data[0]
                        .kategori_perawatan.nm_kategori);
                    $('.nm_perawatan').val(data[0].nm_perawatan);
                    $('select[name="kamar"]').append('<option selected hidden value="' + data[0].bangsal
                        .kd_bangsal + '">' + data[0].bangsal.nm_bangsal + '</option>');
                    $('select[name="kelas"]').append('<option selected hidden value="' + data[0].kelas + '">' +
                        data[0].kelas + '</option>');
                    $('select[name="pembiayaan"]').append('<option selected hidden value="' + data[0].penjab
                        .kd_pj + '">' + data[0].penjab.png_jawab + '</option>');
                    $('.material').val(data[0].material);
                    $('.bhp').val(data[0].bhp);
                    $('.tarif_tindakandr').val(data[0].tarif_tindakandr);
                    $('.tarif_tindakanpr').val(data[0].tarif_tindakanpr);
                    $('.kso').val(data[0].kso);
                    $('.menejemen').val(data[0].menejemen);
                    $('.total_byrdr').val(data[0].total_byrdr);
                    $('.total_byrpr').val(data[0].total_byrpr);
                    $('.total_byrdrpr').val(data[0].total_byrdrpr);

                }
            })

            kategoriPerawatan('.kategori', '#listKategori', 'fix')
            loadPoli('poliklinik');
            loadPenjab();

        }
        $('#listKategori').on('click', 'li', function() {
            $('.kategori').val($(this).text());
            $('#listKategori').fadeOut();
        });

        function hitungUbahTarif() {

            $('.material').val() == '' ? material = 0 : material = parseInt($('.material').val());
            $('.tarif_tindakanpr').val() == '' ? tarif_perawat = 0 : tarif_perawat = parseInt($('.tarif_tindakanpr').val());
            $('.tarif_tindakandr').val() == '' ? tarif_dokter = 0 : tarif_dokter = parseInt($('.tarif_tindakandr').val());
            $('.kso').val() == '' ? kso = 0 : kso = parseInt($('.kso').val());
            $('.menejemen').val() == '' ? menejemen : menejemen = parseInt($('.menejemen').val());
            $('.bhp').val() == '' ? bhp = 0 : bhp = parseInt($('.bhp').val());

            var total_item = material + kso + menejemen + bhp + tarif_dokter + tarif_perawat

            if (tarif_dokter != 0 && tarif_perawat != 0) {
                $('.total_byrdrpr').val(total_item);
                $('.total_byrdr').val(0);
                $('.total_byrpr').val(0);
            } else if (tarif_dokter != 0 && tarif_perawat == 0) {
                $('.total_byrdr').val(total_item);
                $('.total_byrdrpr').val(0);
                $('.total_byrpr').val(0);
            } else if (tarif_dokter == 0 || tarif_perawat != 0) {
                $('.total_byrpr').val(total_item);
                $('.total_byrdr').val(0);
                $('.total_byrdrpr').val(0);
            }
        }

        function ubahTarif() {
            kd_jenis_prw = $('.kd_jenis_prw').val();
            nm_perawatan = $('.nm_perawatan').val();
            kd_pj = $('.pembiayaan').val();
            bagian_rs = $('.material').val();
            tarif_perujuk = $('.tarif_perujuk').val();
            tarif_petugas = $('.tarif_tindakan_petugas').val();
            tarif_dokter = $('.tarif_dokter').val();
            kso = $('.kso').val();
            menejemen = $('.menejemen').val();
            bhp = $('.bhp').val();
            total_byr = $('.total_byr').val();
            kelas = $('.kelas').val();

            $.ajax({
                url: 'ranap/ubah',
                type: 'post',
                data: {
                    _token: "{{ csrf_token() }}",
                    kd_jenis_prw: kd_jenis_prw,
                    nm_perawatan: nm_perawatan,
                    kd_kategori: kategori,
                    kd_pj: kd_pj,
                    kd_bangsal: kamar,
                    kelas: kelas,
                    material: material,
                    tarif_tindakanpr: tarif_perawat,
                    tarif_tindakandr: tarif_dokter,
                    kso: kso,
                    menejemen: menejemen,
                    bhp: bhp,
                    total_byrdr: total_byrdr,
                    total_byrpr: total_byrpr,
                    total_byrdrpr: total_byrdrpr,
                },
                success: function(data) {
                    toastr.info('Tarif ' + kd_jenis_prw + ' berhasil diubah', 'Berhasil');
                    $('#tabel-tarif-ranap').DataTable().destroy();
                    load_data();
                }
            });
        }
    </script>
@endpush
