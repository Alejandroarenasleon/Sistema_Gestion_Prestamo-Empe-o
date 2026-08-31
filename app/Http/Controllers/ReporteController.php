<?php

namespace App\Http\Controllers;

use App\Models\CierreCaja;
use App\Models\Pago;
use App\Models\Remate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class ReporteController extends Controller
{
    public function index(): View
    {
        return view('reportes.index');
    }

    public function export(Request $request): Response
    {
        $datos = $request->validate([
            'tipo' => ['required', 'in:caja,intereses,remates'],
            'desde' => ['nullable', 'date'],
            'hasta' => ['nullable', 'date', 'after_or_equal:desde'],
        ]);

        $desde = $datos['desde'] ?? now()->startOfMonth()->toDateString();
        $hasta = $datos['hasta'] ?? now()->toDateString();

        [$vista, $contexto, $nombre] = match ($datos['tipo']) {
            'caja' => $this->reporteCaja($desde, $hasta),
            'intereses' => $this->reporteIntereses($desde, $hasta),
            'remates' => $this->reporteRemates($desde, $hasta),
        };

        $pdf = Pdf::loadView($vista, $contexto);

        return $pdf->download("reporte-{$datos['tipo']}-{$desde}-{$hasta}.pdf");
    }

    private function reporteCaja(string $desde, string $hasta): array
    {
        $cierres = CierreCaja::query()
            ->with('usuario')
            ->whereBetween('fecha', [$desde, $hasta])
            ->orderBy('fecha')
            ->get();

        return [
            'reportes.pdf.caja',
            compact('cierres', 'desde', 'hasta'),
            'caja',
        ];
    }

    private function reporteIntereses(string $desde, string $hasta): array
    {
        $pagos = Pago::query()
            ->with(['prestamo.cliente', 'usuario'])
            ->whereIn('tipo', ['INTERES', 'RENOVACION'])
            ->whereDate('fecha', '>=', $desde)
            ->whereDate('fecha', '<=', $hasta)
            ->orderBy('fecha')
            ->get();

        $total = $pagos->sum('monto');

        return [
            'reportes.pdf.intereses',
            compact('pagos', 'desde', 'hasta', 'total'),
            'intereses',
        ];
    }

    private function reporteRemates(string $desde, string $hasta): array
    {
        $remates = Remate::query()
            ->with(['prenda.prestamo.cliente', 'usuarioAprobo'])
            ->whereBetween('fecha_venta', [$desde, $hasta])
            ->orderBy('fecha_venta')
            ->get();

        $totalVentas = $remates->sum('precio_venta');
        $totalResultado = $remates->sum('resultado');

        $perdidasPorCategoria = $remates
            ->where('resultado', '<', 0)
            ->groupBy('categoria')
            ->map(fn ($grupo) => $grupo->sum('resultado'));

        return [
            'reportes.pdf.remates',
            compact('remates', 'desde', 'hasta', 'totalVentas', 'totalResultado', 'perdidasPorCategoria'),
            'remates',
        ];
    }
}
