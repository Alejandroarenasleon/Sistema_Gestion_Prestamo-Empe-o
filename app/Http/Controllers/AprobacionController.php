<?php

namespace App\Http\Controllers;

use App\Models\Prestamo;
use App\Models\SolicitudAprobacion;
use App\Services\AuditoriaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AprobacionController extends Controller
{
    public function __construct(
        private AuditoriaService $auditoriaService,
    ) {}

    public function index(): View
    {
        $solicitudes = SolicitudAprobacion::query()
            ->with(['usuarioSolicito', 'usuarioResolvio'])
            ->orderByRaw("CASE estado WHEN 'PENDIENTE' THEN 0 ELSE 1 END")
            ->orderByDesc('fecha_solicitud')
            ->paginate(20);

        return view('aprobaciones.index', compact('solicitudes'));
    }

    public function aprobar(SolicitudAprobacion $solicitud): RedirectResponse
    {
        abort_unless($solicitud->estado === 'PENDIENTE', 422, 'La solicitud ya fue resuelta.');

        $anterior = $solicitud->toArray();

        DB::transaction(function () use ($solicitud) {
            $solicitud->update([
                'estado' => 'APROBADO',
                'id_usuario_resolvio' => Auth::id(),
                'fecha_resolucion' => now(),
            ]);

            $this->resolverSolicitud($solicitud, true);
        });

        $this->auditoriaService->log(
            Auth::id(),
            'solicitud_aprobacion',
            $solicitud->id_solicitud,
            'MODIFICAR',
            $anterior,
            $solicitud->fresh()->toArray(),
        );

        return back()->with('success', 'Solicitud aprobada.');
    }

    public function rechazar(Request $request, SolicitudAprobacion $solicitud): RedirectResponse
    {
        abort_unless($solicitud->estado === 'PENDIENTE', 422, 'La solicitud ya fue resuelta.');

        $datos = $request->validate([
            'motivo' => ['required', 'string', 'max:255'],
        ]);

        $anterior = $solicitud->toArray();

        DB::transaction(function () use ($solicitud, $datos) {
            $solicitud->update([
                'estado' => 'RECHAZADO',
                'motivo' => $datos['motivo'],
                'id_usuario_resolvio' => Auth::id(),
                'fecha_resolucion' => now(),
            ]);

            $this->resolverSolicitud($solicitud, false);
        });

        $this->auditoriaService->log(
            Auth::id(),
            'solicitud_aprobacion',
            $solicitud->id_solicitud,
            'MODIFICAR',
            $anterior,
            $solicitud->fresh()->toArray(),
        );

        return back()->with('success', 'Solicitud rechazada. El préstamo ha sido cancelado.');
    }

    private function resolverSolicitud(SolicitudAprobacion $solicitud, bool $aprobado): void
    {
        match ($solicitud->tipo) {
            'PRESTAMO_RIESGO' => $this->resolverPrestamoRiesgo($solicitud, $aprobado),
            'VENTA_PRENDA', 'AVISO_REMATE' => $this->resolverAvisoRemate($solicitud, $aprobado),
            default => null,
        };
    }

    private function resolverPrestamoRiesgo(SolicitudAprobacion $solicitud, bool $aprobado): void
    {
        $prestamo = Prestamo::find($solicitud->referencia_id);

        if (! $prestamo) {
            return;
        }

        if ($aprobado) {
            $prestamo->update(['requiere_aprobacion' => false]);
        } else {
            $prestamo->update([
                'estado' => 'CANCELADO',
                'activo' => false,
            ]);

            foreach ($prestamo->prendas as $prenda) {
                $prenda->cambiarEstado('DEVUELTA', 'Préstamo rechazado por administrador', Auth::id());
            }
        }
    }

    private function resolverAvisoRemate(SolicitudAprobacion $solicitud, bool $aprobado): void
    {
        $aviso = \App\Models\AvisoRemate::query()
            ->where('id_prenda', $solicitud->referencia_id)
            ->latest('fecha_solicitud')
            ->first();

        if ($aviso) {
            $aviso->update([
                'aprobado' => $aprobado,
                'id_usuario_aprobo' => Auth::id(),
                'fecha_aprobacion' => now(),
            ]);
        }
    }
}
