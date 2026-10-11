<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

// Nombres de ruta equivalentes a los de stepOne/urls.py, login/urls.py y signin/urls.py

Route::get('/', [ShopController::class, 'home'])->name('home');
Route::get('/productos', [ShopController::class, 'productos'])->name('productos');
Route::get('/productos/{idsneaker}', [ShopController::class, 'detalleProducto'])->whereNumber('idsneaker')->name('detalleProducto');
Route::match(['get', 'post'], '/alertcarrito', [ShopController::class, 'alertcarrito'])->name('alertcarrito');

// --- login/ ---
Route::prefix('login')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/', [AuthController::class, 'login']);
        Route::get('/password_forgot', [AuthController::class, 'showPasswordForgot'])->name('login.password_forgot');
        Route::post('/password_forgot', [AuthController::class, 'passwordForgot']);
        Route::get('/password_recovery', [AuthController::class, 'showPasswordRecovery'])->name('login.password_recovery');
        Route::post('/password_recovery', [AuthController::class, 'passwordRecovery']);
    });

    Route::middleware('auth')->group(function () {
        Route::get('/logout', [AuthController::class, 'logout'])->name('login.logout');
        Route::get('/mi-cuenta', [AuthController::class, 'perfil'])->name('login.perfil');
        Route::post('/mi-cuenta', [AuthController::class, 'actualizarPerfil']);
    });
});

// --- signin/ ---
Route::middleware('guest')->group(function () {
    Route::get('/signin', [AuthController::class, 'showSignin'])->name('signin');
    Route::post('/signin', [AuthController::class, 'signin']);
});

// --- Carrito (AJAX) ---
Route::post('/api/agregar-carrito', [CartController::class, 'agregar'])->name('api_agregar_carrito');

Route::middleware('auth')->group(function () {
    Route::get('/api/obtener-carrito', [CartController::class, 'obtener'])->name('api_obtener');
    Route::post('/api/actualizar-carrito', [CartController::class, 'actualizar'])->name('api_actualizar');

    // --- Checkout y pagos ---
    Route::get('/checkout', [CheckoutController::class, 'checkout'])->name('checkout');
    Route::get('/pago-exitoso/{id_orden}', [CheckoutController::class, 'pagoExitoso'])->whereNumber('id_orden')->name('pago_exitoso');
    Route::get('/checkout/failure', [CheckoutController::class, 'failure'])->name('checkout_failure');
    Route::get('/checkout/pending', [CheckoutController::class, 'pending'])->name('checkout_pending');
    Route::post('/api/procesar-pedido', [CheckoutController::class, 'procesarPedido'])->name('api_procesar_pedido');
    Route::post('/api/nueva-direccion', [CheckoutController::class, 'nuevaDireccion'])->name('api_nueva_direccion');
    Route::post('/api/calcular-envio', [CheckoutController::class, 'calcularEnvio'])->name('api_calcular_envio');
    Route::post('/api/validar-cupon', [CheckoutController::class, 'validarCupon'])->name('api_validar_cupon');
});
