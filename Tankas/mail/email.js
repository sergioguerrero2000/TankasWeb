$(function() {
    $("#contactForm").submit(function(event) {
        event.preventDefault();

        var formData = $(this).serialize();

        $.ajax({
            url: "send_email.php",
            type: "POST",
            data: formData,
            success: function(response) {
                // Manejar la respuesta del servidor si es necesario
                console.log(response);
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                // Mostrar un mensaje de error al usuario si es necesario
            }
        });
    });
});

