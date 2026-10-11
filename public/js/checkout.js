/**
 * checkout.js
 * Maneja la lógica de selección de dirección, cotización de envíos (Envia.com/DHL),
 * aplicación de cupones y procesamiento de pago con Mercado Pago.
 */

// ==========================================
// 1. VARIABLES GLOBALES Y UTILIDADES
// ==========================================
let subtotalGlobal = 0;
let costoEnvioGlobal = 0;
let descuentoGlobal = 0;
let idCuponAplicado = null; // Guardaremos el ID para enviarlo al backend

// Función para obtener el CSRF Token de Django
function getCookie(name) {
    let cookieValue = null;
    if (document.cookie && document.cookie !== '') {
        const cookies = document.cookie.split(';');
        for (let i = 0; i < cookies.length; i++) {
            const cookie = cookies[i].trim();
            if (cookie.substring(0, name.length + 1) === (name + '=')) {
                cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                break;
            }
        }
    }
    return cookieValue;
}

// ==========================================
// 2. INICIALIZACIÓN (DOM READY)
// ==========================================
document.addEventListener('DOMContentLoaded', function() {
    // A. Leer el Subtotal inicial del HTML (viene de Django)
    const elementSubtotal = document.getElementById('total-final-display');
    if (elementSubtotal) {
        // Limpiamos el signo de peso y comas para tener el número limpio
        subtotalGlobal = parseFloat(elementSubtotal.textContent.replace('$', '').replace(',', ''));
    }

    // B. Lógica de Formato de Cupón (XXX-XXX-XXX)
    const inputCupon = document.getElementById('input-cupon');
    if (inputCupon) {
        inputCupon.addEventListener('input', function(e) {
            // 1. Convertir a mayúsculas y quitar caracteres no alfanuméricos
            let valor = e.target.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
            
            // 2. Insertar guiones automáticamente en posiciones 3 y 7
            if (valor.length > 3) {
                valor = valor.slice(0, 3) + '-' + valor.slice(3);
            }
            if (valor.length > 7) {
                valor = valor.slice(0, 7) + '-' + valor.slice(7);
            }
            
            // 3. Limitar a 11 caracteres (XXX-XXX-XXX)
            e.target.value = valor.slice(0, 11);
        });
    }
});

// ==========================================
// 3. CÁLCULO DE TOTALES
// ==========================================
function recalcularTotal() {
    const displayTotal = document.getElementById('total-final-display');
    const btnPagar = document.getElementById('btn-pagar');

    // Fórmula: Subtotal (Productos) + Envío - Descuento
    let granTotal = subtotalGlobal + costoEnvioGlobal - descuentoGlobal;

    // Evitar totales negativos
    if (granTotal < 0) granTotal = 0;

    // Actualizar texto en pantalla
    if (displayTotal) {
        displayTotal.textContent = `$${granTotal.toFixed(2)}`;
    }
    
    // Actualizar botón de pago
    if (btnPagar) {
        btnPagar.textContent = `Pagar con Mercado Pago ($${granTotal.toFixed(2)})`;
    }
}

// ==========================================
// 4. LÓGICA DE ENVÍOS (DIRECCIÓN Y COTIZACIÓN)
// ==========================================

