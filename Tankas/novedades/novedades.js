document.addEventListener('DOMContentLoaded', () => {
    fetch('novedades/obtenernovedades.php')
        .then(response => response.json())
        .then(data => {
            
            const novedadesContainer = document.getElementById('novedades');
            data.forEach(novedad => {
                const novedadElement = document.createElement('div');
                novedadElement.classList.add('col-lg-6', 'mb-5');

                novedadElement.innerHTML = `
                    <div class="row align-items-center">
                        <div class="col-sm-5">
                            <img class="img-fluid mb-3 mb-sm-0" src="${'img/'+novedad.imagen}" alt="">
                        </div>
                        <div class="col-sm-7">
                            <h4>${novedad.titulo}</h4>
                            <p class="m-0">${novedad.descripcion}</p>
                        </div>
                    </div>
                `;
                novedadesContainer.appendChild(novedadElement);
            });
        })
        .catch(error => console.error('Error al cargar las novedades:', error));
});