<?php

use App\Http\Controllers\AprobacionController;
use App\Http\Controllers\AuditoriaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CajaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CotizacionOroController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\ParametroController;
use App\Http\Controllers\PrestamoController;
use App\Http\Controllers\RemateController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        if (view()->exists('auth.login')) {
            return view('auth.login');
        }
        if (view()->exists('auth/login')) {
            return view('auth/login');
        }

        return "Error: Laravel no encuentra las vistas. Está buscando en la carpeta: " . resource_path('views');
    })->name('login');

    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('clientes', ClienteController::class)->except(['edit', 'update', 'destroy']);

    Route::resource('prestamos', PrestamoController::class)->except(['edit', 'update', 'destroy']);
    Route::get('prestamos/{prestamo}/contrato', [PrestamoController::class, 'contrato'])->name('prestamos.contrato');

    Route::get('pagos/create', [PagoController::class, 'create'])->name('pagos.create');
    Route::post('pagos', [PagoController::class, 'store'])->name('pagos.store');
    Route::get('pagos/{pago}/recibo', [PagoController::class, 'recibo'])->name('pagos.recibo');

    Route::get('remates', [RemateController::class, 'index'])->name('remates.index');
    Route::post('remates/{prenda}/solicitar', [RemateController::class, 'solicitarVenta'])->name('remates.solicitar');

    Route::get('caja', [CajaController::class, 'index'])->name('caja.index');
    Route::post('caja/generar', [CajaController::class, 'generarResumen'])->name('caja.generar');
    Route::post('caja/{cierre}/confirmar', [CajaController::class, 'confirmarArqueo'])->name('caja.confirmar');

    Route::get('reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('reportes/exportar', [ReporteController::class, 'export'])->name('reportes.exportar');

    Route::get('notificaciones', [NotificacionController::class, 'index'])->name('notificaciones.index');
    Route::post('notificaciones/enviar-simulado', [NotificacionController::class, 'enviarSimulado'])->name('notificaciones.enviar');

    Route::middleware('admin')->group(function () {
        Route::get('aprobaciones', [AprobacionController::class, 'index'])->name('aprobaciones.index');
        Route::post('aprobaciones/{solicitud}/aprobar', [AprobacionController::class, 'aprobar'])->name('aprobaciones.aprobar');
        Route::post('aprobaciones/{solicitud}/rechazar', [AprobacionController::class, 'rechazar'])->name('aprobaciones.rechazar');

        Route::post('remates/{prenda}/registrar', [RemateController::class, 'registrarVenta'])->name('remates.registrar');

        Route::get('parametros', [ParametroController::class, 'index'])->name('parametros.index');
        Route::put('parametros', [ParametroController::class, 'update'])->name('parametros.update');

        Route::get('cotizacion-oro', [CotizacionOroController::class, 'index'])->name('cotizacion-oro.index');
        Route::post('cotizacion-oro', [CotizacionOroController::class, 'store'])->name('cotizacion-oro.store');

        Route::get('auditoria', [AuditoriaController::class, 'index'])->name('auditoria.index');

        Route::resource('usuarios', UsuarioController::class)->except(['show']);
    });
});