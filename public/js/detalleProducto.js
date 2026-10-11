document.addEventListener('DOMContentLoaded', function() {
    const tallaItems = document.querySelectorAll('.numero__item');
    const inputVariante = document.getElementById('variante-seleccionada');
    const botonCarrito = document.querySelector('.btn__agregar_carrito');

    // Inicialización
    if (botonCarrito) {
        botonCarrito.disabled = true;
        botonCarrito.textContent = 'Selecciona una talla';
    } else {
        console.error('ERROR: No se encontró el botón de carrito.');
        return;
    }
    if (!inputVariante) {
        console.error('ERROR: No se encontró el input oculto de la variante.');
        return;
    }

    tallaItems.forEach(item => {
        const stock = parseInt(item.dataset.stock) || 0;

        if (stock <= 0) {
            item.classList.add('sin-stock');
            return;
        }

        item.addEventListener('click', function() {
            const varianteId = this.dataset.varianteId;

            // Quitar activo
            tallaItems.forEach(i => i.classList.remove('activo'));

            // Activar talla seleccionada
            this.classList.add('activo');

            // Guardar variante seleccionada
            inputVariante.value = varianteId;

            // Habilitar botón
            botonCarrito.disabled = false;
            botonCarrito.textContent = 'Añadir al Carrito +';
        });
    });
});


// ----------------------------
// ENVÍO DE DATOS AL CARRITO (AJAX)
// ----------------------------
document.addEventListener('DOMContentLoaded', function() {

    const formulario = document.querySelector('.form__carrito');
    if (!formulario) return;

    formulario.addEventListener('submit', function(event){
        event.preventDefault();  // 1. Evitamos que el formulario recargue la página

        const inputVariante = document.getElementById('variante-seleccionada');
        const varianteId = inputVariante.value;
        
        if(!varianteId) {
            Swal.fire({
                icon: 'warning',
                title: 'Falta la talla',
                text: 'Por favor, selecciona una talla antes de agregar.',
                confirmButtonColor: '#407AD0', // Tu color azul
                confirmButtonText: 'Entendido'
            });
            return;
        }

        // 2. Preparamos datos para enviar
        const csrfToken = document.querySelector('[name=_token]').value;
        const btnSubmit = formulario.querySelector('button[type="submit"]');
        
        // Deshabilitar botón para evitar doble click
        btnSubmit.disabled = true;
        btnSubmit.textContent = "Agregando...";

        // 3. Petición AJAX a Django
        fetch('/api/agregar-carrito', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json', 
                'X-CSRF-TOKEN': csrfToken 
            },
            body: JSON.stringify({ 'variant_id': varianteId, 'cantidad': 1 })
        })
        .then(res => {
            if(res.status === 401) {
                window.location.href = "/login"; // Redirigir si no hay sesión
                throw new Error("No autenticado");
            }
            return res.json();
        })
        .then(data => {
            // Reactivar botón
            btnSubmit.disabled = false;
            btnSubmit.textContent = "Añadir al Carrito";

            if (data.status === 'success') {
                // A) Actualizar el badge del header (numerito rojo)
                const badge = document.getElementById('carritoBadge');
                if(badge) {
                    badge.textContent = data.total_items;
                    badge.style.display = 'flex';
                }

                // B) Mostrar tu animación de alerta bonita
                mostrarAlertaBonita(data);
                
                // C) Opcional: Actualizar el panel lateral si existe función global
                if(typeof cargarCarritoDesdeBD === 'function') {
                    cargarCarritoDesdeBD();
                }

            } else {
                // --- ERROR DE STOCK  ---
                Swal.fire({
                    icon: 'error',
                    title: 'Ups!',
                    text: data.message, // "Solo quedan 3 disponibles..."
                    confirmButtonColor: '#000000', // Negro para errores o tu azul
                    confirmButtonText: 'Cerrar'
                });
            }
        })
        .catch(error => {
            console.error(error);
            btnSubmit.disabled = false;
            btnSubmit.textContent = "Añadir al Carrito";
        });
    });

    // Función auxiliar para tu animación (extraída de tu código original)
    function mostrarAlertaBonita(data) {
        const alerta = document.getElementById('alerta-carrito');
        const img = document.getElementById('alerta-img');
        const nombre = document.getElementById('alerta-nombre');
        const talla = document.getElementById('alerta-talla');

        if(img) img.src = data.producto_img;
        if(nombre) nombre.textContent = data.producto_nombre;
        if(talla) talla.textContent = "Talla: " + data.producto_talla;

        // Mostrar alerta
        alerta.style.display = "block";
        // Pequeño hack para que la transición CSS funcione
        setTimeout(() => {
            alerta.style.opacity = "1";
            alerta.style.visibility = "visible";
            alerta.style.transform = "translateX(0)";
        }, 10);

        // Ocultar después de 3s
        setTimeout(() => {
            alerta.style.opacity = "0";
            alerta.style.transform = "translateX(100%)";
            setTimeout(() => alerta.style.display = "none", 400);
        }, 3000);
    }
});