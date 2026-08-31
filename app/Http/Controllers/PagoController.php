<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Prestamo;
use App\Services\PdfService;
use App\Services\PrestamoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PagoController extends Controller
{
    public function __construct(
        private PrestamoService $prestamoService,
        private PdfService $pdfService,
    ) {}

    public function create(Request $request): View
    {
        $prestamo = $request->filled('prestamo')
            ? Prestamo::with('cliente', 'prendas')->findOrFail($request->integer('prestamo'))
            : null;

        $prestamosActivos = Prestamo::query()
            ->with('cliente')
            ->whereIn('estado', ['VIGENTE', 'MORA'])
            ->where('activo', true)
            ->orderByDesc('fecha_emision')
            ->get();

        // Determinar tipos de pago disponibles según lo que falte pagar
        $tiposDisponibles = $this->obtenerTiposPagoDisponibles($prestamo);

        return view('pagos.create', compact('prestamo', 'prestamosActivos', 'tiposDisponibles'));
    }

    private function obtenerTiposPagoDisponibles(?Prestamo $prestamo): array
    {
        if (! $prestamo) {
            return [
                'INTERES' => 'Pago de Interés',
                'ABONO' => 'Abono a Capital',
                'CANCELACION' => 'Cancelación Total',
                'RENOVACION' => 'Renovación (extiende 1 mes)',
            ];
        }

        $tipos = [];

        $interesPendiente = $prestamo->interesPendiente();
        $saldoCapital = $prestamo->saldoCapital();

        if ($interesPendiente > 0) {
            $tipos['INTERES'] = 'Pago de Interés (pendiente: Bs. ' . number_format($interesPendiente, 2) . ')';
        }

        if ($saldoCapital > 0) {
            $tipos['ABONO'] = 'Abono a Capital (saldo: Bs. ' . number_format($saldoCapital, 2) . ')';
            $tipos['CANCELACION'] = 'Cancelación Total (saldo total: Bs. ' . number_format($prestamo->saldoTotal(), 2) . ')';
        }

        // Renovación siempre disponible si hay capital pendiente
        if ($saldoCapital > 0) {
            $tipos['RENOVACION'] = 'Renovación (extiende 1 mes)';
        }

        // Fallback: si no hay nada pendiente, permitir al menos ver el estado
        if (empty($tipos)) {
            $tipos = [
                'INTERES' => 'Pago de Interés (sin pendiente)',
                'ABONO' => 'Abono a Capital (sin pendiente)',
                'CANCELACION' => 'Cancelación Total (sin pendiente)',
                'RENOVACION' => 'Renovación (extiende 1 mes)',
            ];
        }

        return $tipos;
    }

    public function store(Request $request): RedirectResponse
    {
        $prestamo = Prestamo::findOrFail($request->integer('id_prestamo'));
        $tiposDisponibles = $this->obtenerTiposPagoDisponibles($prestamo);

        $datos = $request->validate([
            'id_prestamo' => ['required', 'exists:prestamo,id_prestamo'],
            'tipo' => ['required', 'in:' . implode(',', array_keys($tiposDisponibles))],
            'monto' => ['required', 'numeric', 'min:0.01'],
            'nueva_fecha_vencimiento' => ['nullable', 'date', 'required_if:tipo,RENOVACION'],
        ]);

        $pago = $this->prestamoService->registrarPago(
            $prestamo,
            $datos['tipo'],
            (float) $datos['monto'],
            Auth::id(),
        );

        $this->pdfService->generarRecibo($pago);

        return redirect()
            ->route('pagos.recibo', $pago)
            ->with('success', 'Pago registrado correctamente.');
    }

    public function recibo(Pago $pago): BinaryFileResponse
    {
        $pago->load(['recibo', 'prestamo.cliente']);

        if (! $pago->recibo) {
            $this->pdfService->generarRecibo($pago);
            $pago->refresh()->load('recibo');
        }

        $ruta = Storage::disk('public')->path($pago->recibo->pdf_url);

        return response()->download($ruta, "recibo-{$pago->id_pago}.pdf");
    }
}
