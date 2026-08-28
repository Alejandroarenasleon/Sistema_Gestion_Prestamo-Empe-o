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

        return view('pagos.create', compact('prestamo', 'prestamosActivos'));
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'id_prestamo' => ['required', 'exists:prestamo,id_prestamo'],
            'tipo' => ['required', 'in:INTERES,ABONO,CANCELACION,RENOVACION'],
            'monto' => ['required', 'numeric', 'min:0.01'],
            'nueva_fecha_vencimiento' => ['nullable', 'date', 'required_if:tipo,RENOVACION'],
        ]);

        $prestamo = Prestamo::findOrFail($datos['id_prestamo']);

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
