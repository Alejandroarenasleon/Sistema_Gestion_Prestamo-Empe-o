<?php

namespace App\Http\Controllers;

use App\Models\CierreCaja;
use App\Services\AuditoriaService;
use App\Services\CajaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CajaController extends Controller
{
    public function __construct(
        private CajaService $cajaService,
        private AuditoriaService $auditoriaService,
    ) {}

    public function index(Request $request): View
    {
        $fecha = ($request->date('fecha') ?? now())->toDateString();

        $cierre = CierreCaja::query()
            ->with('usuario')
            ->where('fecha', $fecha)
            ->first();

        $efectivoEsperado = $this->cajaService->calcularEfectivoEsperado($fecha);

        return view('caja.index', compact('fecha', 'cierre', 'efectivoEsperado'));
    }

    public function generarResumen(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'fecha' => ['required', 'date'],
        ]);

        $this->cajaService->crearCierreCaja(
            $datos['fecha'],
            Auth::id(),
        );

        return redirect()
            ->route('caja.index', ['fecha' => $datos['fecha']])
            ->with('success', 'Resumen de caja generado.');
    }

    public function confirmarArqueo(Request $request, CierreCaja $cierre): RedirectResponse
    {
        $datos = $request->validate([
            'efectivo_fisico' => ['required', 'numeric', 'min:0'],
            'observacion' => ['nullable', 'string', 'max:255'],
        ]);

        $anterior = $cierre->toArray();
        $diferencia = $datos['efectivo_fisico'] - $cierre->efectivo_esperado;

        $cierre->update([
            'efectivo_fisico' => $datos['efectivo_fisico'],
            'diferencia' => $diferencia,
            'observacion' => $datos['observacion'] ?? null,
            'confirmado' => true,
        ]);

        $this->auditoriaService->log(
            Auth::id(),
            'cierre_caja',
            $cierre->id_cierre,
            'MODIFICAR',
            $anterior,
            $cierre->fresh()->toArray(),
        );

        return redirect()
            ->route('caja.index', ['fecha' => $cierre->fecha->toDateString()])
            ->with('success', 'Arqueo confirmado.');
    }
}
