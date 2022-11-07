
function hanyaAngka(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))

        return false;
    return true;
}
function removeZero(input) {
    if (input.value == '0') {
        $(input).val('');
    }
}
function cekKosong(input) {
    if (input.value == '') {
        $(input).val(0);
    }
}

$('#tanggalPembiayaan').daterangepicker({
    locale: {
        language: 'id',
        applyLabel: 'Terapkan',
        cancelLabel: 'Batal',
        format: 'DD/MM/YYYY',
        daysOfWeek: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Ming'],
        monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
    },
    startDate: moment().startOf('month'),
    autoclose: true,
    showDropdowns: true,
    minYear: 2019,
});

$('#tanggal').daterangepicker({
    locale: {
        language: 'id',
        applyLabel: 'Terapkan',
        cancelLabel: 'Batal',
        format: 'DD/MM/YYYY',
        daysOfWeek: ['Ming', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
        monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
    },
    startDate: moment().startOf('month'),
    autoclose: true,
    showDropdowns: true,
    minYear: 2019,
});

function dateRange(id) {
    $(id).daterangepicker({
        locale: {
            language: 'id',
            applyLabel: 'Terapkan',
            cancelLabel: 'Batal',
            format: 'DD/MM/YYYY',
            daysOfWeek: ['Ming', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
            monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
        },
        startDate: moment().startOf('month'),
        autoclose: true,
        showDropdowns: true,
        minYear: 2019,
    });
}
function cekTanggal() {
    if (tgl_pertama == '' && tgl_kedua == '') {

        var hari1 = moment().startOf('month').date();
        var hari2 = moment().endOf('month').date();
        var bulan = moment().month() + 1;
        var tahun = moment().startOf('month').year();


        tgl_pertama = tahun + '-' + bulan + '-' + hari1;
        tgl_kedua = tahun + '-' + bulan + '-' + hari2;

    }
}

function cekDaftar() {
    if ($('#daftar').val() == '') {
        daftar = '';
    }
    return daftar;
}

function cekDiagnosa() {
    if ($('#diagnosa').val() == '') {
        diagnosa = '';
    }
    return diagnosa;
}

function cekStatus() {

    if ($('#ralan').is(":checked")) {
        status = 'ralan';
    } else if ($('#ranap').is(":checked")) {
        status = 'ranap';
    } else {
        status = '';
    }
    return status;
}
function loadPenjab() {
    $.ajax({
        url: '/dms/penjab',
        type: "GET",
        dataType: "json",
        success: function (data) {
            $.each(data, function (_key, pembiayaan) {
                $('select[name="pembiayaan"]').append('<option value="' + pembiayaan.kd_pj + '">' + pembiayaan.png_jawab + '</option>');
            });
        }
    })
}
function loadPoli(param) {
    $.ajax({
        url: '/dms/poli',
        type: "GET",
        dataType: "json",
        success: function (data) {
            $.each(data, function (key, poli) {
                $('select[name="' + param + '"]').append('<option value="' + poli.kd_poli + '">' + poli.nm_poli + '</option>');
            });
        }
    })
}
function kategoriPerawatan(param, param2, attr) {
    $(param).keyup(function () {
        var value = $(this).val();
        if (value != '') {
            $.ajax({
                url: 'kategori/' + value + '/' + attr,
                method: "GET",
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                success: function (data) {
                    $(param2).html(data);
                    $(param2).fadeIn();
                }
            });
        }
    });


    $(param).blur(function () {
        if ($(param).val().length == 0) {
            $(param).val(kategori);
        }

    })
}

