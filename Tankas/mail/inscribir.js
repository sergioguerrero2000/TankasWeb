
boton= document.getElementById('inscribete');
boton.addEventListener("click", function(){ 
    var email= document.getElementById("email_cliente").value;
    if(email!==""){
        $.ajax({
            url: "mail/inscribir.php",
            type: "POST",
            data: {
                email: email
            },
                success: function(response) {
                    // Manejar la respuesta del servidor si es necesario
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    // Mostrar un mensaje de error al usuario si es necesario
                }
            });
    }
    
});