// Se llama cuando el usuario hace clic en una tarjeta de dirección
function seleccionarDireccion(elemento, id) {
    // 1. Efectos visuales (Radio buttons)
    const todas = document.querySelectorAll('.direccion__card');
    todas.forEach(card => {
        card.classList.remove('seleccionada');
        const icono = card.querySelector('.icono__check');
        if(icono) icono.innerHTML = '<div class="radio-vacio"></div>';
    });
    elemento.classList.add('seleccionada');
    const iconoActivo = elemento.querySelector('.icono__check');
    if(iconoActivo) iconoActivo.innerHTML = '<div class="radio-lleno"></div>';
    
    // 2. Guardar el ID en el input oculto
    document.getElementById('id-direccion-seleccionada').value = id;

    // 3. Preparar UI para cotización
    const contenedorEnvios = document.getElementById('contenedor-envios');
    const listaEnvios = document.getElementById('lista-opciones-envio');
    const displayEnvio = document.getElementById('costo-envio-display');
    const btnPagar = document.getElementById('btn-pagar');

    // Mostrar estado de carga
    if(contenedorEnvios) contenedorEnvios.style.display = 'block';
    if(listaEnvios) listaEnvios.innerHTML = '<p style="color:white; padding:10px;">Cotizando envío con DHL...</p>';
    if(displayEnvio) displayEnvio.textContent = "Calculando...";
    if(btnPagar) btnPagar.disabled = true; // Bloquear pago hasta tener precio

    // 4. Petición al Backend (Django -> Envia.com)
    const csrftoken = document.querySelector('meta[name="csrf-token"]').content;
    fetch('/api/calcular-envio', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrftoken },
        body: JSON.stringify({ 'id_direccion': id })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            // Dibujar las opciones que devolvió la API
            renderizarOpcionesEnvio(data.opciones);
        } else {
            // Mostrar error
            listaEnvios.innerHTML = `<p style="color:#ff6b6b;">${data.message}</p>`;
            displayEnvio.textContent = "Error";
        }
    })
    .catch(err => {
        console.error(err);
        listaEnvios.innerHTML = '<p style="color:#ff6b6b;">Error de conexión</p>';
    });
}

// Dibuja las tarjetas de paquetería (DHL Express, etc.)
function renderizarOpcionesEnvio(opciones) {
    const listaEnvios = document.getElementById('lista-opciones-envio');
    listaEnvios.innerHTML = '';

    opciones.forEach((opcion, index) => {
        // Crear tarjeta HTML
        const div = document.createElement('div');
        div.className = 'direccion__card'; 
        div.style.cursor = 'pointer';
        
        // Asignar evento click
        div.onclick = () => seleccionarPaqueteria(div, opcion);

        div.innerHTML = `
            <div class="icono__check"><div class="radio-vacio"></div></div>
            <div class="direccion__texto" style="width:100%">
                <div style="display:flex; justify-content:space-between;">
                    <strong class="direccion__nombre">${opcion.carrier.toUpperCase()}</strong>
                    <span style="color:#40ffaa; font-weight:bold;">$${opcion.totalPrice}</span>
                </div>
                <p class="direccion__detalles">
                    ${opcion.service} - Llega: ${opcion.deliveryEstimate || '3-5 días'}
                </p>
            </div>
        `;
        listaEnvios.appendChild(div);

        // Seleccionar automáticamente la primera opción (la más económica por defecto)
        if (index === 0) seleccionarPaqueteria(div, opcion);
    });
}

// Se llama cuando el usuario elige una paquetería o se selecciona la default
function seleccionarPaqueteria(elemento, datos) {
    // 1. Efecto visual
    const hijos = document.getElementById('lista-opciones-envio').children;
    for (let hijo of hijos) {
        hijo.classList.remove('seleccionada');
        hijo.querySelector('.icono__check').innerHTML = '<div class="radio-vacio"></div>';
    }
    elemento.classList.add('seleccionada');
    elemento.querySelector('.icono__check').innerHTML = '<div class="radio-lleno"></div>';

    // 2. Actualizar variable global de envío
    costoEnvioGlobal = parseFloat(datos.totalPrice);
    
    // 3. Actualizar texto de Envío en el resumen
    document.getElementById('costo-envio-display').textContent = `$${costoEnvioGlobal.toFixed(2)}`;

    // 4. Guardar en dataset del botón
    const btn = document.getElementById('btn-pagar');
    if(btn){
        btn.dataset.costoEnvio = datos.totalPrice;
        btn.dataset.servicioEnvio = datos.service;
        btn.disabled = false;
    }

    // 5. Recalcular total
    recalcularTotal();
}

