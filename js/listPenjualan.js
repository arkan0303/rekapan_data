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

function exportExcel() {
    const table = document.getElementById('table_penjualan');

    let payload = [];

    const tanggalOrder = table.querySelectorAll(
        "tbody td[data-column='tanggal_order']"
    );
    const namaPembeli = table.querySelectorAll(
        "tbody td[data-column='nama_pembeli']"
    );
    table
        .querySelectorAll("tbody td[data-column='total_harga']")
        .forEach((el, index) => {
            const data = {
                namaPembeli: namaPembeli[index].textContent,
                tanggalOrder: tanggalOrder[index].textContent,
                totalHarga: el.textContent,
            };

            payload.push(data);
        });

    const worksheet = XLSX.utils.json_to_sheet(payload);
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, 'Data Penjualan');

    worksheet['A1'].s = {
        font: { bold: true },
        alignment: { horizontal: 'center', vertical: 'middle' },
    };
    worksheet['B1'].s = {
        font: { bold: true },
        alignment: { horizontal: 'center', vertical: 'middle' },
    };
    worksheet['C1'].s = {
        font: { bold: true },
        alignment: { horizontal: 'center', vertical: 'middle' },
    };

    const columnWidths = payload.map((row) => [
        row.namaPembeli.length,
        row.tanggalOrder.length,
        row.totalHarga.length,
    ]);
    const maxColumnWidths = columnWidths.reduce(
        (acc, curr) => [
            Math.max(acc[0], curr[0]),
            Math.max(acc[1], curr[1]),
            Math.max(acc[2], curr[2]),
        ],
        [0, 0, 0]
    );

    worksheet['!cols'] = maxColumnWidths.map((width) => ({ wch: width }));

    XLSX.utils.sheet_add_aoa(
        worksheet,
        [['Nama Pembeli', 'Tanggal Order', 'Total Harga']],
        { origin: 'A1' }
    );

    const filename = 'RekapanData' + new Date().toISOString() + '.xlsx';
    XLSX.writeFile(workbook, filename, { compression: true });
}

document.getElementById('export_excel').addEventListener('click', exportExcel);
