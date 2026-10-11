document.addEventListener('DOMContentLoaded', function() {

    // --- VARIABLES DOM ---
    const btnPerfil = document.getElementById('btnPerfil');
    const menuPerfil = document.getElementById('menuPerfil');
    const btnBuscar = document.getElementById('btnBuscar');
    const buscador = document.getElementById('buscador');
    
    // Variables del Carrito
    const btnCarrito = document.getElementById('btnCarrito');
    const carritoPanel = document.getElementById('carritoPanel');
    const carritoOverlay = document.getElementById('carritoOverlay');
    const cerrarCarritoBtn = document.getElementById('cerrarCarrito');

    // 1. PERFIL Y BUSCADOR (Tu lógica existente)
    if (btnPerfil && menuPerfil) {
        btnPerfil.addEventListener('click', function(e) {
            e.preventDefault(); e.stopPropagation();
            menuPerfil.classList.toggle('activo');
            if (buscador) buscador.classList.remove('activo');
        });
    }
    if (btnBuscar && buscador) {
        btnBuscar.addEventListener('click', function(e) {
            e.preventDefault(); e.stopPropagation();
            buscador.classList.toggle('activo');
            if (menuPerfil) menuPerfil.classList.remove('activo');
        });
    }
    document.addEventListener('click', function(e) {
        if (menuPerfil && menuPerfil.classList.contains('activo')) {
            if (!menuPerfil.contains(e.target) && !btnPerfil.contains(e.target)) menuPerfil.classList.remove('activo');
        }
        if (buscador && buscador.classList.contains('activo')) {
            if (!buscador.contains(e.target) && !btnBuscar.contains(e.target)) buscador.classList.remove('activo');
        }
    });

    // ==========================================
    //       2. LÓGICA DEL CARRITO (AJAX)
    // ==========================================

    // ABRIR CARRITO Y CARGAR DATOS
    if (btnCarrito) {
        btnCarrito.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            carritoPanel.classList.add('activo');
            carritoOverlay.classList.add('activo');
            
            // Cargar productos desde la base de datos al abrir
            cargarCarritoDesdeBD(); 
        });
    }

    // CERRAR CARRITO
    if (cerrarCarritoBtn) cerrarCarritoBtn.addEventListener('click', cerrarPanelCarrito);
    if (carritoOverlay) carritoOverlay.addEventListener('click', cerrarPanelCarrito);

    function cerrarPanelCarrito() {
        if(carritoPanel) carritoPanel.classList.remove('activo');
        if(carritoOverlay) carritoOverlay.classList.remove('activo');
    }

    // --- FUNCIÓN 1: OBTENER DATOS DE DJANGO ---
    function cargarCarritoDesdeBD() {
        fetch('/api/obtener-carrito')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                renderizarCarrito(data.items, data.total);
            }
        })
        .catch(error => console.error('Error cargando carrito:', error));
    }

    // --- FUNCIÓN 2: DIBUJAR HTML EN EL PANEL ---