// ==========================================
// 5. LÓGICA DE CUPONES
// ==========================================
function aplicarCupon() {
    const input = document.getElementById('input-cupon');
    const codigo = input.value;
    const mensaje = document.getElementById('mensaje-cupon');
    const btn = document.getElementById('btn-aplicar-cupon');

    if (!codigo) return;

    // Estado de carga
    btn.disabled = true;
    btn.textContent = "...";
    mensaje.textContent = "";

    const csrftoken = document.querySelector('meta[name="csrf-token"]').content;

    fetch('/api/validar-cupon', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrftoken },
        body: JSON.stringify({ 'codigo': codigo })
    })
    .then(res => res.json())
    .then(data => {
        btn.disabled = false;
        btn.textContent = "Aplicar";

        if (data.status === 'success') {
            // 1. Guardar valores globales
            descuentoGlobal = parseFloat(data.descuento);
            idCuponAplicado = data.id_cupon;

            // 2. Mostrar la fila de descuento en el HTML
            const filaDescuento = document.getElementById('fila-descuento');
            if(filaDescuento) {
                filaDescuento.style.display = 'flex';
                document.getElementById('codigo-aplicado').textContent = data.codigo;
                document.getElementById('monto-descuento').textContent = `- $${descuentoGlobal.toFixed(2)}`;
            }
            
            // 3. Mensaje de éxito
            mensaje.style.color = '#40ffaa';
            mensaje.textContent = "¡Cupón aplicado correctamente!";
            
            // 4. Bloquear input para que no lo cambien
            input.disabled = true; 
            btn.style.display = 'none'; 

            // 5. Recalcular Total
            recalcularTotal();

        } else {
            // Error
            mensaje.style.color = '#ff6b6b';
            mensaje.textContent = data.message;
            
            // Resetear descuento si falló
            descuentoGlobal = 0;
            idCuponAplicado = null;
            const filaDescuento = document.getElementById('fila-descuento');
            if(filaDescuento) filaDescuento.style.display = 'none';
            
            recalcularTotal();
        }
    })
    .catch(err => {
        console.error(err);
        btn.disabled = false;
        btn.textContent = "Aplicar";
        mensaje.style.color = '#ff6b6b';
        mensaje.textContent = "Error al validar cupón";
    });
}

// ==========================================
// 6. PROCESAMIENTO DE PAGO (MERCADO PAGO)
// ==========================================
function procesarPago() {
    const idDireccion = document.getElementById('id-direccion-seleccionada').value;
    const btn = document.getElementById('btn-pagar');

    // 1. Validaciones
    if (!idDireccion) {
        Swal.fire('Atención', 'Selecciona una dirección de envío.', 'warning');
        return;
    }

    // ---  OBTENER EL COSTO DE ENVÍO SELECCIONADO ---
    // Si no hay costo guardado (ej: no cargó la API), detenemos
    if (!btn.dataset.costoEnvio) {
        Swal.fire('Atención', 'Espera a que se calcule el costo de envío.', 'warning');
        return;
    }
    const costoEnvioSeleccionado = btn.dataset.costoEnvio;
    const nombreServicioEnvio = btn.dataset.servicioEnvio || "Envío Estándar";
    // -----------------------------------------------------

    // 2. Bloquear botón
    const textoOriginal = btn.textContent; 
    btn.disabled = true;
    btn.textContent = "Procesando...";

    // ==============================================================
    // TRUCO: Abrir ventana ANTES del fetch para evitar bloqueo de popups
    // ==============================================================
    const ventanaPago = window.open('', '_blank', 'width=1000,height=700');
    
    if (ventanaPago) {
        ventanaPago.document.write(`
            <div style="font-family:sans-serif; text-align:center; padding-top:50px;">
                <h2>Conectando con Mercado Pago...</h2>
                <p>Por favor no cierres esta ventana.</p>
                <div class="loader" style="border: 5px solid #f3f3f3; border-top: 5px solid #3498db; border-radius: 50%; width: 50px; height: 50px; animation: spin 1s linear infinite; margin: 20px auto;"></div>
                <style>@keyframes spin {0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); }}</style>
            </div>
        `);
    } else {
        Swal.fire('Error', 'Tu navegador bloqueó la ventana emergente. Permite los pop-ups para pagar.', 'error');
        btn.disabled = false;
        btn.textContent = textoOriginal;
        return;
    }

    const csrftoken = document.querySelector('meta[name="csrf-token"]').content;

    // 3. Petición al Backend
    fetch('/api/procesar-pedido', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrftoken
        },
        body: JSON.stringify({
            'id_direccion': idDireccion,
            'id_cupon': idCuponAplicado, // Enviamos el cupón si existe
            // --- ENVIAMOS EL COSTO EXACTO ---
            'costo_envio': costoEnvioSeleccionado,
            'servicio_envio': nombreServicioEnvio
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'redirect') {
            // A. Redirigimos la ventana emergente a Mercado Pago
            ventanaPago.location.href = data.url; 

            // B. En tu página original mostramos la alerta que pediste
            Swal.fire({
                title: 'Completando pago',
                text: 'Se abrió una ventana de Mercado Pago. ¿Ya finalizaste tu compra?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, ya pagué',
                cancelButtonText: 'Seguir comprando / Cerrar',
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si dice que ya pagó, lo mandamos a verificar la orden
                    window.location.href = "/pago-exitoso/" + data.external_reference;
                } else {
                    // Si dice seguir comprando, solo cerramos la alerta y restauramos el botón
                    btn.disabled = false;
                    btn.textContent = textoOriginal;
                }
            });

        } else {
            // Error en backend
            ventanaPago.close();
            Swal.fire('Error', data.message, 'error');
            btn.disabled = false;
            btn.textContent = textoOriginal;
        }
    })
    .catch(err => {
        console.error(err);
        if(ventanaPago) ventanaPago.close();
        Swal.fire('Error', 'Error de conexión', 'error');
        btn.disabled = false;
        btn.textContent = textoOriginal;
    });
}

