@extends('layouts.base')

@section('extra_css')

<link rel="stylesheet" href="{{ asset('css/productos.css') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=BBH+Sans+Hegarty&display=swap" rel="stylesheet">
@endsection


@section('title', 'Productos')

@section('content')

<div class="layout-productos">

    <aside class="filtros__panel">
        <form id="formFiltros" method="GET" action="{{ route('productos') }}">

            @if(request('genero'))
                <input type="hidden" name="genero" value="{{ request('genero') }}">
            @endif

            <div class="filtros__header">
                <h2 class="filtros__titulo">Filtros</h2>
                <a href="{{ route('productos', request('genero') ? ['genero' => request('genero')] : []) }}" class="filtros__limpiar">Limpiar todo</a>
            </div>

            <div class="filtro__seccion cerrado">
                <h3 class="filtro__titulo">
                    Marca
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
               </h3>
                <div class="filtro__opciones">
                    @foreach($todas_las_marcas as $marca)
                         <label class="filtro__checkbox">
                              <input type="checkbox" name="marca[]" value="{{ $marca->name }}"
                                   @checked(in_array($marca->name, $marcas_seleccionadas))>
                              <span class="filtro__label">{{ $marca->name }}</span>
                         </label>
                    @endforeach
               </div>
            </div>

            <div class="filtro__seccion cerrado">
               <h3 class="filtro__titulo">
                    Categoria
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
               </h3>

               <div class="filtro__opciones">
                    @foreach($todas_las_categorias as $categoria)
                         <label class="filtro__checkbox">
                              <input type="checkbox" name="tipo[]" value="{{ $categoria->name }}"
                                   @checked(in_array($categoria->name, $tipos_seleccionados))>

                              <span class="filtro__label">{{ $categoria->name }}</span>
                         </label>
                    @endforeach
               </div>
            </div>

            <div class="filtro__seccion">
               <h3 class="filtro__titulo">Talla (CM)</h3>

               <div class="tallas__grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 5px;">
                    @foreach($todas_las_tallas as $talla)
                         <label class="talla__checkbox" style="cursor: pointer;">
                              <input type="checkbox" name="talla[]" value="{{ $talla }}" style="display: none;"
                                   @checked(in_array($talla, $tallas_seleccionadas))>
                              <span class="talla__visual" style="display: block; padding: 5px; border: 1px solid #ccc; text-align: center; border-radius: 4px; font-size: 0.9rem;">
                                   {{ $talla }}
                              </span>
                         </label>
                    @endforeach
               </div>
          </div>

               <style>
                    .talla__checkbox input:checked + .talla__visual {
                         background-color: var(--azul02); /* Tu color azul */
                         color: white;
                         border-color: var(--azul02);
                    }
               </style>

            <div class="filtro__seccion">
                <h3 class="filtro__titulo">
                    Precio
               </h3>
                <div class="precio__inputs">
                    <div class="precio__input">
                        <label>Mín</label>
                        <input type="number" name="precio_min" placeholder="0" value="{{ request('precio_min') }}">
                    </div>
                    <div class="precio__input">
                        <label>Máx</label>
                        <input type="number" name="precio_max" placeholder="5000" value="{{ request('precio_max') }}">
                    </div>
                </div>
            </div>

            <div class="filtro__seccion cerrado">
                <h3 class="filtro__titulo">
                    Color
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
               </h3>
                <div class="filtro__opciones" style="max-height: 200px; overflow-y: auto;">

                    @foreach($todos_los_colores as $color)
                         <label class="filtro__checkbox">
                              <input type="checkbox" name="color[]" value="{{ $color }}"
                                   @checked(in_array($color, $colores_seleccionados))>

                              <span class="filtro__label">{{ $color }}</span>
                         </label>
                    @endforeach

               </div>
            </div>

            <button type="submit" class="boton__aplicar">Aplicar Filtros</button>
        </form>
    </aside>

     <div class="contenedor__productos">

          @foreach($productos as $item)
          <div class="contenedor__sneaker">
               <a href="{{ route('detalleProducto', ['idsneaker' => $item->idSneaker, 'genero' => request('genero'), 'color' => $item->color]) }}">
               <div class="sneaker__imagen">
                    @if($item->getImage())
                         <img src="{{ $item->getImage() }}" alt="{{ $item->sneaker->name }} - {{ $item->color }}">
                    @else
                         <img src="{{ asset('images/SneakerDefault.png') }}" alt="{{ $item->sneaker->name }}">
                    @endif
               </div>

               <div class="sneaker__informacion">
                    <p class="nombre">{{ $item->sneaker->name }} ({{ $item->color }})</p>
                    <p class="precio">${{ $item->price }}</p>
               </div>

          </a></div>
          @endforeach

     </div>

</div>
@endsection

@section('extra_js')
<script src="{{ asset('js/productos.js') }}"></script>
@endsection