function renderizarCarrito(items, total) {
        const contenido = document.getElementById('carritoContenido');
        const footer = document.getElementById('carritoFooter');
        const badge = document.getElementById('carritoBadge');
        
        // Actualizar Badge
        let totalCantidad = items.reduce((sum, item) => sum + item.cantidad, 0);
        if (badge) {
            badge.textContent = totalCantidad;
            badge.style.display = totalCantidad > 0 ? 'flex' : 'none';
        }

        if (items.length === 0) {
            if(footer) footer.style.display = 'none';
            contenido.innerHTML = `<div class="carrito-vacio" style="text-align:center; padding: 2rem;"><p>Tu carrito está vacío</p></div>`;
            return;
        }

        if(footer) footer.style.display = 'block';
        
        let html = '';
        items.forEach(item => {
            html += `
                <div class="producto-carrito">
                    <div class="producto-carrito__img">
                        <img src="${item.imagen}" alt="${item.nombre}" style="width:60px; height:60px; object-fit:cover; border-radius:8px;">
                    </div>
                    <div class="producto-carrito__info">
                        <div class="producto-carrito__nombre">${item.nombre}</div>
                        <div class="producto-carrito__talla" style="font-size:0.8rem; color:#666;">Talla: ${item.talla} | Color: ${item.color}</div>
                        <div class="producto-carrito__precio">$${item.precio.toLocaleString()}</div>
                        
                        <div class="producto-carrito__controles">
                            <div class="controles-cantidad">
                                <button class="btn-cantidad" onclick="cambiarCantidad(${item.id_detalle}, 'restar')">-</button>
                                <span class="cantidad-texto">${item.cantidad}</span>
                                <button class="btn-cantidad" onclick="cambiarCantidad(${item.id_detalle}, 'sumar')">+</button>
                            </div>
                            <button class="btn-eliminar" onclick="eliminarProducto(${item.id_detalle})">Eliminar</button>
                        </div>
                    </div>
                </div>
            `;
        });

        contenido.innerHTML = html;
        const totalElem = document.getElementById('totalCarrito');
        if(totalElem) totalElem.textContent = `$${total.toLocaleString()}`;
    }

    // --- FUNCIÓN 3: AGREGAR (Global para el HTML) ---
    window.agregarAlCarrito = function(e, variantId) {
        if(e) { e.preventDefault(); e.stopPropagation(); }

        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        fetch('/api/agregar-carrito', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ 'variant_id': variantId, 'cantidad': 1 })
        })
        .then(res => {
            if(res.status === 401) { window.location.href = "/login"; return null; }
            return res.json();
        })
        .then(data => {
            if (data && data.status === 'success') {
                // 1. Actualizar el numerito rojo
                const badge = document.getElementById('carritoBadge');
                if(badge) {
                    badge.textContent = data.total_items;
                    badge.style.display = 'flex';
                }

                // 2. Mostrar la alerta (FIXED)
                mostrarAlerta(data);

                // 3. (Opcional) Recargar el contenido del panel por si lo abre después
                cargarCarritoDesdeBD();
            }
        });
    };

    window.cambiarCantidad = function(idDetalle, accion) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        fetch('/api/actualizar-carrito', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ 
                'id_detalle': idDetalle, 
                'accion': accion // 'sumar' o 'restar'
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                cargarCarritoDesdeBD(); // Recargamos el carrito para ver los cambios
            }
        });
    };

    window.eliminarProducto = function(idDetalle) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        if(!confirm("¿Eliminar producto?")) return;

        fetch('/api/actualizar-carrito', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ 
                'id_detalle': idDetalle, 
                'accion': 'eliminar' 
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                cargarCarritoDesdeBD();
            }
        });
    };

    // --- FUNCIÓN 4: MOSTRAR ALERTA (ARREGLADA) ---
    function mostrarAlerta(data) {
        const alerta = document.getElementById('alerta-carrito');
        if (!alerta) {
            console.error("No se encontró el div #alerta-carrito en el HTML");
            return;
        }

        // Inyectar datos
        const img = document.getElementById('alerta-img');
        const nombre = document.getElementById('alerta-nombre');
        const talla = document.getElementById('alerta-talla');

        if(img) img.src = data.producto_img;
        if(nombre) nombre.textContent = data.producto_nombre;
        if(talla) talla.textContent = "Talla: " + data.producto_talla;

        // FORZAR VISIBILIDAD (Estilos en línea para sobrescribir CSS)
        alerta.style.display = 'block';
        // Pequeño timeout para permitir la transición de opacidad si la tienes
        setTimeout(() => {
            alerta.style.opacity = '1';
            alerta.style.visibility = 'visible';
            alerta.style.transform = 'translateX(0)';
        }, 10);

        // Ocultar a los 3 segundos
        setTimeout(() => {
            alerta.style.opacity = '0';
            alerta.style.transform = 'translateX(100%)';
            setTimeout(() => { alerta.style.display = 'none'; }, 500);
        }, 3000);
    }

    // CSRF Token Helper
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

    // Cargar el número del carrito apenas entras a la página
    if(document.getElementById('carritoBadge')) {
        cargarCarritoDesdeBD(); 
    }
});