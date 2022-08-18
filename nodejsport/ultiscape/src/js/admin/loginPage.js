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
            var errors = res.errors;

            clearFormErrors();

            if (status == 'success') {
                window.location.assign('./overview');
            } else {

                // Use reverse in order to focus the first input with an error rather than the last
                errors.reverse().forEach((error) => {
                    if (error.type == 'general') {
                        // Display the general error
                    } else {
                        // Show the form error type
                        $("#" + error.type + "Error").html(error.msg);
                        showFormError("#" + error.type + "Error", "#" + error.type);
                        $("#" + error.type).shake(50);
                    }
                });
            }

                $('.loadingGif').fadeOut(100);
        });
    });
});
