<?php

namespace App\Http\Controllers;

use App\Models\Prenda;
use App\Models\Prestamo;
use App\Models\SolicitudAprobacion;
use App\Services\CajaService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private CajaService $cajaService,
    ) {}

    public function index(): View
    {
        $hoy = now()->toDateString();
        $finSemana = now()->addWeek()->toDateString();

        return view('dashboard.index', [
            'cajaDelDia' => $this->cajaService->calcularEfectivoEsperado($hoy),
            'prendasPorVencerSemana' => Prenda::query()
                ->whereHas('prestamo', function ($query) use ($hoy, $finSemana) {
                    $query->whereBetween('fecha_vencimiento', [$hoy, $finSemana])
                        ->whereIn('estado', ['VIGENTE', 'MORA']);
                })
                ->where('activo', true)
                ->count(),
            'enMora' => Prestamo::query()
                ->where('estado', 'MORA')
                ->where('activo', true)
                ->count(),
            'disponiblesRemate' => Prenda::query()
                ->where('estado', 'DISPONIBLE_REMATE')
                ->where('activo', true)
                ->count(),
            'solicitudesPendientes' => SolicitudAprobacion::query()
                ->where('estado', 'PENDIENTE')
                ->count(),
        ]);
    }
}
