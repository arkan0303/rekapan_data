$(document).ready(function () {
    const form = $('#tambah_order');
    const submit = form.find("button[type='submit'][id='submit_order']");

    form.submit(function (e) {
        e.preventDefault();
    });

    // Ajax
    submit.click(function () {
        $.ajax({
            url: 'php/tambahOrder.php',
            method: 'POST',
            data: form.serialize(),
            success: function (data) {
                window.location.reload();
            },
            error: function (error) {
                console.log(error);
            },
        });
    });
});
