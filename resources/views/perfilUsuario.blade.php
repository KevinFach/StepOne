<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,200..800&family=Praise&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    
     <link rel="stylesheet" href="{{ asset('css/perfilUsuario.css') }}">
    <title>Perfil</title>
</head>
<body>
    @include('partials.header')
    
    <main>
        <!-- Contenido de cada página -->
        <div class="perfil__contenedor">
            <div class="perfil__layout">
                <!-- Sidebar -->
                <aside class="perfil__sidebar">
                    <div class="sidebar__avatar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <div class="avatar__badge">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </div>
                    </div>
                    <h2 class="sidebar__nombre" id="sidebar-nombre">{{ $user->first_name ?: 'Usuario' }} {{ $user->last_name }}</h2>
                    <p class="sidebar__email" id="sidebar-email">{{ $user->email }}</p>

                    <div class="sidebar__stats">
                        <div class="stat__item">
                            <p class="stat__numero">12</p>
                            <p class="stat__label">Compras</p>
                        </div>
                        <div class="stat__item">
                            <p class="stat__numero">3</p>
                            <p class="stat__label">Pedidos activos</p>
                        </div>
                        <div class="stat__item">
                            <p class="stat__numero">$8,450</p>
                            <p class="stat__label">Total gastado</p>
                        </div>
                    </div>

                    <button class="sidebar__boton">Ver mis compras</button>
                    <button id ='button_cerrarsesion' 
                            onclick = "cerrarSesion()"  
                            data-url="{{ route('login.logout') }}"
                            class="sidebar__boton" 
                            style="background-color: transparent; color: var(--azul02); border: 2px solid var(--azul02); margin-top: 0.5rem;">
                        Cerrar sesión
                    </button>
                </aside>

                <!-- Contenido principal -->
                <div class="perfil__contenido">
                    <!-- Información Personal -->
                    <div class="contenido__card">
                        <div class="card__header">
                            <h3 class="card__titulo">Información Personal</h3>
                            <button class="card__boton" id="btn-editar-personal" onclick="editarPersonal()">Editar</button>
                        </div>

                        <div id="vista-personal">
                            <div class="datos__grid">
                                <div class="dato__item">
                                    <label class="dato__label">Nombre</label>
                                    <span class="dato__valor" id="nombre-vista">{{ $user->first_name ?: '' }}</span>
                                </div>
                                <div class="dato__item">
                                    <label class="dato__label">Apellidos</label>
                                    <span class="dato__valor" id="apellido-vista">{{ $user->last_name ?: '--' }}</span>
                                </div>
                                <div class="dato__item dato__completo">
                                    <label class="dato__label">Correo electrónico</label>
                                    <span class="dato__valor" id="email-vista">{{ $user->email }}</span>
                                </div>
                                <div class="dato__item dato__completo">
                                    <label class="dato__label">Teléfono</label>
                                    <span class="dato__valor" id="telefono-vista">{{ $user->phone ?: 'Sin registrar' }}</span>
                                </div>
                            </div>
                        </div>

                        <div id="edicion-personal" class="oculto">
                            <form method="POST" action="{{ route('login.perfil') }}">
                            @csrf <input type="hidden" name="tipo_formulario" value="personal">
                                <div class="datos__grid">
                                    <div class="dato__item">
                                        <label class="dato__label">Nombre</label>
                                        <input type="text" name="first_name" class="dato__input" id="nombre-input" value="{{ $user->first_name ?: '' }}">
                                    </div>
                                    <div class="dato__item">
                                        <label class="dato__label">Apellidos</label>
                                        <input type="text" name="last_name" class="dato__input" id="apellido-input" value="{{ $user->last_name ?: '' }}">
                                    </div>
                                    <div class="dato__item dato__completo">
                                        <label class="dato__label">Correo electrónico</label>
                                        <input type="email" class="dato__input" id="email-input" value="{{ $user->email }}">
                                    </div>
                                    <div class="dato__item dato__completo">
                                        <label class="dato__label">Teléfono</label>
                                        <input type="tel" name="phone" class="dato__input" id="telefono-input" value="{{ $user->phone ?: 'Sin registrar' }}">
                                    </div>
                                </div>
                                <div class="botones__grupo">
                                    <button class="boton__principal">Guardar cambios</button>
                                    <button class="boton__secundario">Cancelar</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Datos Fiscales -->
                    <div class="contenido__card">
                        <div class="card__header">
                            <h3 class="card__titulo">Datos Fiscales</h3>
                            <button class="card__boton" id="btn-editar-fiscal" onclick="editarFiscal()">Editar</button>
                        </div>

                        <div id="vista-fiscal">
                            <div class="datos__grid">
                                <div class="dato__item">
                                    <label class="dato__label">RFC</label>
                                    <span class="dato__valor" id="rfc-vista">{{ $user->rfc ?: 'Sin registrar' }}</span>
                                </div>
                                <div class="dato__item">
                                    <label class="dato__label">CURP</label>
                                    <span class="dato__valor" id="curp-vista">{{ $user->CURP ?: 'Sin registrar' }}</span>
                                </div>
                            </div>
                        </div>

                        <div id="edicion-fiscal" class="oculto">
                            <form method="POST" action="{{ route('login.perfil') }}">
                                @csrf
                                <input type="hidden" name="tipo_formulario" value="fiscal">
                                <div class="datos__grid">
                                    <div class="dato__item">
                                        <label class="dato__label">RFC</label>
                                        <input type="text" name="rfc" class="dato__input" id="rfc-input" value="{{ $user->rfc ?: 'Sin registrar' }}" maxlength="13">
                                    </div>
                                    <div class="dato__item">
                                        <label class="dato__label">CURP</label>
                                        <input type="text" name="curp" class="dato__input" id="curp-input" value="{{ $user->CURP ?: 'Sin registrar' }}" maxlength="18">
                                    </div>
                                </div>
                                <div class="botones__grupo">
                                    <button class="boton__principal">Guardar cambios</button>
                                    <button class="boton__secundario">Cancelar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    @include('partials.footer')

    <script src="{{ asset('js/perfilUsuario.js') }}"></script>
</body>
</html>
