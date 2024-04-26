var table2Excel = new Table2Excel();

document.getElementById('export').addEventListener('click', function () {
    let date = new Date();
    let formattedDate = date
        .toLocaleString('id-ID', { timeZone: 'Asia/Jakarta' })
        .split(' ')[0];
    table2Excel.export(
        document.getElementById('table_export'),
        'Rekapan_data_cv_prima_multimedia_' + formattedDate
    );
});
