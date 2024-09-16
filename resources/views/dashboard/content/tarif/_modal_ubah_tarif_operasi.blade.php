<div class="modal fade text-sm" id="modal-ubah">
    <div class="modal-dialog modal-xl">
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
                        <div class="col-2">
                            <div id="form-group">
                                <label for="kd_jenis_prw">Kode Tindakan</label>
                                <input type="text" value="" id="u_kd_jenis_prw" name="kd_jenis_prw" class="form-control"
                                    readonly>
                            </div>
                        </div>
                        <div class="col-sm-3 mb-3">
                            <div id="form-group">
                                <label for="nm_perawatan">Nama Tindakan / Perawatan</label>
                                <input type="text" value="" id="u_nm_perawatan" class="form-control" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Kategori :</label>
                                <select name="kategori" id="u_kategori" class="custom-select form-control-border">
                                    <option value="Kebidanan" selected>Kebidanan</option>
                                    <option value="Operasi">Operasi</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Kelas :</label>
                                <select name="kelas" id="u_kelas" class="custom-select form-control-border">
                                    <option value="-">-</option>
                                    <option value="Kelas 1">Kelas 1</option>
                                    <option value="Kelas 2">Kelas 2</option>
                                    <option value="Kelas 3">Kelas 3</option>
                                    <option value="Kelas VIP">Kelas VIP</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="pembiayaan">Pembiayaan</label>
                                <select class="custom-select form-control-border" id="u_pembiayaan" name="pembiayaan">
                                </select>
                            </div>
                        </div>

                    </div>
                    <fieldset class="border p-2 mb-3">
                        <legend style="font-size: 1.2em;margin-bottom:0px"><span
                                class="badge badge-info">Operator</span>
                        </legend>
                        <div class="row">
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="op1">Operator 1</label>
                                    <input type="text" value="" name="op1" id="u_op1" class="form-control hitung"
                                        onkeypress="return hanyaAngka(event)" onblur="cekKosong(this);hitungUbah()"
                                        onfocus="removeZero(this)" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="op2">Operator 2</label>
                                    <input type="text" value="" name="op2" id="u_op2" class="form-control hitung"
                                        onkeypress="return hanyaAngka(event)" onblur="cekKosong(this);hitungUbah()"
                                        onfocus="removeZero(this)" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="op3">Operator 3</label>
                                    <input type="text" value="" name="op3" id="u_op3" class="form-control hitung"
                                        onkeypress="return hanyaAngka(event)" onblur="cekKosong(this);hitungUbah()"
                                        onfocus="removeZero(this)" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="border p-2 mb-3">
                        <legend style="font-size: 1.2em;margin-bottom:0px"><span class="badge badge-info">Asisten
                                Operator</span>
                        </legend>
                        <div class="row">
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="a_op1">Assiten Op. 1</label>
                                    <input type="text" value="" name="a_op1" id="u_a_op1" class="form-control hitung"
                                        onkeypress="return hanyaAngka(event)" onblur="cekKosong(this);hitungUbah()"
                                        onfocus="removeZero(this)" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="a_op2">Assiten Op. 2</label>
                                    <input type="text" value="" name="a_op2" id="u_a_op2" class="form-control hitung"
                                        onkeypress="return hanyaAngka(event)" onblur="cekKosong(this);hitungUbah()"
                                        onfocus="removeZero(this)" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="a_op3">Assiten Op. 3</label>
                                    <input type="text" value="" name="a_op3" id="u_a_op3" class="form-control hitung"
                                        onkeypress="return hanyaAngka(event)" onblur="cekKosong(this);hitungUbah()"
                                        onfocus="removeZero(this)" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="border p-2 mb-3">
                        <legend style="font-size: 1.2em;margin-bottom:0px"><span class="badge badge-info">Dokter
                                Anestesi & Asisten</span>
                        </legend>
                        <div class="row">
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="anes">dr. Anestesi</label>
                                    <input type="text" value="" name="anes" id="u_anes" class="form-control hitung"
                                        onkeypress="return hanyaAngka(event)" onblur="cekKosong(this);hitungUbah()"
                                        onfocus="removeZero(this)" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="a_anes1">Assiten Anes. 1</label>
                                    <input type="text" value="" name="a_anes1" id="u_a_anes1"
                                        class="form-control hitung" onkeypress="return hanyaAngka(event)"
                                        onblur="cekKosong(this);hitungUbah()" onfocus="removeZero(this)"
                                        autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="a_anes2">Assiten Anes. 2</label>
                                    <input type="text" value="" name="a_anes2" id="u_a_anes2"
                                        class="form-control hitung" onkeypress="return hanyaAngka(event)"
                                        onblur="cekKosong(this);hitungUbah()" onfocus="removeZero(this)"
                                        autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="border p-2 mb-3">
                        <legend style="font-size: 1.2em;margin-bottom:0px"><span class="badge badge-info">Onloop</span>
                        </legend>
                        <div class="row">
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="onloop1">Onloop 1</label>
                                    <input type="text" value="" name="onloop1" id="u_onloop1"
                                        class="form-control hitung" onkeypress="return hanyaAngka(event)"
                                        onblur="cekKosong(this);hitungUbah()" onfocus="removeZero(this)"
                                        autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="onloop2">Onloop 2</label>
                                    <input type="text" value="" name="onloop2" id="u_onloop2"
                                        class="form-control hitung" onkeypress="return hanyaAngka(event)"
                                        onblur="cekKosong(this);hitungUbah()" onfocus="removeZero(this)"
                                        autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="onloop3">Onloop 3</label>
                                    <input type="text" value="" name="onloop3" id="u_onloop3"
                                        class="form-control hitung" onkeypress="return hanyaAngka(event)"
                                        onblur="cekKosong(this);hitungUbah()" onfocus="removeZero(this)"
                                        autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="onloop4">Onloop 4</label>
                                    <input type="text" value="" name="onloop4" id="u_onloop4"
                                        class="form-control hitung" onkeypress="return hanyaAngka(event)"
                                        onblur="cekKosong(this);hitungUbah()" onfocus="removeZero(this)"
                                        autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="onloop5">Onloop 5</label>
                                    <input type="text" value="" name="onloop5" id="u_onloop5"
                                        class="form-control hitung" onkeypress="return hanyaAngka(event)"
                                        onblur="cekKosong(this);hitungUbah()" onfocus="removeZero(this)"
                                        autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="border p-2 mb-3">
                        <legend style="font-size: 1.2em;margin-bottom:0px"><span class="badge badge-info">Bidan</span>
                        </legend>
                        <div class="row">
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="bidan1">Bidan 1</label>
                                    <input type="text" value="" name="bidan1" id="u_bidan1" class="form-control hitung"
                                        onkeypress="return hanyaAngka(event)" onblur="cekKosong(this);hitungUbah()"
                                        onfocus="removeZero(this)" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="bidan2">Bidan 2</label>
                                    <input type="text" value="" name="bidan2" id="u_bidan2" class="form-control hitung"
                                        onkeypress="return hanyaAngka(event)" onblur="cekKosong(this);hitungUbah()"
                                        onfocus="removeZero(this)" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="bidan3">Bidan 3</label>
                                    <input type="text" value="" name="bidan3" id="u_bidan3" class="form-control hitung"
                                        onkeypress="return hanyaAngka(event)" onblur="cekKosong(this);hitungUbah()"
                                        onfocus="removeZero(this)" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="border p-2 mb-3">
                        <legend style="font-size: 1.2em;margin-bottom:0px"><span class="badge badge-info">Dokter Anak,
                                PJ dan Umum</span>
                        </legend>
                        <div class="row">
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="dr_anak">dr. Anak</label>
                                    <input type="text" value="" name="dr_anak" id="u_dr_anak"
                                        class="form-control hitung" onkeypress="return hanyaAngka(event)"
                                        onblur="cekKosong(this);hitungUbah()" onfocus="removeZero(this)"
                                        autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="dr_pjanak">dr. PJ Anak</label>
                                    <input type="text" value="" name="dr_pjanak" id="u_dr_pjanak"
                                        class="form-control hitung" onkeypress="return hanyaAngka(event)"
                                        onblur="cekKosong(this);hitungUbah()" onfocus="removeZero(this)"
                                        autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="dr_umum">Dokter Umum</label>
                                    <input type="text" value="" name="dr_umum" id="u_dr_umum"
                                        class="form-control hitung" onkeypress="return hanyaAngka(event)"
                                        onblur="cekKosong(this);hitungUbah()" onfocus="removeZero(this)"
                                        autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="border p-2 mb-3">
                        <legend style="font-size: 1.2em;margin-bottom:0px"><span class="badge badge-info">Perawat dan
                                Instrumen</span>
                        </legend>
                        <div class="row">
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="p_resus">Perawat Resusitasi</label>
                                    <input type="text" value="" name="p_resus" id="u_p_resus"
                                        class="form-control hitung" onkeypress="return hanyaAngka(event)"
                                        onblur="cekKosong(this);hitungUbah()" onfocus="removeZero(this)"
                                        autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="p_luar">Perawat Luar</label>
                                    <input type="text" value="" name="p_luar" id="u_p_luar" class="form-control hitung"
                                        onkeypress="return hanyaAngka(event)" onblur="cekKosong(this);hitungUbah()"
                                        onfocus="removeZero(this)" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="instrumen">Instrumen</label>
                                    <input type="text" value="" name="instrumen" id="u_instrumen"
                                        class="form-control hitung" onkeypress="return hanyaAngka(event)"
                                        onblur="cekKosong(this);hitungUbah()" onfocus="removeZero(this)"
                                        autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="border p-2 mb-3">
                        <legend style="font-size: 1.2em;margin-bottom:0px"><span
                                class="badge badge-info">Lain-lain</span>
                        </legend>
                        <div class="row">
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="akomodasi">Akomodasi</label>
                                    <input type="text" value="" name="akomodasi" id="u_akomodasi"
                                        class="form-control hitung" onkeypress="return hanyaAngka(event)"
                                        onblur="cekKosong(this);hitungUbah()" onfocus="removeZero(this)"
                                        autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="sewa">Sewa OK/VK</label>
                                    <input type="text" value="" name="sewa" id="u_sewa" class="form-control hitung"
                                        onkeypress="return hanyaAngka(event)" onblur="cekKosong(this);hitungUbah()"
                                        onfocus="removeZero(this)" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="sarpras">Sarpras</label>
                                    <input type="text" value="" name="sarpras" id="u_sarpras"
                                        class="form-control hitung" onkeypress="return hanyaAngka(event)"
                                        onblur="cekKosong(this);hitungUbah()" onfocus="removeZero(this)"
                                        autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="alat">Alat</label>
                                    <input type="text" value="" name="alat" id="u_alat" class="form-control hitung"
                                        onkeypress="return hanyaAngka(event)" onblur="cekKosong(this);hitungUbah()"
                                        onfocus="removeZero(this)" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="bagian">Bagian RS</label>
                                    <input type="text" value="" name="bagian" id="u_bagian" class="form-control hitung"
                                        onkeypress="return hanyaAngka(event)" onblur="cekKosong(this);hitungUbah()"
                                        onfocus="removeZero(this)" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="total">Total</label>
                                    <input type="text" value="" name="total" id="u_total" class="form-control hitung"
                                        onkeypress="return hanyaAngka(event)" autocomplete="off" disabled>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary " onclick="ubahTarifOperasi()"
                        data-dismiss="modal">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