// ==========================================
// 7. LÓGICA DEL MODAL DE NUEVA DIRECCIÓN
// ==========================================
const modal = document.getElementById('modalDireccion');

function abrirModalDireccion() {
    if(modal) modal.classList.add('activo');
}

function cerrarModalDireccion() {
    if(modal) modal.classList.remove('activo');
}

if(modal) {
    modal.addEventListener('click', (e) => {
        if (e.target === modal) cerrarModalDireccion();
    });
}

// Envío del formulario de nueva dirección
const formDireccion = document.getElementById('formNuevaDireccion');
if(formDireccion) {
    formDireccion.addEventListener('submit', function(e) {
        e.preventDefault(); 

        const formData = new FormData(this);
        const datos = Object.fromEntries(formData.entries());
        const csrftoken = document.querySelector('meta[name="csrf-token"]').content;

        fetch('/api/nueva-direccion', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrftoken },
            body: JSON.stringify(datos)
        })
        .then(res => {
            if (!res.ok) throw new Error("Error al guardar");
            return res.json();
        })
        .then(data => {
            if (data.status === 'success') {
                cerrarModalDireccion();
                actualizarVistaDireccion(data);
                Swal.fire({
                    icon: 'success',
                    title: 'Guardado',
                    text: 'Dirección agregada correctamente',
                    timer: 1500,
                    showConfirmButton: false
                });
                formDireccion.reset();
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'No se pudo guardar la dirección', 'error');
        });
    });
}

// Agrega la nueva dirección a la lista visualmente sin recargar
function actualizarVistaDireccion(data) {
    const contenedorLista = document.getElementById('lista-direcciones');
    const mensajeVacio = document.getElementById('mensaje-sin-dir');
    
    if(mensajeVacio) mensajeVacio.style.display = 'none';

    const nuevaTarjeta = document.createElement('div');
    nuevaTarjeta.className = 'direccion__card'; 
    nuevaTarjeta.onclick = function() { seleccionarDireccion(this, data.id); };

    nuevaTarjeta.innerHTML = `
        <div class="icono__check">○</div>
        <div class="direccion__texto">
            <p class="direccion__nombre">
                ${data.street} #${data.number}
            </p>
            <p class="direccion__detalles">
                Col. ${data.colony}<br>
                CP: ${data.zip} - ${data.city}, ${data.state}
            </p>
        </div>
    `;

    contenedorLista.appendChild(nuevaTarjeta);
    
    // Seleccionarla automáticamente
    seleccionarDireccion(nuevaTarjeta, data.id);
}