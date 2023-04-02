jQuery(document).ready(function($) {
    $('#contact-form').submit(function(event) {
        event.preventDefault();
        alert("We have received your message. You'll hear from us soon.");
        $.ajax({
            url: "https://wlem42r1c3.execute-api.us-east-1.amazonaws.com/default",
            type: "post",
            crossDomain: true,
            data: JSON.stringify({ name: $('#name').val(),
                    email: $('#email').val(),
                    phone: $('#phone').val(),
                    message: $('#message').val(),
                    to_email: 'info@MahaPetro.com'
                  }),
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            headers: {
                          "accept": "application/json",
                          "Access-Control-Allow-Origin":"*",
                          "Content-Type": "application/json"
                      },
            success: function (data, status) {
                console.log(status);
                if (status == "success") {
                    // alert("We have received your message. You'll hear from us soon.");
                    $('#contact-form').trigger("reset");
                }
                else {
                    alert("Oops! Something went wrong while sending your message.");
                }
            }

        });
    });
});      