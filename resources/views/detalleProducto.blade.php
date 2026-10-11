@extends('layouts.base')

@section('extra_css')
<link rel="stylesheet" href="{{ asset('css/productos.css') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=BBH+Sans+Hegarty&display=swap" rel="stylesheet">
@endsection

@section('title', 'Detalle Producto')

@section('content')
        <div class="contenedor__detalle">
            <div class="imagen">
                @php($imagen_url = $variante_principal?->getImage())
                @if($imagen_url)
                    <img src="{{ $imagen_url }}" alt="{{ $sneaker->name }} - {{ $variante_principal->color }}">
                @else
                    <img src="{{ asset('images/SneakerDefault.png') }}" alt="No disponible">
                @endif
            </div>

            <div class="informacion">

                <div class="informacion__datos">
                    <h2 class="datos__titulo">{{ $sneaker->name }}</h2>
                    <p class="datos__precio">${{ $variante_principal?->price ?? 'N/A' }}</p>
                    <p class="datos__genero" style="color: white; font-size: 0.9rem; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 1px;">
                        @if($variante_principal?->gender == 'Man')Hombre
                        @elseif($variante_principal?->gender == 'Woman')Mujer
                        @else Unisex @endif

                        <span style="margin: 0 5px;">|</span>
                        {{ $sneaker->category?->name ?: 'General' }}
                    </p>
                    <p class="datos__color">Color: {{ $color_activo }}</p>

                    <div class="color__opciones">
                        @foreach($colores_disponibles as $color)
                            <a href="{{ route('detalleProducto', array_filter(['idsneaker' => $sneaker->idSneaker, 'color' => $color, 'genero' => $genero_activo])) }}"
                               class="color__swatch @if($color == $color_activo) activo @endif">
                                {{ $color }}
                            </a>
                        @endforeach
                    </div>
                    <p class="datos__talla">Selecciona tu talla:</p>
                </div>

                <div class="informacion__numeros">
                    @foreach($variantes_color as $variante)
                        <div class="numero__item" data-variante-id="{{ $variante->idSneakerVariant }}" data-stock="{{ $variante->stock }}">
                            CM {{ $variante->size }}
                        </div>
                    @endforeach
                </div>

                <div class="descripcion__producto">
                    <h3 style="font-size: 1.2rem; margin-bottom: 0.5rem;">Descripción</h3>
                    <p style="color: white; line-height: 1.6;">
                        {{ $sneaker->description ?: 'Sin descripción disponible.' }}
                    </p>
                </div>

                <form class="form__carrito" method="POST" action="{{ route('alertcarrito') }}">
                    @csrf
                    <input type="hidden" name="variante_id" id="variante-seleccionada" value="">
                    <input type="hidden" name="cantidad" value="1">

                    <button type="submit" class="btn__agregar_carrito" disabled>
                        Añadir al Carrito
                    </button>
                </form>

            </div>
        </div>
@endsection

@section('extra_js')
<script src="{{ asset('js/detalleProducto.js') }}"></script>
@endsection
