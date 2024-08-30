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

document.getElementById('exportBtn').addEventListener('click', function () {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'php/exportExcel.php', true);
    xhr.responseType = 'blob'; // Set response type to blob

    xhr.onload = function () {
        if (xhr.status === 200) {
            // Create a link element to trigger the download
            const blob = xhr.response;
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);

            // Get the filename from the Content-Disposition header or use a default name
            const contentDisposition = xhr.getResponseHeader(
                'Content-Disposition'
            );
            const filename = contentDisposition
                ? contentDisposition.split('filename=')[1].replace(/"/g, '')
                : 'export.xlsx';

            link.download = filename; // Set the download attribute with the filename
            document.body.appendChild(link);
            link.click(); // Programmatically click the link to trigger the download
            document.body.removeChild(link); // Remove the link after triggering the download
        } else {
            console.error('Error:', xhr.statusText);
        }
    };

    xhr.onerror = function () {
        console.error('Request failed');
    };

    xhr.send(); // Send the request
});
