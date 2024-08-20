function deleteProduct(button) {
    const id = button.getAttribute('data-id');

    let xhr = new XMLHttpRequest();
    xhr.open('GET', 'php/hapusProduk.php?id=' + id, true);
    xhr.onload = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            const statusMessage = document.getElementById('status_message');
            statusMessage.classList.remove('d-none');
            statusMessage.innerHTML = `<strong>${xhr.response}</strong>`;
            window.scrollTo(0, 0);
            if (xhr.response.toLowerCase().includes('berhasil')) {
                statusMessage.classList.add('alert-success');
            } else {
                statusMessage.classList.add('alert-danger');
            }
            setTimeout(() => {
                location.href = '/';
            }, 2000);
        }
    };

    xhr.onerror = function () {
        console.error('An error occurred while processing the request.');
    };

    xhr.send();
}
