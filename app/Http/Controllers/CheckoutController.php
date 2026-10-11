<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Checkout y pagos. La página de checkout lee el carrito real; los endpoints
 * de pedido, envío, cupones y MercadoPago son stubs pendientes de portar.
 */
class CheckoutController extends Controller
{
    public function checkout(): View
    {
        $user = Auth::user();

        $carrito = Cart::where('idCostumer', $user->idCostumer)
            ->where('status', 'active')
            ->with('details.variant.sneaker')
            ->first();

        $items = [];
        $total = 0;

        foreach ($carrito?->details ?? [] as $detalle) {
            $total += $detalle->priceAtAddition * $detalle->quantity;

            $items[] = [
                'detalle' => $detalle,
                'nombre' => $detalle->variant->sneaker->name,
                'imagen' => $detalle->variant->getImage(),
                'talla' => $detalle->variant->size,
                'cantidad' => $detalle->quantity,
                'precio' => $detalle->priceAtAddition,
            ];
        }

        $direcciones = $user->addresses()->get();

        return view('checkout', [
            'items' => $items,
            'total' => $total,
            'direcciones' => $direcciones,
            'direccion_default' => $direcciones->first(),
            'carrito_id' => $carrito?->idCart ?? 0,
        ]);
    }

    public function pagoExitoso(int $id_orden): View|RedirectResponse
    {
        $orden = Order::findOrFail($id_orden);

        if ($orden->idCostumer !== Auth::id()) {
            return redirect()->route('home');
        }

        // TODO: portar pago_exitoso: marcar 'enviado', enviar emails.compra y registrar Payment
        return view('pago_exitoso', ['orden' => $orden]);
    }

    public function failure(): RedirectResponse
    {
        // TODO: portar checkout_failure
        return redirect()->route('checkout');
    }

    public function pending(): RedirectResponse
    {
        // TODO: portar checkout_pending
        return redirect()->route('checkout');
    }

    public function procesarPedido(Request $request): JsonResponse
    {
        // TODO: portar api_procesar_pedido (Order + DetailOrder + preferencia de MercadoPago).
        // El access token debe ir en .env, nunca en el código.
        return $this->pendiente();
    }

    public function nuevaDireccion(Request $request): JsonResponse
    {
        // TODO: portar api_nueva_direccion
        return $this->pendiente();
    }

    public function calcularEnvio(Request $request): JsonResponse
    {
        // TODO: portar api_calcular_envio (cotizar_envio_envia_com de stepOne/utils.py)
        return $this->pendiente();
    }

    public function validarCupon(Request $request): JsonResponse
    {
        // TODO: portar api_validar_cupon
        return $this->pendiente();
    }

    private function pendiente(): JsonResponse
    {
        return response()->json(['status' => 'error', 'message' => 'Pendiente de implementar'], 501);
    }
}
