document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.money_format').forEach((el) => {
        let angka = el.textContent;

        // Remove non-digit characters except for the dot
        let number_string = angka.replace(/[^\d.]/g, '');

        // Split the number into integer and decimal parts
        let split = number_string.split('.');

        // Get the integer part
        let rupiah = split[0];

        // Format the integer part with comma as thousands separator
        rupiah = rupiah.replace(/\B(?=(\d{3})+(?!\d))/g, ',');

        // Combine the integer part with the decimal part (if exists)
        rupiah =
            split.length > 1
                ? 'Rp. ' + rupiah + ',' + split[1]
                : 'Rp. ' + rupiah;

        // Update the element with the formatted value
        el.textContent = rupiah;
    });
});
