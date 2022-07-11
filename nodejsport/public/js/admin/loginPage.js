var formData;
var formOutput;
var url = new URL(window.location.href);

$(document).ready(function() {
    $("#loginForm").submit(function(event) {
        event.preventDefault();
        
        $('.loadingGif').fadeIn(100);
        formData = $("#loginForm").serialize();

        fetch("../../api/admin/login", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                authToken: $("#authToken").val(),
                usernameEmail: $("#usernameEmail").val(),
                password: $("#password").val()
            })
        })
        .then(res => res.json())
        .then(res => {
            console.log(res);

            var status = res.status;
            var errorType = res.errorType;
            var errorMessage = res.errorMessage;

            var formOutput = res.errorMessage;

            clearFormErrors();

                if (status = 'success') {
                    window.location.replace('./overview');
                } else {
                    switch (errorType) {
                        case 'noUsernameEmail':
                            showFormError("#noUsernameEmailError", '#usernameEmail');
                            $("#usernameEmail").shake(50);
                            break;
                        case 'noMatch':
                            showFormError("#noMatchError", '#password');
                            $("#password").shake(50);
                            break;
                        default:
                            showFormError("#"+formOutput+"Error", "#"+formOutput);
                            $("#"+formOutput).shake(50);
                            break;
                    }
                }

                

                $('.loadingGif').fadeOut(100);
        });
    });
});
