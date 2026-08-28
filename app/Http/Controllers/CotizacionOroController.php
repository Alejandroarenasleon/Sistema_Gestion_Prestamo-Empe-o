<?php

namespace App\Http\Controllers;

use App\Models\CotizacionOro;
use App\Services\AuditoriaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CotizacionOroController extends Controller
{
    public function __construct(
        private AuditoriaService $auditoriaService,
    ) {}

    public function index(): View
    {
        $cotizaciones = CotizacionOro::query()
            ->with('usuario')
            ->orderByDesc('fecha')
            ->orderBy('quilate')
            ->paginate(30);

        return view('cotizacion-oro.index', compact('cotizaciones'));
    }

    public function store(Request $request): RedirectResponse
    {
        $quilatesPermitidos = ['10K', '14K', '18K', '21K', '22K', '24K'];

        $datos = $request->validate([
            'quilate' => ['required', 'string', 'max:10', 'in:' . implode(',', $quilatesPermitidos)],
            'precio_gramo' => ['required', 'numeric', 'min:0.01'],
            'fecha' => ['required', 'date'],
        ]);

        $cotizacionAnterior = CotizacionOro::where('quilate', $datos['quilate'])
            ->orderByDesc('fecha')
            ->first();

        $cotizacion = CotizacionOro::create([
            ...$datos,
            'id_usuario' => Auth::id(),
        ]);

        $this->auditoriaService->log(
            Auth::id(),
            'cotizacion_oro',
            $cotizacion->id_cotizacion,
            'CREAR',
            $cotizacionAnterior?->toArray(),
            $cotizacion->toArray(),
        );

        return back()->with('success', 'Cotización registrada.');
    }
}
