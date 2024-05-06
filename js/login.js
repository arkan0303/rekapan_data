const form = document.getElementById('form_login');
const submit = form.querySelector("button[type='submit']");
const loading = form.querySelector('button.loading');
const errorMsg = document.querySelector('.alert');

form.onsubmit = (e) => {
    e.preventDefault();
};

submit.onclick = () => {
    console.log('clicked');
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'php/login.php', true);
    xhr.onload = () => {
        if (xhr.readyState === XMLHttpRequest.LOADING) {
            loading.classList.remove('d-none');
            submit.classList.add('d-none');
            errorMsg.classList.add('d-none');
        }
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.response === 'success') {
                errorMsg.classList.add('d-none');
                location.href = '/';
            } else {
                loading.classList.add('d-none');
                submit.classList.remove('d-none');
                errorMsg.classList.remove('d-none');
                errorMsg.innerHTML = `<strong>${xhr.response}</strong>
                `;
            }
        }
    };

    let formData = new FormData(form);
    xhr.send(formData);
};
