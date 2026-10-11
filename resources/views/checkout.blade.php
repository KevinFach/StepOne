@extends('layouts.base')


@section('extra_css')
<link rel="stylesheet" href="{{ asset('css/checkout.css') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=BBH+Sans+Hegarty&display=swap" rel="stylesheet">
@endsection

@section('title', 'Checkout')

@section('content')
<main>
    <div class="checkout__contenedor">
        <div class="checkout__formulario">
            <h2 class="seccion__titulo">Datos de envío</h2>
            
            <h3 class="subtitulo__seccion">Selecciona una dirección:</h3>

            <div id="lista-direcciones" class="grid__direcciones">
                @forelse($direcciones as $dir)
                    <div class="direccion__card @if($loop->first) seleccionada @endif" 
                         onclick="seleccionarDireccion(this, '{{ $dir->idAddres }}')">
                        
                        <div class="icono__check">
                            @if($loop->first)
                                <div class="radio-lleno"></div>
                            @else
                                <div class="radio-vacio"></div>
                            @endif
                        </div>

                        <div class="direccion__texto">
                            <p class="direccion__nombre">
                                {{ $dir->street }} #{{ $dir->number }}
                            </p>
                            <p class="direccion__detalles">
                                Col. {{ $dir->colony }}<br>
                                CP: {{ $dir->zip }} - {{ $dir->city }}, {{ $dir->state }}
                            </p>
                        </div>
                    </div>
                @empty
                    <p id="mensaje-sin-dir">No tienes direcciones guardadas.</p>
                @endforelse
            </div>

            <input type="hidden" id="id-direccion-seleccionada" value="{{ $direccion_default?->idAddres ?? '' }}">

            <button class="boton__agregar" onclick="abrirModalDireccion()">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="16"></line>
                    <line x1="8" y1="12" x2="16" y2="12"></line>
                </svg>
                Agregar nueva dirección
            </button>

            <div id="contenedor-envios" style="margin-top: 2rem; display: none;">
                <h3 class="subtitulo__seccion">Elige tu paquetería:</h3>
                <div id="lista-opciones-envio" class="grid__direcciones"></div>
            </div>

            <h2 class="seccion__titulo">Pago</h2>
            
            <!-- <div class="formulario__grupo">
                <label class="formulario__label">Número de tarjeta</label>
                <input type="text" id="card-number" class="formulario__input__largo" placeholder="0000 0000 0000 0000">
            </div>

            <div class="formulario__row">
                <div class="formulario__grupo">
                    <label class="formulario__label">Nombre del titular</label>
                    <input type="text" id="card-name" class="formulario__input">
                </div>
            </div>

            <div class="formulario__row">
                <div class="formulario__grupo">
                    <label class="formulario__label">MM / AA</label>
                    <input type="text" id="card-expiry" class="formulario__input" placeholder="12/25">
                </div>
                <div class="formulario__grupo">
                    <label class="formulario__label">CVV</label>
                    <input type="text" id="card-cvv" class="formulario__input" placeholder="123" maxlength="3">
                </div>
            </div> -->
        <div class="info-pago" style="background: rgba(255,255,255,0.1); padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; color: white;">
                <p style="margin: 0; font-size: 0.9rem;">
                    Serás redirigido a <strong>Mercado Pago</strong> para completar tu compra de forma segura con Tarjeta, Transferencia o Efectivo.
                </p>
                </div>

        
            <button class="boton__realizar" id="btn-pagar" onclick="procesarPago()">Pagar con Mercado Pago (${{ $total }})</button>
        </div>

        <div class="checkout__resumen">
            <h2 class="resumen__titulo">Resumen del pedido</h2>

            @forelse($items as $item)
            <div class="resumen__producto">
                <div class="resumen__imagen">
                    @if($item['imagen'])
                        <img src="{{ $item['imagen'] }}" alt="{{ $item['nombre'] }}">
                    @else
                        <img src="{{ asset('images/SneakerDefault.png') }}" alt="Default">
                    @endif
                </div>
                <div class="resumen__info">
                    <p class="resumen__nombre">{{ $item['nombre'] }}</p>
                    <p class="resumen__detalles">Talla: {{ $item['talla'] }} | Cant: {{ $item['cantidad'] }}</p>
                    <p class="resumen__precio">${{ $item['precio'] }}</p>
                </div>
            </div>
            @empty
                <p>Tu carrito está vacío.</p>
            @endforelse

            <!-- <div class="resumen__fila total">
                <span>Total</span>
                <span>${{ $total }}</span>
            </div> -->

            <div class="cupon__container" style="margin-top: 20px; border-top: 1px solid rgba(255,255,255,0.2); padding-top: 15px;">
                <label style="color:white; display:block; margin-bottom:5px; font-size: 0.9rem;">¿Tienes un cupón?</label>
                <div style="display: flex; gap: 10px;">
                    <input type="text" id="input-cupon" placeholder="XXX-XXX-XXX" maxlength="11" 
                        style="flex: 1; padding: 10px; border-radius: 5px; border: none; text-transform: uppercase;">
                    <button onclick="aplicarCupon()" id="btn-aplicar-cupon"
                            style="background: #40ffaa; border: none; padding: 0 15px; border-radius: 5px; font-weight: bold; cursor: pointer; color: #000;">
                        Aplicar
                    </button>
                </div>
                <small id="mensaje-cupon" style="display:block; margin-top:5px; height: 20px;"></small>
            </div>

            <div style="border-top: 1px solid rgba(255,255,255,0.2); margin-top: 20px; padding-top: 15px;">
                <div class="resumen__fila">
                    <span>Subtotal</span>
                    <span>${{ $total }}</span>
                </div>
                
                <div class="resumen__fila">
                    <span>Envío</span>
                    <span id="costo-envio-display" style="font-size: 0.9em; opacity: 0.8;">Selecciona dirección</span> 
                </div>

                <div class="resumen__fila total">
                    <span>Total a Pagar</span>
                    <span id="total-final-display">${{ $total }}</span>
                </div>
            </div>
        </div>
    </div>

    <div id="modalDireccion" class="modal__overlay">
        <div class="modal__contenido">
            <div class="modal__header">
                <h3>Nueva Dirección</h3>
                <button class="modal__cerrar" onclick="cerrarModalDireccion()">&times;</button>
            </div>
            
            <form id="formNuevaDireccion" class="modal__form">
                <div class="form__fila">
                    <div class="form__grupo">
                        <label>Calle</label>
                        <input type="text" name="street" required placeholder="Ej: Av. Revolución">
                    </div>
                    <div class="form__grupo corto">
                        <label>Número</label>
                        <input type="text" name="number" required placeholder="123">
                    </div>
                </div>

                <div class="form__fila">
                    <div class="form__grupo">
                        <label>Colonia</label>
                        <input type="text" name="colony" required placeholder="Col. Centro">
                    </div>
                    <div class="form__grupo corto">
                        <label>C.P.</label>
                        <input type="text" name="zip" required placeholder="00000" maxlength="5">
                    </div>
                </div>

                <div class="form__fila">
                    <div class="form__grupo">
                        <label>Ciudad</label>
                        <input type="text" name="city" required placeholder="Boca del Rio">
                    </div>
                    <div class="form__grupo">
                        <label>Estado</label>
                        <input type="text" name="state" required placeholder="Veracruz">
                    </div>
                </div>

                <div class="modal__botones">
                    <button type="button" class="btn__cancelar" onclick="cerrarModalDireccion()">Cancelar</button>
                    <button type="submit" class="btn__guardar">Guardar Dirección</button>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection


@section('extra_js')
<!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
<script src="{{ asset('js/checkout.js') }}"></script>
@endsection