<script>
    function hitungUbah() {
        op1 = parseInt($('#u_op1').val());
        op2 = parseInt($('#u_op2').val());
        op3 = parseInt($('#u_op3').val());
        a_op1 = parseInt($('#u_a_op1').val());
        a_op2 = parseInt($('#u_a_op2').val());
        a_op3 = parseInt($('#u_a_op3').val());
        anes = parseInt($('#u_anes').val());
        a_anes1 = parseInt($('#u_a_anes1').val());
        a_anes2 = parseInt($('#u_a_anes2').val());
        onloop1 = parseInt($('#u_onloop1').val());
        onloop2 = parseInt($('#u_onloop2').val());
        onloop3 = parseInt($('#u_onloop3').val());
        onloop4 = parseInt($('#u_onloop4').val());
        onloop5 = parseInt($('#u_onloop5').val());
        bidan1 = parseInt($('#u_bidan1').val());
        bidan2 = parseInt($('#u_bidan2').val());
        bidan3 = parseInt($('#u_bidan3').val());
        dr_anak = parseInt($('#u_dr_anak').val());
        dr_pjanak = parseInt($('#u_dr_pjanak').val());
        dr_umum = parseInt($('#u_dr_umum').val());
        p_resus = parseInt($('#u_p_resus').val());
        p_luar = parseInt($('#u_p_luar').val());
        instrumen = parseInt($('#u_instrumen').val());
        akomodasi = parseInt($('#u_akomodasi').val());
        sewa = parseInt($('#u_sewa').val());
        sarpras = parseInt($('#u_sarpras').val());
        alat = parseInt($('#u_alat').val());
        bagian = parseInt($('#u_bagian').val());

        total = op1 + op2 + op3 + a_op1 + a_op2 +
            a_op3 + anes + a_anes1 + a_anes2 + onloop1 + onloop2 + onloop3 +
            onloop4 + onloop5 + bidan1 + bidan2 + bidan3 + dr_anak + dr_pjanak +
            dr_umum + p_resus + p_luar + instrumen + akomodasi + sewa + sarpras + alat + bagian;

        $('#u_total').val(total);

    }

    function ambilTarifOperasi(id) {

        $.ajax({
            url: 'operasi/' + id,
            data: {
                "_token": "{{ csrf_token() }}"
            },

            success: function (data) {
                $('#u_kd_jenis_prw').val(data[0].kode_paket);
                $('#u_nm_perawatan').val(data[0].nm_perawatan);
                $('#u_kelas').append('<option selected hidden value="' + data[0].kelas + '">' + data[0]
                    .kelas + '</option>');
                $('#u_pembiayaan').append('<option selected hidden value="' + data[0].penjab.kd_pj +
                    '">' +
                    data[0].penjab.png_jawab + '</option>');
                $('#u_kategori').append('<option selected hidden value="' + data[0].kategori + '">' +
                    data[0]
                        .kategori + '</option>');
                $('#u_op1').val(data[0].operator1);
                $('#u_op2').val(data[0].operator2);
                $('#u_op3').val(data[0].operator3);
                $('#u_a_op1').val(data[0].asisten_operator1);
                $('#u_a_op2').val(data[0].asisten_operator2);
                $('#u_a_op3').val(data[0].asisten_operator3);
                $('#u_anes').val(data[0].dokter_anestesi);
                $('#u_a_anes1').val(data[0].asisten_anestesi);
                $('#u_a_anes2').val(data[0].asisten_anestesi2);
                $('#u_onloop1').val(data[0].omloop);
                $('#u_onloop2').val(data[0].omloop2);
                $('#u_onloop3').val(data[0].omloop3);
                $('#u_onloop4').val(data[0].omloop4);
                $('#u_onloop5').val(data[0].omloop5);
                $('#u_bidan1').val(data[0].bidan);
                $('#u_bidan2').val(data[0].bidan2);
                $('#u_bidan3').val(data[0].bidan3);
                $('#u_dr_anak').val(data[0].dokter_anak);
                $('#u_dr_pjanak').val(data[0].dokter_pjanak);
                $('#u_dr_umum').val(data[0].dokter_umum);
                $('#u_p_resus').val(data[0].perawaat_resusitas);
                $('#u_p_luar').val(data[0].perawat_luar);
                $('#u_instrumen').val(data[0].instrumen);
                $('#u_akomodasi').val(data[0].akomodasi);
                $('#u_sewa').val(data[0].sewa_ok);
                $('#u_sarpras').val(data[0].sarpras);
                $('#u_alat').val(data[0].alat);
                $('#u_bagian').val(data[0].bagian_rs);
                $('#u_total').val(data[0].total);


                loadPenjab();
            }
        })
    }

    function ubahTarifOperasi() {
        kd_jenis_prw = $('#u_kd_jenis_prw').val();
        nm_perawatan = $('#u_nm_perawatan').val();
        kelas = $('#u_kelas').val();
        kd_pj = $('#u_pembiayaan').val();
        kategori = $('#u_kategori').val();
        op1 = $('#u_op1').val();
        op2 = $('#u_op2').val();
        op3 = $('#u_op3').val();
        a_op1 = $('#u_a_op1').val();
        a_op2 = $('#u_a_op2').val();
        a_op3 = $('#u_a_op3').val();
        anes = $('#u_anes').val();
        a_anes1 = $('#u_a_anes1').val();
        a_anes2 = $('#u_a_anes2').val();
        onloop1 = $('#u_onloop1').val();
        onloop2 = $('#u_onloop2').val();
        onloop3 = $('#u_onloop3').val();
        onloop4 = $('#u_onloop4').val();
        onloop5 = $('#u_onloop5').val();
        bidan1 = $('#u_bidan1').val();
        bidan2 = $('#u_bidan2').val();
        bidan3 = $('#u_bidan3').val();
        dr_anak = $('#u_dr_anak').val();
        dr_pjanak = $('#u_dr_pjanak').val();
        dr_umum = $('#u_dr_umum').val();
        p_resus = $('#u_p_resus').val();
        p_luar = $('#u_p_luar').val();
        instrumen = $('#u_instrumen').val();
        akomodasi = $('#u_akomodasi').val();
        sewa = $('#u_sewa').val();
        sarpras = $('#u_sarpras').val();
        alat = $('#u_alat').val();
        bagian = $('#u_bagian').val();
        $.ajax({
            url: 'operasi/ubah',
            type: 'post',
            data: {
                _token: "{{ csrf_token() }}",
                kode_paket: kd_jenis_prw,
                nm_perawatan: nm_perawatan,
                kategori: kategori,
                operator1: op1,
                operator2: op2,
                operator3: op3,
                asisten_operator1: a_op1,
                asisten_operator2: a_op2,
                asisten_operator3: a_op3,
                instrumen: instrumen,
                dokter_anak: dr_anak,
                perawat_resusitas: p_resus,
                dokter_anestesi: anes,
                asisten_anes: a_anes1,
                asisten_anes2: a_anes2,
                bidan: bidan1,
                bidan2: bidan2,
                bidan3: bidan3,
                perawat_luar: p_luar,
                sewa_ok: sewa,
                akomodasi: akomodasi,
                bagian_rs: bagian,
                omloop: onloop1,
                omloop2: onloop2,
                omloop3: onloop3,
                omloop4: onloop4,
                omloop5: onloop5,
                sarpras: sarpras,
                alat: alat,
                dokter_pjanak: dr_pjanak,
                dokter_umum: dr_umum,
                kd_pj: kd_pj,
                kelas: kelas,

            },
            success: function (data) {
                toastr.info('Tarif ' + kd_jenis_prw + ' berhasil diubah', 'Berhasil');
                $('#tabel-tarif-operasi').DataTable().destroy();
                load_data();
            }
        });
    }

    $('#modal-ubah').on('hidden.bs.modal', function () {
        $('select[name="pembiayaan"] option').detach();
    });
</script>
@endpush