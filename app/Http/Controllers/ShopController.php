<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Sneaker;
use App\Models\SneakerVariant;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function home(): View
    {
        return view('home_02');
    }

    /**
     * Una tarjeta por cada combinación única de Sneaker y Color, con filtros.
     */
    public function productos(Request $request): View
    {
        $marcas = (array) $request->query('marca', []);
        $tipos = (array) $request->query('tipo', []);
        $tallas = (array) $request->query('talla', []);
        $colores = (array) $request->query('color', []);

        $baseQuery = SneakerVariant::query()
            ->whereHas('sneaker', fn ($q) => $q->where('estado', Sneaker::ACTIVE));

        if ($busqueda = $request->query('q')) {
            $baseQuery->whereHas('sneaker', fn ($q) => $q->where('name', 'like', "%{$busqueda}%"));
        }

        match ($request->query('genero')) {
            'mujer' => $baseQuery->whereIn('gender', ['Woman', 'Unisex']),
            'hombre' => $baseQuery->whereIn('gender', ['Man', 'Unisex']),
            default => null,
        };

        if ($marcas) {
            $baseQuery->whereHas('sneaker.brand', fn ($q) => $q->whereIn('name', $marcas));
        }
        if ($request->filled('precio_min')) {
            $baseQuery->where('price', '>=', $request->query('precio_min'));
        }
        if ($request->filled('precio_max')) {
            $baseQuery->where('price', '<=', $request->query('precio_max'));
        }
        if ($colores) {
            $baseQuery->whereIn('color', $colores);
        }
        if ($tipos) {
            $baseQuery->whereHas('sneaker.category', fn ($q) => $q->whereIn('name', $tipos));
        }
        if ($tallas) {
            $baseQuery->whereIn('size', $tallas);
        }

        // Primera variante de cada (sneaker, color) como referencia de la tarjeta
        $idsReferencia = $baseQuery
            ->selectRaw('MIN(idSneakerVariant) as id')
            ->groupBy('idSneaker', 'color')
            ->orderBy('idSneaker')
            ->orderBy('color')
            ->pluck('id');

        $productos = SneakerVariant::with('sneaker')
            ->whereIn('idSneakerVariant', $idsReferencia)
            ->orderBy('idSneaker')
            ->orderBy('color')
            ->get();

        $variantesActivas = SneakerVariant::whereHas('sneaker', fn ($q) => $q->where('estado', Sneaker::ACTIVE));

        $todasLasTallas = (clone $variantesActivas)->distinct()->pluck('size')
            ->sortBy(fn ($talla) => is_numeric($talla) ? (float) $talla : 0)
            ->values();

        return view('productos', [
            'productos' => $productos,
            'todas_las_marcas' => Brand::orderBy('name')->get(),
            'todas_las_categorias' => Category::orderBy('name')->get(),
            'todas_las_tallas' => $todasLasTallas,
            'todos_los_colores' => (clone $variantesActivas)->distinct()->orderBy('color')->pluck('color'),
            'marcas_seleccionadas' => $marcas,
            'tallas_seleccionadas' => $tallas,
            'colores_seleccionados' => $colores,
            'tipos_seleccionados' => $tipos,
            'busqueda_actual' => $busqueda,
        ]);
    }

    public function detalleProducto(Request $request, int $idsneaker): View
    {
        $sneaker = Sneaker::with('category')->findOrFail($idsneaker);

        $generoUrl = $request->query('genero');
        $generoActivo = 'inicio';
        $generos = null;

        if ($generoUrl === 'hombre') {
            $generos = ['Man', 'Unisex'];
            $generoActivo = 'hombre';
        } elseif ($generoUrl === 'mujer') {
            $generos = ['Woman', 'Unisex'];
            $generoActivo = 'mujer';
        }

        $todasLasVariantes = $sneaker->variants()
            ->when($generos, fn ($q) => $q->whereIn('gender', $generos))
            ->orderBy('size')
            ->get();

        // Si el filtro de género deja sin variantes, se muestran todas
        if ($todasLasVariantes->isEmpty()) {
            $todasLasVariantes = $sneaker->variants()->orderBy('size')->get();
        }

        $coloresDisponibles = $todasLasVariantes->pluck('color')->unique()->sort()->values();

        $colorSeleccionado = $request->query('color');
        if (! $colorSeleccionado || ! $coloresDisponibles->contains($colorSeleccionado)) {
            $colorSeleccionado = $coloresDisponibles->first();
        }

        $variantesDelColor = $todasLasVariantes->where('color', $colorSeleccionado)->values();
        $variantePrincipal = $variantesDelColor->first();

        if (! $generoUrl && $variantePrincipal) {
            if ($variantePrincipal->gender === 'Woman') {
                $generoActivo = 'mujer';
            } elseif ($variantePrincipal->gender === 'Man') {
                $generoActivo = 'hombre';
            }
        }

        return view('detalleProducto', [
            'sneaker' => $sneaker,
            'variante_principal' => $variantePrincipal,
            'variantes_color' => $variantesDelColor,
            'colores_disponibles' => $coloresDisponibles,
            'color_activo' => $colorSeleccionado,
            'genero_activo' => $generoActivo,
        ]);
    }

    public function alertcarrito(): View
    {
        return view('alertcarrito');
    }
}
