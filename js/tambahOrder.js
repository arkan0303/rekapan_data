$(document).ready(function () {
    const form = $('form');
    const submit = form.find("button[type='submit']");

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
