document.addEventListener('DOMContentLoaded', function() {
        // Seleccionamos todos los títulos de los filtros
        const titulos = document.querySelectorAll('.filtro__titulo');

        titulos.forEach(titulo => {
            titulo.addEventListener('click', function() {
                // Encontramos el padre (.filtro__seccion)
                const seccion = this.parentElement;
                
                // Le quitamos o ponemos la clase 'cerrado'
                seccion.classList.toggle('cerrado');
            });
        });
    });
