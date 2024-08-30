document.getElementById('exportBtn').addEventListener('click', function () {
    // Send request to the PHP script
    fetch('export_excel.php', {
        method: 'GET',
    })
        .then((response) => response.blob()) // Convert the response to a blob
        .then((blob) => {
            // Create a link element to trigger the download
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);

            // Get the filename from the response headers or use a default name
            const filename =
                response.headers
                    .get('Content-Disposition')
                    .split('filename=')[1] || 'export.xlsx';

            link.download = filename.replace(/"/g, ''); // Set the download attribute with the filename
            document.body.appendChild(link);
            link.click(); // Programmatically click the link to trigger the download
            document.body.removeChild(link); // Remove the link after triggering the download
        })
        .catch((error) => {
            console.error('Error:', error);
        });
});
