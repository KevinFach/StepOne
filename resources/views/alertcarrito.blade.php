@extends('layouts.base')

@section('extra_css')
<link rel="stylesheet" href="{{ asset('css/alertcarrito.css') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=BBH+Sans+Hegarty&display=swap" rel="stylesheet">
@endsection

@section('title', 'Detalle Producto')

@section('content')
        <div class="contenedor__carrito">
            <div class="carrito__mensaje">
                <div class="icono__check">✓</div>
                <p class="mensaje__texto">Agregado al carrito correctamente</p>
            </div>

            <div class="carrito__producto">
                <div class="producto__imagen">
                    <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400" alt="Nike Vomero Plus SE">
                </div>
                <div class="producto__info">
                    <h3 class="producto__nombre">Nike Vomero Plus SE</h3>
                    <p class="producto__talla">Talla CM 22</p>
                </div>
            </div>

            <div class="carrito__botones">
                <button class="boton boton__ver">Ver carrito</button>
                <a href="{{ route('checkout') }}" class="boton boton__comprar">Comprar</a>
            </div>
        </div>
@endsection
