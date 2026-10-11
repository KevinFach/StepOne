<header class="barra-principal @auth modo-logueado @else modo-invitado @endauth">
  <nav class="nav">
    <div class="nav__opciones">
      <a href="{{ route('home') }}" 
         class="nav__opcion @if(request()->is('/')) --seleccionada @endif">
         Inicio
      </a>
      <a href="{{ route('productos') }}?genero=mujer"
         class="nav__opcion @if(request('genero') == 'mujer' || ($genero_activo ?? null) == 'mujer') --seleccionada @endif">
         Mujer
      </a>
      <a href="{{ route('productos') }}?genero=hombre"
         class="nav__opcion @if(request('genero') == 'hombre' || ($genero_activo ?? null) == 'hombre') --seleccionada @endif">
         Hombre
      </a>
    </div>
  </nav>

  <div class="logo__contenedor">
    <h1 class="header__titulo">StepOne</h1>
  </div>

  <!-- ============================================= -->
  <!--                BUSCADOR                      -->
  <!-- ============================================= -->
  <div class="opciones">
    <div style="position: relative;">
      <a href="" class="opciones__icono" id="btnBuscar" >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="20"
          height="20"
          viewBox="0 0 20 20"
          fill="none"
          background="black"
        >
          <path
            d="M19.7748 18.6875L14.9656 13.8793C16.3595 12.2058 17.0546 10.0594 16.9062 7.88649C16.7579 5.7136 15.7775 3.68155 14.1691 2.21306C12.5607 0.744569 10.448 -0.0472976 8.27066 0.00218707C6.09327 0.0516718 4.01878 0.938698 2.47874 2.47874C0.938698 4.01878 0.0516718 6.09327 0.00218707 8.27066C-0.0472976 10.448 0.744569 12.5607 2.21306 14.1691C3.68155 15.7775 5.7136 16.7579 7.88649 16.9062C10.0594 17.0546 12.2058 16.3595 13.8793 14.9656L18.6875 19.7748C18.7589 19.8462 18.8437 19.9028 18.937 19.9415C19.0302 19.9801 19.1302 20 19.2312 20C19.3321 20 19.4321 19.9801 19.5254 19.9415C19.6187 19.9028 19.7034 19.8462 19.7748 19.7748ZM1.55803 8.47361C1.55803 7.10583 1.96362 5.76878 2.72351 4.63151C3.4834 3.49425 4.56347 2.60787 5.82713 2.08444C7.09078 1.56102 8.48128 1.42407 9.82277 1.69091C11.1643 1.95775 12.3965 2.61639 13.3637 3.58355C14.3308 4.55071 14.9895 5.78295 15.2563 7.12444C15.5231 8.46593 15.3862 9.85643 14.8628 11.1201C14.3393 12.3837 13.453 13.4638 12.3157 14.2237C11.1784 14.9836 9.84138 15.3892 8.47361 15.3892C6.6401 15.3872 4.88228 14.6579 3.5858 13.3614C2.28932 12.0649 1.56006 10.3071 1.55803 8.47361Z"
            fill="white"
          />
        </svg>
      </a>
      <div class="buscador" id="buscador">
        <form action="{{ route('productos') }}" method="GET">
          
          @if(request('genero'))
            <input type="hidden" name="genero" value="{{ request('genero') }}">
          @endif
          <input type="text" name="q" class="buscador__input" placeholder="Buscar productos...">
        </form>
      </div>
    </div>

    <!-- ================================= -->
    <!--              PERFIL               -->
    <!-- ================================= -->

    <div style="position: relative;">
      @auth
        <a href="#" class="opciones__icono" id="btnPerfil">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            width="29"
            height="29"
            viewBox="0 0 29 29"
            fill="none"
          >
            <path
              d="M19.4844 8.15625C19.2623 11.1508 16.9922 13.5938 14.5 13.5938C12.0078 13.5938 9.73368 11.1514 9.51561 8.15625C9.28905 5.04102 11.498 2.71875 14.5 2.71875C17.5019 2.71875 19.7109 5.09766 19.4844 8.15625Z"
              stroke="white"
              stroke-width="1.9"
              stroke-linecap="round"
              stroke-linejoin="round"
            />
            <path
              d="M14.5 17.5938C9.57228 17.5938 4.57091 20.3125 3.64541 25.4441C3.53382 26.0627 3.88386 26.6562 4.53127 26.6562H24.4688C25.1167 26.6562 25.4668 26.0627 25.3552 25.4441C24.4291 20.3125 19.4278 17.5938 14.5 17.5938Z"
              stroke="white"
              stroke-width="1.9"
              stroke-miterlimit="10"
            />
          </svg>
    
        </a>
        <div class="menu-perfil" id="menuPerfil">
          
              <a href="{{ route('login.perfil') }}" class="menu-perfil__opcion">Mi cuenta</a>
              <a href="#" class="menu-perfil__opcion">Mis pedidos</a>
              <a href="{{ route('login.logout') }}" class="menu-perfil__opcion">Cerrar sesión</a>
        </div>

      @else

        <div class="opciones__botones-acceso">
          <a href="{{ route('login') }}" class="btn__acceso">Iniciar Sesión</a>
          <a href="{{ route('signin') }}" class="btn__acceso btn__registro">Registrarse</a>
        </div>
      
      @endauth

    </div>

    <!-- =========================================== -->
    <!--          ICONO CARRITO DE COMPRAS           -->
    <!-- =========================================== -->
    
    @auth
    <div>
      
      <a href="" class="opciones__icono" id="btnCarrito">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="20"
          height="19"
          viewBox="0 0 20 19"
          fill="none"
        >
          <path
            d="M18.4615 4.56H14.6154C14.6154 3.35061 14.1291 2.19076 13.2636 1.33559C12.398 0.480427 11.2241 0 10 0C8.77592 0 7.60198 0.480427 6.73643 1.33559C5.87088 2.19076 5.38462 3.35061 5.38462 4.56H1.53846C1.13044 4.56 0.739122 4.72014 0.450605 5.0052C0.162087 5.29025 0 5.67687 0 6.08V17.48C0 17.8831 0.162087 18.2697 0.450605 18.5548C0.739122 18.8399 1.13044 19 1.53846 19H18.4615C18.8696 19 19.2609 18.8399 19.5494 18.5548C19.8379 18.2697 20 17.8831 20 17.48V6.08C20 5.67687 19.8379 5.29025 19.5494 5.0052C19.2609 4.72014 18.8696 4.56 18.4615 4.56ZM10 1.52C10.8161 1.52 11.5987 1.84028 12.1757 2.4104C12.7527 2.98051 13.0769 3.75374 13.0769 4.56H6.92308C6.92308 3.75374 7.24725 2.98051 7.82429 2.4104C8.40132 1.84028 9.18395 1.52 10 1.52ZM18.4615 17.48H1.53846V6.08H5.38462V7.6C5.38462 7.80156 5.46566 7.99487 5.60992 8.1374C5.75418 8.27993 5.94983 8.36 6.15385 8.36C6.35786 8.36 6.55352 8.27993 6.69777 8.1374C6.84203 7.99487 6.92308 7.80156 6.92308 7.6V6.08H13.0769V7.6C13.0769 7.80156 13.158 7.99487 13.3022 8.1374C13.4465 8.27993 13.6421 8.36 13.8462 8.36C14.0502 8.36 14.2458 8.27993 14.3901 8.1374C14.5343 7.99487 14.6154 7.80156 14.6154 7.6V6.08H18.4615V17.48Z"
            fill="white"
          />
        </svg>
        <span class="carrito-badge" id="carritoBadge">0</span>
      </a>
    </div>
  @endauth</div>

  <!-- ================================================= -->
  <!--              PANEL CARRITO DE COMPRAS             -->
  <!-- ================================================= -->

  <div class="carrito-overlay" id="carritoOverlay"></div>
    <div class="carrito-panel" id="carritoPanel">
        <div class="carrito-header">
            <h2>Mi Carrito</h2>
            <button class="cerrar-carrito" id="cerrarCarrito">×</button>
        </div>
        <div class="carrito-contenido" id="carritoContenido">
            <div class="carrito-vacio" id="carritoVacio">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/>
                </svg>
                <p>Tu carrito está vacío</p>
            </div>
        </div>
        <div class="carrito-footer" id="carritoFooter" style="display: none;">
            <div class="carrito-total">
                <span>Total:</span>
                <span id="totalCarrito">$0</span>
            </div>
            <a href="{{ route('checkout') }}" class="btn-comprar">Proceder al pago</a>
        </div>
    </div>

</header>



<div id="alerta-carrito" class="contenedor__carrito" style="position: fixed; top: 80px; right: 20px; z-index: 9999; display: none;">
    <div class="carrito__mensaje">
        <div class="icono__check">✓</div>
        <p class="mensaje__texto">Agregado al carrito correctamente</p>
    </div>

    <div class="carrito__producto">
        <div class="producto__imagen">
            <img id="alerta-img" src="" alt="Producto" style="width: 50px; height: 50px; object-fit: cover;">
        </div>
        <div class="producto__info">
            <h3 id="alerta-nombre" class="producto__nombre" style="font-size: 0.9rem; margin:0;"></h3>
            <p id="alerta-talla" class="producto__talla" style="font-size: 0.8rem; color: gray; margin:0;"></p>
        </div>
    </div>
    <span onclick="cerrarAlerta()" style="position:absolute; top:5px; right:10px; cursor:pointer;">&times;</span>
</div>

<script src="{{ asset('js/header.js') }}"></script>



