

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
