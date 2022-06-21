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
                        <div class="col-sm-6">
                            <div id="form-group">
                                <label for="kd_jenis_prw">Kode Tindakan</label>
                                <input type="text" value="" id="kd_jenis_prw" name="kd_jenis_prw" class="form-control"
                                    readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="nm_kategori">Kategori</label>
                                <input type="search" value="" name="nm_kategori" id="nm_kategori" class="form-control"
                                    autocomplete="off" required>
                                <div id="list_nm_kategori"></div>
                            </div>
                        </div>
                    </div>

                    <div id="form-group">
                        <label for="nm_perawatan">Nama Tindakan / Perawatan</label>
                        <input type="text" name="nm_perawatan" value="" id="nm_perawatan" class="form-control"
                            autocomplete="off" autocapitalize="on">
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="poliklinik">Unit / Poli</label>
                                <select class="custom-select form-control-border" id="poliklinik" name="poliklinik">
                                    <option hidden value="">Pilih Unit / Poli</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="pembiayaan">Pembiayaan</label>
                                <select class="custom-select form-control-border" id="pembiayaan" name="pembiayaan">
                                    <option hidden value="">Pilih Pembiayaan</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div id="form-group">
                                <label for="material">Jasa Rumah Sakit</label>
                                <input type="text" value="" id="material" class="form-control hitung" onblur="hitung()"
                                    onkeypress="return hanyaAngka(event)" onchange="cekKosong(this)" autocomplete="off">
                            </div>
                        </div>
                        <div class=" col-sm-4">
                            <div id="form-group">
                                <label for="tarif_tindakandr">Jasa Dokter</label>
                                <input type="text" value="" id="tarif_tindakandr" class="form-control hitung"
                                    onblur="hitung()" onkeypress="return hanyaAngka(event)" onchange="cekKosong(this)"
                                    autocomplete="off">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div id="form-group">
                                <label for="tarif_tindakanpr">Jasa Perawat</label>
                                <input type="text" value="" id="tarif_tindakanpr" class="form-control hitung"
                                    onblur="hitung()" onkeypress="return hanyaAngka(event)" onchange="cekKosong(this)"
                                    autocomplete="off">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div id="form-group">
                                <label for="bhp">BHP/Obat</label>
                                <input type="text" value="" id="bhp" class="form-control hitung" onblur="hitung()"
                                    onkeypress="return hanyaAngka(event)" onchange="cekKosong(this)" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div id="form-group">
                                <label for="kso">KSO</label>
                                <input type="text" value="" id="kso" class="form-control hitung" onblur="hitung()"
                                    onkeypress="return hanyaAngka(event)" onchange="cekKosong(this)" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div id="form-group">
                                <label for="menejemen">Manajemen</label>
                                <input type="text" value="" id="menejemen" class="form-control hitung" onblur="hitung()"
                                    onkeypress="return hanyaAngka(event)" onchange="cekKosong(this)" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div id="form-group">
                                <label for="total_byrdr">Total Jasa Dokter</label>
                                <input type="text" value="" id="total_byrdr" class="form-control hitung" readonly>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div id="form-group">
                                <label for="total_byrpr">Total Jasa Perawat</label>
                                <input type="text" value="" id="total_byrpr" class="form-control hitung" readonly>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div id="form-group">
                                <label for="total_byrdrpr">Total Jasa Dokter + Perawat</label>
                                <input type="text" value="" id="total_byrdrpr" class="form-control hitung" readonly>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="tambahtarif()"
                        data-dismiss="modal">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
<script>
    $('#modal-tambah').on('shown.bs.modal', function () {
        kategoriPerawatan('#nm_kategori', '#list_nm_kategori', 'fix');
        loadPoli('poliklinik');
        loadPenjab();
        $('.hitung').val(0);

        $.ajax(
            {
                url: 'akhir',
                success: function (data) {
                    $("input[name='kd_jenis_prw']").val(data);
                }
            },
        );

    })

    $('#list_nm_kategori').on('click', 'li', function () {
        $('#nm_kategori').val($(this).text());
        $('#list_nm_kategori').fadeOut();
    });


    $('#modal-tambah').on('hidden.bs.modal', function () {
        $('select[name="poliklinik"] option').detach();
        $('select[name="pembiayaan"] option').detach();
    });
    function hitung() {

        material = parseInt($('#material').val());
        tarif_perawat = parseInt($('#tarif_tindakanpr').val());
        tarif_dokter = parseInt($('#tarif_tindakandr').val());
        kso = parseInt($('#kso').val());
        menejemen = parseInt($('#menejemen').val());
        bhp = parseInt($('#bhp').val());

        var total_item = material + kso + menejemen + bhp + tarif_dokter + tarif_perawat

        if (tarif_dokter != 0 && tarif_perawat != 0) {
            $('#total_byrdrpr').val(total_item);
            $('#total_byrdr').val(0);
            $('#total_byrpr').val(0);
        } else if (tarif_dokter != 0 && tarif_perawat == 0) {
            $('#total_byrdr').val(total_item);
            $('#total_byrdrpr').val(0);
            $('#total_byrpr').val(0);
        } else if (tarif_dokter == 0 || tarif_perawat != 0) {
            $('#total_byrpr').val(total_item);
            $('#total_byrdr').val(0);
            $('#total_byrdrpr').val(0);
        }
    }

    function tambahtarif() {
        let kd_jenis_prw = $('#kd_jenis_prw').val();
        let nm_perawatan = $('#nm_perawatan').val();
        let poli = $('#poliklinik').val();
        let kd_pj = $('#pembiayaan').val();
        let textKategori = $('#nm_kategori').val().split("-");
        let kategori = textKategori[0];
        let material = $('#material').val();
        let tarif_perawat = $('#tarif_tindakanpr').val();
        let tarif_dokter = $('#tarif_tindakandr').val();
        let kso = $('#kso').val();
        let menejemen = $('#menejemen').val();
        let bhp = $('#bhp').val();
        let total_byrdr = $('#total_byrdr').val();
        let total_byrpr = $('#total_byrpr').val();
        let total_byrdrpr = $('#total_byrdrpr').val();

        $.ajax({
            url: 'ralan/tambahtarif',
            type: 'post',
            data: {
                _token: "{{ csrf_token() }}",
                kd_jenis_prw: kd_jenis_prw,
                nm_perawatan: nm_perawatan,
                kd_kategori: kategori,
                kd_pj: kd_pj,
                kd_poli: poli,
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
            success: function (data) {
                toastr.info('Tarif ' + kd_jenis_prw + ' berhasil diubah', 'Berhasil');
                $('#tabel-tarif-ralan').DataTable().destroy();
                load_data();
            }
        });
    }
</script>
@endpush