{{-- ------------------------------------------------ --}}
{{--                     NOTA                         --}}
{{-- ------------------------------------------------ --}}
{{-- Este código es un ejemplo de la implementación
    que se podria hacer con los demas templates       --}}

@extends('layouts.base')

@section('extra_css')
<link rel="stylesheet" href="{{ asset('css/home02.css') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=BBH+Sans+Hegarty&display=swap" rel="stylesheet">
@endsection

@section('title', 'Inicio')

@section('content')
    <section>
        <div class="contenedor__principal">
            <h1 class="texto__fondo">JORDAN</h1>
            <img src="{{ asset('images/sneaker_home.png') }}" alt="Jordan Imagen" class="imagen__delante">
        </div>

        <div class="informacion__sneaker">
            <h2 class="sneaker__nombre">Jordan Blue</h2>
            <p class="sneaker__precio">$4,225</p>

            <div class="btnAnadir">
                <button class="btnAnadir__texto">Añadir al carrito +</button>
            </div>
        </div>
    </section>
@endsection
