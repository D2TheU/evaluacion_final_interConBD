$(function() {
    var l = new Login();
})

class Login {
    constructor() {
        this.submitEvent()
    }
    submitEvent() {
        $('form').submit((event) => {
            event.preventDefault()
            this.sendForm()
        })
    }
    sendForm() {
        let form_data = new FormData();
        form_data.append('username', $('#user').val())
        form_data.append('password', $('#password').val())
        $.ajax({
            url: '../server/check_login.php',
            dataType: "json",
            cache: false,
            processData: false,
            contentType: false,
            data: form_data,
            type: 'POST',
            success: function(php_response) {
                if (php_response.result == "ok") {
                    window.location.href = 'main.php';
                } else {
                    alert(php_response.message);
                }
            },
            error: function() {
                alert("error en la comunicación con el servidor");
            }
        })
    }
}
