@extends('layouts.base')


@section('extra_css')
<link rel="stylesheet" href="{{ asset('css/checkout.css') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=BBH+Sans+Hegarty&display=swap" rel="stylesheet">
@endsection

@section('title', 'Pedidos')

@section('content')


    <main>
        <div class="pedidos__contenedor">
            <div class="pedidos__header">
                <h1 class="pedidos__titulo">Mis Pedidos</h1>
                <p class="pedidos__subtitulo">Historial completo de tus compras</p>
            </div>

            <!-- Filtros -->
            <div class="pedidos__filtros">
                <button class="filtro__boton activo" onclick="filtrarPedidos('todos')">Todos</button>
                <button class="filtro__boton" onclick="filtrarPedidos('entregado')">Entregados</button>
                <button class="filtro__boton" onclick="filtrarPedidos('en-camino')">En camino</button>
                <button class="filtro__boton" onclick="filtrarPedidos('procesando')">Procesando</button>
                <button class="filtro__boton" onclick="filtrarPedidos('cancelado')">Cancelados</button>
            </div>

            <!-- Lista de pedidos -->
            <div class="pedidos__lista">
                <!-- Pedido 1 - Entregado -->
                <div class="pedido__card" data-estado="entregado">
                    <div class="pedido__header">
                        <div class="pedido__info">
                            <p class="pedido__numero">Pedido #10254</p>
                            <p class="pedido__fecha">15 de Noviembre, 2024</p>
                        </div>
                        <span class="pedido__estado estado--entregado">Entregado</span>
                    </div>

                    <div class="pedido__productos">
                        <div class="producto__item">
                            <div class="producto__imagen">
                                <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400" alt="Nike Air Max">
                            </div>
                            <div class="producto__detalles">
                                <p class="producto__nombre">Nike Air Max 270</p>
                                <p class="producto__info">Color: Negro | Talla: CM 25 | Cantidad: 1</p>
                            </div>
                            <div class="producto__precio">$2,899</div>
                        </div>
                    </div>

                    <div class="pedido__footer">
                        <div class="pedido__total">
                            <p class="total__label">Total del pedido</p>
                            <p class="total__cantidad">$2,899</p>
                        </div>
                        <div class="pedido__acciones">
                            <button class="boton__accion boton__ver">Ver detalles</button>
                            <button class="boton__accion boton__rastrear">Volver a comprar</button>
                        </div>
                    </div>
                </div>

                <!-- Pedido 2 - En camino -->
                <div class="pedido__card" data-estado="en-camino">
                    <div class="pedido__header">
                        <div class="pedido__info">
                            <p class="pedido__numero">Pedido #10308</p>
                            <p class="pedido__fecha">3 de Diciembre, 2024</p>
                        </div>
                        <span class="pedido__estado estado--en-camino">En camino</span>
                    </div>

                    <div class="pedido__productos">
                        <div class="producto__item">
                            <div class="producto__imagen">
                                <img src="https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?w=400" alt="Adidas Ultraboost">
                            </div>
                            <div class="producto__detalles">
                                <p class="producto__nombre">Adidas Ultraboost 21</p>
                                <p class="producto__info">Color: Blanco | Talla: CM 26 | Cantidad: 1</p>
                            </div>
                            <div class="producto__precio">$3,299</div>
                        </div>
                        <div class="producto__item">
                            <div class="producto__imagen">
                                <img src="https://images.unsplash.com/photo-1549298916-b41d501d3772?w=400" alt="Jordan 1">
                            </div>
                            <div class="producto__detalles">
                                <p class="producto__nombre">Air Jordan 1 Retro</p>
                                <p class="producto__info">Color: Rojo | Talla: CM 27 | Cantidad: 1</p>
                            </div>
                            <div class="producto__precio">$3,999</div>
                        </div>
                    </div>

                    <div class="pedido__footer">
                        <div class="pedido__total">
                            <p class="total__label">Total del pedido</p>
                            <p class="total__cantidad">$7,298</p>
                        </div>
                        <div class="pedido__acciones">
                            <button class="boton__accion boton__ver">Ver detalles</button>
                            <button class="boton__accion boton__rastrear">Rastrear pedido</button>
                        </div>
                    </div>
                </div>

                <!-- Pedido 3 - Procesando -->
                <div class="pedido__card" data-estado="procesando">
                    <div class="pedido__header">
                        <div class="pedido__info">
                            <p class="pedido__numero">Pedido #10315</p>
                            <p class="pedido__fecha">6 de Diciembre, 2024</p>
                        </div>
                        <span class="pedido__estado estado--procesando">Procesando</span>
                    </div>

                    <div class="pedido__productos">
                        <div class="producto__item">
                            <div class="producto__imagen">
                                <img src="https://images.unsplash.com/photo-1600269452121-4f2416e55c28?w=400" alt="Puma RS-X">
                            </div>
                            <div class="producto__detalles">
                                <p class="producto__nombre">Puma RS-X Tech</p>
                                <p class="producto__info">Color: Azul | Talla: CM 24 | Cantidad: 2</p>
                            </div>
                            <div class="producto__precio">$4,998</div>
                        </div>
                    </div>

                    <div class="pedido__footer">
                        <div class="pedido__total">
                            <p class="total__label">Total del pedido</p>
                            <p class="total__cantidad">$4,998</p>
                        </div>
                        <div class="pedido__acciones">
                            <button class="boton__accion boton__ver">Ver detalles</button>
                            <button class="boton__accion boton__rastrear">Cancelar pedido</button>
                        </div>
                    </div>
                </div>

                <!-- Pedido 4 - Cancelado -->
                <div class="pedido__card" data-estado="cancelado">
                    <div class="pedido__header">
                        <div class="pedido__info">
                            <p class="pedido__numero">Pedido #10201</p>
                            <p class="pedido__fecha">28 de Octubre, 2024</p>
                        </div>
                        <span class="pedido__estado estado--cancelado">Cancelado</span>
                    </div>

                    <div class="pedido__productos">
                        <div class="producto__item">
                            <div class="producto__imagen">
                                <img src="https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=400" alt="New Balance 574">
                            </div>
                            <div class="producto__detalles">
                                <p class="producto__nombre">New Balance 574</p>
                                <p class="producto__info">Color: Gris | Talla: CM 25 | Cantidad: 1</p>
                            </div>
                            <div class="producto__precio">$2,199</div>
                        </div>
                    </div>

                    <div class="pedido__footer">
                        <div class="pedido__total">
                            <p class="total__label">Total del pedido</p>
                            <p class="total__cantidad">$2,199</p>
                        </div>
                        <div class="pedido__acciones">
                            <button class="boton__accion boton__ver">Ver detalles</button>
                            <button class="boton__accion boton__rastrear">Volver a comprar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection

@section('extra_js')
<script src="{{ asset('js/pedidos.js') }}"></script>
@endsection