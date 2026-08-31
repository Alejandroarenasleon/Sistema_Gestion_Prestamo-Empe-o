<?php

namespace App\Http\Controllers;

use App\Models\AvisoRemate;
use App\Models\Prenda;
use App\Models\Remate;
use App\Models\SolicitudAprobacion;
use App\Services\AuditoriaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RemateController extends Controller
{
    public function __construct(
        private AuditoriaService $auditoriaService,
    ) {}

    public function index(): View
    {
        $prendas = Prenda::query()
            ->with(['prestamo.cliente', 'fotos'])
            ->where('estado', 'DISPONIBLE_REMATE')
            ->where('activo', true)
            ->orderBy('id_prenda')
            ->paginate(20);

        return view('remates.index', compact('prendas'));
    }

    public function solicitarVenta(Request $request, Prenda $prenda): RedirectResponse
    {
        abort_unless($prenda->estado === 'DISPONIBLE_REMATE', 422, 'La prenda no está disponible para remate.');

        $datos = $request->validate([
            'precio_ofertado' => ['required', 'numeric', 'min:0.01'],
        ]);

        DB::transaction(function () use ($prenda, $datos) {
            AvisoRemate::create([
                'id_prenda' => $prenda->id_prenda,
                'precio_ofertado' => $datos['precio_ofertado'],
                'aprobado' => null,
            ]);

            SolicitudAprobacion::create([
                'tipo' => 'VENTA_PRENDA',
                'referencia_id' => $prenda->id_prenda,
                'id_usuario_solicito' => Auth::id(),
                'estado' => 'PENDIENTE',
            ]);
        });

        $this->auditoriaService->log(
            Auth::id(),
            'prenda',
            $prenda->id_prenda,
            'MODIFICAR',
            ['estado' => 'DISPONIBLE_REMATE'],
            ['solicitud_venta' => true, 'precio_ofertado' => $datos['precio_ofertado']],
        );

        return back()->with('success', 'Propuesta de venta enviada para aprobación del administrador.');
    }

    public function registrarVenta(Request $request, Prenda $prenda): RedirectResponse
    {
        abort_unless($prenda->estado === 'DISPONIBLE_REMATE', 422, 'La prenda no está disponible para remate.');

        // US-16: no se permite registrar la venta sin aprobación del administrador.
        $aprobacion = SolicitudAprobacion::query()
            ->where('tipo', 'VENTA_PRENDA')
            ->where('referencia_id', $prenda->id_prenda)
            ->where('estado', 'APROBADO')
            ->latest()
            ->first();

        abort_unless($aprobacion, 403, 'La venta requiere la aprobación del administrador.');

        $datos = $request->validate([
            'precio_venta' => ['required', 'numeric', 'min:0.01'],
            'comprador' => ['required', 'string', 'max:150'],
        ]);

        // US-17: resultado = precio de venta - (capital + interés no pagado).
        $adeudado = $prenda->prestamo->saldoTotal();
        $resultado = round($datos['precio_venta'] - $adeudado, 2);

        DB::transaction(function () use ($prenda, $datos, $resultado, $adeudado) {
            Remate::create([
                'id_prenda' => $prenda->id_prenda,
                'categoria' => $prenda->categoria,
                'precio_venta' => $datos['precio_venta'],
                'comprador' => $datos['comprador'],
                'resultado' => $resultado,
                'fecha_venta' => now()->toDateString(),
                'id_usuario_aprobo' => Auth::id(),
            ]);

            $prenda->cambiarEstado('VENDIDA', 'Venta en remate registrada', Auth::id());

            // US-17: el préstamo asociado se cierra al concretarse el remate.
            $prenda->prestamo->update([
                'estado' => 'CANCELADO',
                'activo' => false,
            ]);
        });

        $this->auditoriaService->log(
            Auth::id(),
            'remate',
            $prenda->id_prenda,
            'CREAR',
            null,
            [
                'precio_venta' => $datos['precio_venta'],
                'adeudado' => $adeudado,
                'resultado' => $resultado,
                'categoria' => $prenda->categoria,
            ],
        );

        return redirect()
            ->route('remates.index')
            ->with('success', 'Venta en remate registrada. Resultado: Bs. ' . number_format($resultado, 2));
    }
}
