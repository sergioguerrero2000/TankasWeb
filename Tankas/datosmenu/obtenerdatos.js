document.addEventListener("DOMContentLoaded", function() {
    // Realizar una solicitud AJAX para obtener los datos del servidor
    fetch('datosmenu/obtenerdatos.php')
        .then(response => response.json())
        .then(data => mostrarProductosEnMenu(data))
        .catch(error => console.error('Error al obtener los datos:', error));
});
function mostrarProductosEnMenu(data) {
    // Obtener el elemento del acordeón
    var accordion = document.getElementById('accordion');

    // Iterar sobre cada familia de productos
    data.forEach(function(familia) {
        // Crear un elemento de tarjeta
        var card = document.createElement('div');
        card.className = 'card';

        // Crear el encabezado de la tarjeta
        var cardHeader = document.createElement('div');
        cardHeader.className = 'card-header';
        cardHeader.innerHTML = '<h5 class="mb-0 text-center"><button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse_' + familia.id + '" aria-expanded="false" aria-controls="collapse_' + familia.id + '">' + familia.nombre + '</button></h5>';

        // Crear el cuerpo de la tarjeta
        var collapse = document.createElement('div');
        collapse.id = 'collapse_' + familia.id;
        collapse.className = 'collapse';
        collapse.setAttribute('aria-labelledby', 'heading_' + familia.id);
        collapse.setAttribute('data-parent', '#accordion');

        var cardBody = document.createElement('div');
        cardBody.className = 'card-body';
        cardBody.style.color = 'white';
        cardBody.style.textAlign = 'left';

        // Crear la lista de productos para la familia actual
        var listaProductos = '<ul>';

        // Si el ID de la familia es 1, agregar el mensaje "Elija su pan"
        if (familia.id == 1) {
            listaProductos += '<p>Elija el pan que mas le guste: </p>';
            listaProductos += '<li> Antequera, chapata, gallega, integral, multicereales......... 0,50€</li>';
            listaProductos += '<li> 100% integral, curcuma, pasas y nueces, pan de pueblo, sin gluten......... 1,00€</li>';
            listaProductos += '<p>Ahora elija los ingredientes: </p>';
        }

        // Agregar los productos de la familia actual
        familia.articulos.forEach(function(articulo) {
            listaProductos += '<li>' + articulo.nombre + ' ............ ' + articulo.precio + '€ </li>';
        });

        listaProductos += '</ul>';
        cardBody.innerHTML = listaProductos;

        // Agregar el encabezado y el cuerpo de la tarjeta al elemento de la tarjeta
        card.appendChild(cardHeader);
        card.appendChild(collapse);
        collapse.appendChild(cardBody);

        // Agregar la tarjeta al acordeón
        accordion.appendChild(card);
    });
}
