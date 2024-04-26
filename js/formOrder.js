$(document).ready(function () {
    /* Fungsi untuk memastikan user hanya bisa input qty dengan nilai lebih dari 1 */
    formatQtyOnInput();

    /* Fungsi untuk memformat input ke dalam rupiah */
    formatRupiah();
});

function formatQtyOnInput() {
    $('#qty').on('input', function () {
        var value = $(this).val();
        if (parseInt(value) < 1) {
            $(this).val('1');
        }
    });
}

function formatRupiah() {
    $('#total_harga').on('input', function () {
        var angka = $(this).val();
        var number_string = angka.replace(/[^\d]/g, '').toString(),
            split = number_string.split('.'),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        // tambahkan koma jika yang di input sudah menjadi angka ribuan
        if (ribuan) {
            separator = sisa ? ',' : '';
            rupiah += separator + ribuan.join(',');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        $(this).val(rupiah);
    });
}
