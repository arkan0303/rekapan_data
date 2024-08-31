document.getElementById('exportBtn').addEventListener('click', function () {
    // Create an XMLHttpRequest object
    const xhr = new XMLHttpRequest();

    // Configure it: GET-request for the URL /export_excel.php
    xhr.open('GET', 'php/exportExcel.php', true);
    xhr.responseType = 'blob'; // Expect a binary response (blob)

    // Set up a callback function to handle the response
    xhr.onload = function () {
        if (xhr.status === 200) {
            // Create a link element to trigger the download
            const link = document.createElement('a');
            link.href = URL.createObjectURL(xhr.response);

            // Get the filename from the response headers or use a default name
            const disposition = xhr.getResponseHeader('Content-Disposition');
            let filename = 'export.xlsx'; // Default filename
            if (disposition && disposition.includes('filename=')) {
                filename = disposition.split('filename=')[1].replace(/"/g, '');
            }

            link.download = filename; // Set the download attribute with the filename
            document.body.appendChild(link);
            link.click(); // Programmatically click the link to trigger the download
            document.body.removeChild(link); // Remove the link after triggering the download
        } else {
            console.error('Error:', xhr.statusText);
        }
    };

    // Handle network errors
    xhr.onerror = function () {
        console.error('Network error');
    };

    // Send the request
    xhr.send();
});
