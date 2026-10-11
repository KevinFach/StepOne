        function editarPersonal() {
            document.getElementById('vista-personal').classList.add('oculto');
            document.getElementById('edicion-personal').classList.remove('oculto');
            document.getElementById('btn-editar-personal').style.display = 'none';
        }

        function cancelarPersonal() {
            document.getElementById('vista-personal').classList.remove('oculto');
            document.getElementById('edicion-personal').classList.add('oculto');
            document.getElementById('btn-editar-personal').style.display = 'block';
        }

        function editarFiscal() {
            document.getElementById('vista-fiscal').classList.add('oculto');
            document.getElementById('edicion-fiscal').classList.remove('oculto');
            document.getElementById('btn-editar-fiscal').style.display = 'none';
        }

        function cancelarFiscal() {
            document.getElementById('vista-fiscal').classList.remove('oculto');
            document.getElementById('edicion-fiscal').classList.add('oculto');
            document.getElementById('btn-editar-fiscal').style.display = 'block';
        }

        function cerrarSesion() {
            const boton = document.getElementById('button_cerrarsesion');

            const urlLogout = boton.getAttribute('data-url');

                //if (confirm("¿Seguro que deseas salir?")) {
        
                    window.location.href = urlLogout;

            //    }
        }