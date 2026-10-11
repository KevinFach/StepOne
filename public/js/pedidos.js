function filtrarPedidos(estado) {
            const cards = document.querySelectorAll('.pedido__card');
            const botones = document.querySelectorAll('.filtro__boton');
            
            // Actualizar botón activo
            botones.forEach(btn => btn.classList.remove('activo'));
            event.target.classList.add('activo');
            
            // Filtrar pedidos
            cards.forEach(card => {
                if (estado === 'todos') {
                    card.style.display = 'block';
                } else {
                    if (card.dataset.estado === estado) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                }
            });
        }