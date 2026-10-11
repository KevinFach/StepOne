<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Endpoints AJAX del carrito (header.js / detalleProducto.js).
 * Stubs: la lógica de stock y de Cart/DetailCart sigue en stepOne/views.py del proyecto Django.
 */
class CartController extends Controller
{
    public function agregar(Request $request): JsonResponse
    {
        // TODO: portar agregar_al_carrito (validar stock, Cart activo, DetailCart, total_items)
        return response()->json(['status' => 'error', 'message' => 'Carrito pendiente de implementar'], 501);
    }

    public function obtener(Request $request): JsonResponse
    {
        // TODO: portar api_obtener_carrito (items del Cart activo con imagen, talla, color, precio)
        return response()->json(['status' => 'success', 'items' => [], 'total' => 0]);
    }

    public function actualizar(Request $request): JsonResponse
    {
        // TODO: portar api_actualizar_cantidad (acciones 'sumar', 'restar', 'eliminar')
        return response()->json(['status' => 'error', 'message' => 'Carrito pendiente de implementar'], 501);
    }
}
