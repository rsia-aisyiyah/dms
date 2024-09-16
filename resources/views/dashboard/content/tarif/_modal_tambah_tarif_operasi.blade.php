<div class="modal fade text-sm" id="modal-tambah">
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
                        <div class="col-sm-2">
                            <div id="form-group">
                                <label for="kd_jenis_prw">Kode Tindakan</label>
                                <input type="text" value="" id="kd_jenis_prw" name="kd_jenis_prw"
                                    class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-sm-3 mb-3">
                            <div id="form-group">
                                <label for="nm_perawatan">Nama Tindakan / Perawatan</label>
                                <input type="text" value="" id="nm_perawatan" class="form-control"
                                    autocomplete="off">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Kategori :</label>
                                <select name="kategori" id="kategori" class="custom-select form-control-border">
                                    <option value="Kebidanan" selected>Kebidanan</option>
                                    <option value="Operasi">Operasi</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
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
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="pembiayaan">Pembiayaan</label>
                                <select class="custom-select form-control-border" id="pembiayaan" name="pembiayaan">
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
                                    <input type="text" value="" name="op1" id="op1"
                                        class="form-control hitung" onkeypress="return hanyaAngka(event)"
                                        onblur="cekKosong(this);hitungOperasi()" onfocus="removeZero(this)"
                                        autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="op2">Operator 2</label>
                                    <input type="text" value="" name="op2" id="op2"
                                        class="form-control hitung" onkeypress="return hanyaAngka(event)"
                                        onblur="cekKosong(this);hitungOperasi()" onfocus="removeZero(this)"
                                        autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="op3">Operator 3</label>
                                    <input type="text" value="" name="op3" id="op3"
                                        class="form-control hitung" onkeypress="return hanyaAngka(event)"
                                        onblur="cekKosong(this);hitungOperasi()" onfocus="removeZero(this)"
                                        autocomplete="off">
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
                                    <input type="text" value="" name="a_op1" id="a_op1"
                                        class="form-control hitung" onkeypress="return hanyaAngka(event)"
                                        onblur="cekKosong(this);hitungOperasi()" onfocus="removeZero(this)"
                                        autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="a_op2">Assiten Op. 2</label>
                                    <input type="text" value="" name="a_op2" id="a_op2"
                                        class="form-control hitung" onkeypress="return hanyaAngka(event)"
                                        onblur="cekKosong(this);hitungOperasi()" onfocus="removeZero(this)"
                                        autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="a_op3">Assiten Op. 3</label>
                                    <input type="text" value="" name="a_op3" id="a_op3"
                                        class="form-control hitung" onkeypress="return hanyaAngka(event)"
                                        onblur="cekKosong(this);hitungOperasi()" onfocus="removeZero(this)"
                                        autocomplete="off">
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
                                    <input type="text" value="" name="anes" id="anes"
                                        class="form-control hitung" onkeypress="return hanyaAngka(event)"
                                        onblur="cekKosong(this);hitungOperasi()" onfocus="removeZero(this)"
                                        autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="a_anes1">Assiten Anes. 1</label>
                                    <input type="text" value="" name="a_anes1" id="a_anes1"
                                        class="form-control hitung" onkeypress="return hanyaAngka(event)"
                                        onblur="cekKosong(this);hitungOperasi()" onfocus="removeZero(this)"
                                        autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="a_anes2">Assiten Anes. 2</label>
                                    <input type="text" value="" name="a_anes2" id="a_anes2"
                                        class="form-control hitung" onkeypress="return hanyaAngka(event)"
                                        onblur="cekKosong(this);hitungOperasi()" onfocus="removeZero(this)"
                                        autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="border p-2 mb-3">
                        <legend style="font-size: 1.2em;margin-bottom:0px"><span
                                class="badge badge-info">Onloop</span>
                        </legend>
                        <div class="row">
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="onloop1">Onloop 1</label>
                                    <input type="text" value="" name="onloop1" id="onloop1"
                                        class="form-control hitung" onkeypress="return hanyaAngka(event)"
                                        onblur="cekKosong(this);hitungOperasi()" onfocus="removeZero(this)"
                                        autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="onloop2">Onloop 2</label>
                                    <input type="text" value="" name="onloop2" id="onloop2"
                                        class="form-control hitung" onkeypress="return hanyaAngka(event)"
                                        onblur="cekKosong(this);hitungOperasi()" onfocus="removeZero(this)"
                                        autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="onloop3">Onloop 3</label>
                                    <input type="text" value="" name="onloop3" id="onloop3"
                                        class="form-control hitung" onkeypress="return hanyaAngka(event)"
                                        onblur="cekKosong(this);hitungOperasi()" onfocus="removeZero(this)"
                                        autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="onloop4">Onloop 4</label>
                                    <input type="text" value="" name="onloop4" id="onloop4"
                                        class="form-control hitung" onkeypress="return hanyaAngka(event)"
                                        onblur="cekKosong(this);hitungOperasi()" onfocus="removeZero(this)"
                                        autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="onloop5">Onloop 5</label>
                                    <input type="text" value="" name="onloop5" id="onloop5"
                                        class="form-control hitung" onkeypress="return hanyaAngka(event)"
                                        onblur="cekKosong(this);hitungOperasi()" onfocus="removeZero(this)"
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
                                    <input type="text" value="" name="bidan1" id="bidan1"
                                        class="form-control hitung" onkeypress="return hanyaAngka(event)"
                                        onblur="cekKosong(this);hitungOperasi()" onfocus="removeZero(this)"
                                        autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="bidan2">Bidan 2</label>
                                    <input type="text" value="" name="bidan2" id="bidan2"
                                        class="form-control hitung" onkeypress="return hanyaAngka(event)"
                                        onblur="cekKosong(this);hitungOperasi()" onfocus="removeZero(this)"
                                        autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="bidan3">Bidan 3</label>
                                    <input type="text" value="" name="bidan3" id="bidan3"
                                        class="form-control hitung" onkeypress="return hanyaAngka(event)"
                                        onblur="cekKosong(this);hitungOperasi()" onfocus="removeZero(this)"
                                        autocomplete="off">
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
                                    <input type="text" value="" name="dr_anak" id="dr_anak"
                                        class="form-control hitung" onkeypress="return hanyaAngka(event)"
                                        onblur="cekKosong(this);hitungOperasi()" onfocus="removeZero(this)"
                                        autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="dr_pjanak">dr. PJ Anak</label>
                                    <input type="text" value="" name="dr_pjanak" id="dr_pjanak"
                                        class="form-control hitung" onkeypress="return hanyaAngka(event)"
                                        onblur="cekKosong(this);hitungOperasi()" onfocus="removeZero(this)"
                                        autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="dr_umum">Dokter Umum</label>
                                    <input type="text" value="" name="dr_umum" id="dr_umum"
                                        class="form-control hitung" onkeypress="return hanyaAngka(event)"
                                        onblur="cekKosong(this);hitungOperasi()" onfocus="removeZero(this)"
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
                                    <input type="text" value="" name="p_resus" id="p_resus"
                                        class="form-control hitung" onkeypress="return hanyaAngka(event)"
                                        onblur="cekKosong(this);hitungOperasi()" onfocus="removeZero(this)"
                                        autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="p_luar">Perawat Luar</label>
                                    <input type="text" value="" name="p_luar" id="p_luar"
                                        class="form-control hitung" onkeypress="return hanyaAngka(event)"
                                        onblur="cekKosong(this);hitungOperasi()" onfocus="removeZero(this)"
                                        autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="instrumen">Instrumen</label>
                                    <input type="text" value="" name="instrumen" id="instrumen"
                                        class="form-control hitung" onkeypress="return hanyaAngka(event)"
                                        onblur="cekKosong(this);hitungOperasi()" onfocus="removeZero(this)"
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
                                    <input type="text" value="" name="akomodasi" id="akomodasi"
                                        class="form-control hitung" onkeypress="return hanyaAngka(event)"
                                        onblur="cekKosong(this);hitungOperasi()" onfocus="removeZero(this)"
                                        autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="sewa">Sewa OK/VK</label>
                                    <input type="text" value="" name="sewa" id="sewa"
                                        class="form-control hitung" onkeypress="return hanyaAngka(event)"
                                        onblur="cekKosong(this);hitungOperasi()" onfocus="removeZero(this)"
                                        autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="sarpras">Sarpras</label>
                                    <input type="text" value="" name="sarpras" id="sarpras"
                                        class="form-control hitung" onkeypress="return hanyaAngka(event)"
                                        onblur="cekKosong(this);hitungOperasi()" onfocus="removeZero(this)"
                                        autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="alat">Alat</label>
                                    <input type="text" value="" name="alat" id="alat"
                                        class="form-control hitung" onkeypress="return hanyaAngka(event)"
                                        onblur="cekKosong(this);hitungOperasi()" onfocus="removeZero(this)"
                                        autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="bagian">Bagian RS</label>
                                    <input type="text" value="" name="bagian" id="bagian"
                                        class="form-control hitung" onkeypress="return hanyaAngka(event)"
                                        onblur="cekKosong(this);hitungOperasi()" onfocus="removeZero(this)"
                                        autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div id="form-group">
                                    <label for="total">Total</label>
                                    <input type="text" value="" name="total" id="total"
                                        class="form-control hitung" onkeypress="return hanyaAngka(event)"
                                        onblur="cekKosong(this);hitungOperasi()" autocomplete="off" disabled>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary " onclick="tambahTarifOperasi()"
                        data-dismiss="modal">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        function hitungOperasi() {
            op1 = parseInt($('#op1').val());
            op2 = parseInt($('#op2').val());
            op3 = parseInt($('#op3').val());
            a_op1 = parseInt($('#a_op1').val());
            a_op2 = parseInt($('#a_op2').val());
            a_op3 = parseInt($('#a_op3').val());
            anes = parseInt($('#anes').val());
            a_anes1 = parseInt($('#a_anes1').val());
            a_anes2 = parseInt($('#a_anes2').val());
            onloop1 = parseInt($('#onloop1').val());
            onloop2 = parseInt($('#onloop2').val());
            onloop3 = parseInt($('#onloop3').val());
            onloop4 = parseInt($('#onloop4').val());
            onloop5 = parseInt($('#onloop5').val());
            bidan1 = parseInt($('#bidan1').val());
            bidan2 = parseInt($('#bidan2').val());
            bidan3 = parseInt($('#bidan3').val());
            dr_anak = parseInt($('#dr_anak').val());
            dr_pjanak = parseInt($('#dr_pjanak').val());
            dr_umum = parseInt($('#dr_umum').val());
            p_resus = parseInt($('#p_resus').val());
            p_luar = parseInt($('#p_luar').val());
            instrumen = parseInt($('#instrumen').val());
            akomodasi = parseInt($('#akomodasi').val());
            sewa = parseInt($('#sewa').val());
            sarpras = parseInt($('#sarpras').val());
            alat = parseInt($('#alat').val());
            bagian = parseInt($('#bagian').val());

            total = op1 + op2 + op3 + a_op1 + a_op2 +
                a_op3 + anes + a_anes1 + a_anes2 + onloop1 + onloop2 + onloop3 +
                onloop4 + onloop5 + bidan1 + bidan2 + bidan3 + dr_anak + dr_pjanak +
                dr_umum + p_resus + p_luar + instrumen + akomodasi + sewa + sarpras + alat + bagian;

            $('#total').val(total);


        }

        function tambahTarifOperasi() {
            kd_jenis_prw = $('#kd_jenis_prw').val();
            nm_perawatan = $('#nm_perawatan').val();
            kelas = $('#kelas').val();
            kd_pj = $('#pembiayaan').val();
            kategori = $('#kategori').val();
            op1 = $('#op1').val();
            op2 = $('#op2').val();
            op3 = $('#op3').val();
            a_op1 = $('#a_op1').val();
            a_op2 = $('#a_op2').val();
            a_op3 = $('#a_op3').val();
            anes = $('#anes').val();
            a_anes1 = $('#a_anes1').val();
            a_anes2 = $('#a_anes2').val();
            onloop1 = $('#onloop1').val();
            onloop2 = $('#onloop2').val();
            onloop3 = $('#onloop3').val();
            onloop4 = $('#onloop4').val();
            onloop5 = $('#onloop5').val();
            bidan1 = $('#bidan1').val();
            bidan2 = $('#bidan2').val();
            bidan3 = $('#bidan3').val();
            dr_anak = $('#dr_anak').val();
            dr_pjanak = $('#dr_pjanak').val();
            dr_umum = $('#dr_umum').val();
            p_resus = $('#p_resus').val();
            p_luar = $('#p_luar').val();
            instrumen = $('#instrumen').val();
            akomodasi = $('#akomodasi').val();
            sewa = $('#sewa').val();
            sarpras = $('#sarpras').val();
            alat = $('#alat').val();
            bagian = $('#bagian').val();
            $.ajax({
                url: 'operasi/tambah',
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
                success: function(data) {
                    toastr.info('Tarif ' + kd_jenis_prw + ' berhasil ditambah', 'Berhasil');
                    $('#tabel-tarif-operasi').DataTable().destroy();
                    load_data();
                }
            });
        }
        $('#modal-tambah').on('shown.bs.modal', function() {
            $('.hitung').val(0);
            loadPenjab();
            $.ajax({
                url: 'operasi/akhir',
                success: function(data) {
                    $("input[name='kd_jenis_prw']").val(data);
                }
            });

        });
        $('#modal-tambah').on('hidden.bs.modal', function() {
            $('select[name="pembiayaan"] option').detach();
        });
    </script>
@endpush
