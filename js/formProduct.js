let submitUrl = "";
    let productId = "";

    document.addEventListener('DOMContentLoaded', function () {
        // Initialize inputmask with Rupiah format
        Inputmask('numeric', {
            radixPoint: '.',
            groupSeparator: ',',
            digits: 2,
            autoGroup: true,
            prefix: '',
            rightAlign: false,
        }).mask(document.getElementById('harga'));
    });
    const params = new URLSearchParams(window.location.search);    

    let hasId = params.has("id") && params.get("id") !== ""

    if (hasId) {
        submitUrl = "php/ubahProduk.php";
        productId = params.get("id");
    } else {
        submitUrl = "php/tambahProduk.php";
    }

const form = document.getElementById('form_product');

const inputGamabr = form.querySelector("input[type='file'][name='gambar']");
const buttonSave = form.querySelector("button[type='submit']");
const statusMesage = document.getElementById('status_message');

if (productId === '') {
    const buttonReset = form.querySelector("button[type='reset']");
    buttonReset.addEventListener('click', resetImagePreview);
}

form.onsubmit = (e) => {
    e.preventDefault();
};

inputGamabr.addEventListener('change', previewImage);
buttonSave.addEventListener('click', saveProduct);

function previewImage(event) {
    const fileInput = event.target;
    const preview = document.getElementById('preview');

    if (fileInput.files && fileInput.files[0]) {
        const reader = new FileReader();

        reader.onload = function (e) {
            preview.src = e.target.result;
            preview.classList.remove('d-none');
        };

        reader.readAsDataURL(fileInput.files[0]);
    }
}

function resetImagePreview() {
    const preview = document.getElementById('preview');
    preview.src = '';
    preview.classList.add('d-none');
}

function saveProduct() {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', submitUrl, true);
    xhr.onload = function () {
        if (xhr.readyState === XMLHttpRequest.LOADING) {
            statusMesage.classList.add('d-none');
            buttonSave.disabled = true;
            if (!hasId) buttonReset.disabled = true;
            buttonSave.textContent = 'Memproses...';
        }

        if (xhr.readyState === XMLHttpRequest.DONE) {
            let data = xhr.response;
            console.log(xhr.response.toLowerCase().includes('berhasil'));
            if (data.toLowerCase().includes('berhasil')) {
                window.scrollTo(0, 0);
                statusMesage.classList.remove('d-none');
                statusMesage.classList.add('alert-success');
                statusMesage.classList.remove('alert-danger');
                statusMesage.innerHTML = `<strong>${data}</strong>`;
                buttonSave.disabled = true;
                if (!hasId) buttonReset.disabled = true;
                form.querySelectorAll('input, textarea').forEach(
                    (el) => (el.disabled = true)
                );
                setTimeout(() => {
                    location.href = '/';
                }, 2000);
            } else {
                statusMesage.classList.remove('d-none');
                statusMesage.classList.add('alert-danger');
                statusMesage.innerHTML = `<strong>${data}</strong>`;

                buttonSave.disabled = false;
                buttonSave.textContent = 'Simpan';

                if (!hasId) {
                    buttonReset.disabled = false;
                }
            }
        }
    };

    let formData = new FormData(form);
    if (hasId) {
        formData.append('productId', parseInt(productId));
    }
    xhr.send(formData);
}
