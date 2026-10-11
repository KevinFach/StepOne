@extends('layouts.base')

@section('extra_css')
<link rel="stylesheet" href="{{ asset('css/pago_exitoso.css') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=BBH+Sans+Hegarty&display=swap" rel="stylesheet">
@endsection

@section('content')

<div class="exito__contenedor">
    <div class="exito__card">
        <div class="icono__exito">
            <svg viewBox="0 0 24 24" class="check-svg">
                <path d="M4 12l6 6L20 6" />
            </svg>
        </div>

        <h1 class="exito__titulo">¡Pago Recibido!</h1>

        <p class="exito__texto">
            Tu orden <span class="orden__id">#{{ $orden->idOrder }}</span> ha sido confirmada.
        </p>

        <p class="exito__subtexto">
            Hemos enviado un correo con los detalles de tu compra.
        </p>

        <a href="{{ route('home') }}" class="boton__volver">
            Seguir comprando
        </a>
    </div>
</div>

@endsection
