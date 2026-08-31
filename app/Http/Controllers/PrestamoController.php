<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Prestamo;
use App\Models\SolicitudAprobacion;
use App\Services\PdfService;
use App\Services\PrestamoService;
use App\Services\PrendaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PrestamoController extends Controller
{
    public function __construct(
        private PrestamoService $prestamoService,
        private PdfService $pdfService,
        private PrendaService $prendaService,
    ) {}

    public function index(Request $request): View
    {
        $prestamos = Prestamo::query()
            ->with(['cliente', 'prendas'])
            ->when($request->filled('estado'), fn ($query) => $query->where('estado', $request->estado))
            ->when($request->filled('q'), function ($query) use ($request) {
                $termino = $request->string('q')->trim()->toString();
                $query->whereHas('cliente', fn ($q) => $q->buscar($termino));
            })
            ->orderByDesc('fecha_emision')
            ->paginate(20)
            ->withQueryString();

        return view('prestamos.index', compact('prestamos'));
    }

    public function create(Request $request): View
    {
        $cliente = $request->filled('cliente')
            ? Cliente::findOrFail($request->integer('cliente'))
            : null;

        return view('prestamos.create', compact('cliente'));
    }

    public function store(Request $request): RedirectResponse
    {
        $categoriasOro = ['oro', 'joyas', 'joya', 'joyería', 'joyeria'];
        $categoriasElectronicos = ['electronico', 'electrónico', 'herramienta'];

        $datos = $request->validate([
            'id_cliente' => ['required', 'exists:cliente,id_cliente'],
            'monto_capital' => ['required', 'numeric', 'min:1'],
            'tasa_interes_mensual' => ['required', 'numeric', 'min:0'],
            'fecha_emision' => ['required', 'date'],
            'fecha_vencimiento' => ['required', 'date', 'after:fecha_emision'],
            'prendas' => ['required', 'array', 'min:1'],
            'prendas.*.categoria' => ['required', 'string', 'max:40'],
            'prendas.*.descripcion' => ['required', 'string', 'max:255'],
            'prendas.*.marca' => ['nullable', 'string', 'max:60'],
            'prendas.*.modelo' => ['nullable', 'string', 'max:60'],
            'prendas.*.material' => ['nullable', 'string', 'max:60'],
            'prendas.*.peso_gramos' => ['nullable', 'numeric', 'min:0'],
            'prendas.*.numero_serie_imei' => ['nullable', 'string', 'max:60'],
            'prendas.*.estado_fisico_obs' => ['nullable', 'string'],
            'prendas.*.avaluo' => ['required', 'numeric', 'min:0'],
            'prendas.*.fotos' => ['nullable', 'array'],
            'prendas.*.fotos.*' => ['image', 'max:5120'],
        ], [
            'prendas.required' => 'Debe agregar al menos una prenda en garantía.',
            'prendas.min' => 'Debe agregar al menos una prenda en garantía.',
            'prendas.*.categoria.required' => 'La categoría de la prenda es obligatoria.',
            'prendas.*.descripcion.required' => 'La descripción de la prenda es obligatoria.',
            'prendas.*.avaluo.required' => 'El avalúo de la prenda es obligatorio.',
            'prendas.*.avaluo.numeric' => 'El avalúo debe ser un número válido.',
            'prendas.*.peso_gramos.numeric' => 'El peso debe ser un número válido.',
        ]);

        foreach ($request->input('prendas', []) as $index => $prendaData) {
            $cat = mb_strtolower(trim($prendaData['categoria'] ?? ''));

            if (in_array($cat, $categoriasOro, true) && empty($prendaData['peso_gramos'])) {
                return back()->withInput()->with('error', "La prenda #{$index} ({$prendaData['categoria']}) requiere peso en gramos.");
            }

            if (in_array($cat, $categoriasElectronicos, true) && empty($prendaData['numero_serie_imei'])) {
                return back()->withInput()->with('error', "La prenda #{$index} ({$prendaData['categoria']}) requiere número de serie o IMEI.");
            }

            $tieneFotos = $request->hasFile("prendas.{$index}.fotos") && count($request->file("prendas.{$index}.fotos")) > 0;

            if (! $tieneFotos && empty($prendaData['estado_fisico_obs'])) {
                return back()->withInput()->with('error', "La prenda #{$index} ({$prendaData['categoria']}) requiere observaciones del estado físico si no se adjunta fotografía.");
            }
        }

        $cliente = Cliente::findOrFail($datos['id_cliente']);

        if ($cliente->alerta_riesgo) {
            $existePendiente = SolicitudAprobacion::where('tipo', 'PRESTAMO_RIESGO')
                ->where('referencia_id', $cliente->id_cliente)
                ->where('estado', 'PENDIENTE')
                ->exists();

            if (! $existePendiente) {
                $prestamoData = $request->only([
                    'id_cliente', 'monto_capital', 'tasa_interes_mensual', 'fecha_emision', 'fecha_vencimiento',
                ]);
                $prestamoData['requiere_aprobacion'] = true;

                $prestamo = $this->prestamoService->crearPrestamo($prestamoData, Auth::id());

                foreach ($request->input('prendas', []) as $prendaData) {
                    $prenda = $prestamo->prendas()->create([
                        'categoria' => $prendaData['categoria'],
                        'descripcion' => $prendaData['descripcion'],
                        'marca' => $prendaData['marca'] ?? null,
                        'modelo' => $prendaData['modelo'] ?? null,
                        'material' => $prendaData['material'] ?? null,
                        'peso_gramos' => $prendaData['peso_gramos'] ?? null,
                        'numero_serie_imei' => $prendaData['numero_serie_imei'] ?? null,
                        'estado_fisico_obs' => $prendaData['estado_fisico_obs'] ?? null,
                        'avaluo' => $prendaData['avaluo'],
                        'estado' => 'VIGENTE',
                        'activo' => true,
                    ]);
                    $prenda->cambiarEstado('VIGENTE', 'Prenda incluida en préstamo (pendiente aprobación)', Auth::id());
                }

                SolicitudAprobacion::create([
                    'tipo' => 'PRESTAMO_RIESGO',
                    'referencia_id' => $prestamo->id_prestamo,
                    'id_usuario_solicito' => Auth::id(),
                    'estado' => 'PENDIENTE',
                    'fecha_solicitud' => now(),
                ]);
            }

            return back()
                ->withInput()
                ->with('error', "El cliente {$cliente->nombre_completo} tiene alerta de riesgo ({$cliente->motivo_alerta}). "
                    . 'Se generó una solicitud de aprobación pendiente para el Administrador.');
        }

        $montoMaximo = 0.0;
        foreach ($request->input('prendas', []) as $prendaData) {
            $montoMaximo += $this->prendaService->calcularMaximoPrestamo(
                (float) $prendaData['avaluo'],
                $prendaData['categoria']
            );
        }

        $montoSolicitado = (float) $datos['monto_capital'];

        if ($montoSolicitado > $montoMaximo && $montoMaximo > 0) {
            $existePendienteMonto = SolicitudAprobacion::where('tipo', 'PRESTAMO_RIESGO')
                ->where('referencia_id', $datos['id_cliente'])
                ->where('estado', 'PENDIENTE')
                ->exists();

            if (! $existePendienteMonto) {
                $prestamoData = $request->only([
                    'id_cliente', 'monto_capital', 'tasa_interes_mensual', 'fecha_emision', 'fecha_vencimiento',
                ]);
                $prestamoData['requiere_aprobacion'] = true;

                $prestamo = $this->prestamoService->crearPrestamo($prestamoData, Auth::id());

                foreach ($request->input('prendas', []) as $prendaData) {
                    $prenda = $prestamo->prendas()->create([
                        'categoria' => $prendaData['categoria'],
                        'descripcion' => $prendaData['descripcion'],
                        'marca' => $prendaData['marca'] ?? null,
                        'modelo' => $prendaData['modelo'] ?? null,
                        'material' => $prendaData['material'] ?? null,
                        'peso_gramos' => $prendaData['peso_gramos'] ?? null,
                        'numero_serie_imei' => $prendaData['numero_serie_imei'] ?? null,
                        'estado_fisico_obs' => $prendaData['estado_fisico_obs'] ?? null,
                        'avaluo' => $prendaData['avaluo'],
                        'estado' => 'VIGENTE',
                        'activo' => true,
                    ]);

                    if ($request->hasFile("prendas.{$index}.fotos")) {
                        foreach ($request->file("prendas.{$index}.fotos") as $foto) {
                            $prenda->fotos()->create([
                                'url' => $foto->store("prendas/{$prenda->id_prenda}", 'public'),
                            ]);
                        }
                    }

                    $prenda->cambiarEstado('VIGENTE', 'Prenda incluida en préstamo (pendiente aprobación)', Auth::id());
                }

                SolicitudAprobacion::create([
                    'tipo' => 'PRESTAMO_RIESGO',
                    'referencia_id' => $prestamo->id_prestamo,
                    'id_usuario_solicito' => Auth::id(),
                    'estado' => 'PENDIENTE',
                    'fecha_solicitud' => now(),
                ]);
            }

            return back()
                ->withInput()
                ->with('error', "El monto solicitado (Bs. " . number_format($montoSolicitado, 2) . ") supera el máximo permitido (Bs. " . number_format($montoMaximo, 2) . "). "
                    . 'Se generó una solicitud de aprobación para el Administrador.');
        }

        unset($datos['prendas']);
        $prestamo = $this->prestamoService->crearPrestamo($request->only([
            'id_cliente', 'monto_capital', 'tasa_interes_mensual', 'fecha_emision', 'fecha_vencimiento',
        ]), Auth::id());

        $prendasData = $request->input('prendas', []);

        foreach ($prendasData as $index => $prendaData) {
            $prenda = $prestamo->prendas()->create([
                'categoria' => $prendaData['categoria'],
                'descripcion' => $prendaData['descripcion'],
                'marca' => $prendaData['marca'] ?? null,
                'modelo' => $prendaData['modelo'] ?? null,
                'material' => $prendaData['material'] ?? null,
                'peso_gramos' => $prendaData['peso_gramos'] ?? null,
                'numero_serie_imei' => $prendaData['numero_serie_imei'] ?? null,
                'estado_fisico_obs' => $prendaData['estado_fisico_obs'] ?? null,
                'avaluo' => $prendaData['avaluo'],
                'estado' => 'VIGENTE',
                'activo' => true,
            ]);

            if ($request->hasFile("prendas.{$index}.fotos")) {
                foreach ($request->file("prendas.{$index}.fotos") as $foto) {
                    $prenda->fotos()->create([
                        'url' => $foto->store("prendas/{$prenda->id_prenda}", 'public'),
                    ]);
                }
            }

            $prenda->cambiarEstado('VIGENTE', 'Prenda incluida en préstamo', Auth::id());
        }

        $this->pdfService->generarContrato($prestamo->fresh(['cliente', 'prendas']));

        return redirect()
            ->route('prestamos.show', $prestamo)
            ->with('success', 'Préstamo registrado. Contrato generado.');
    }

    public function show(Prestamo $prestamo): View
    {
        $prestamo->load(['cliente', 'prendas.fotos', 'pagos', 'contrato', 'usuarioRegistro']);

        return view('prestamos.show', compact('prestamo'));
    }

    public function contrato(Prestamo $prestamo): BinaryFileResponse
    {
        $prestamo->load('contrato');

        if (! $prestamo->contrato) {
            $this->pdfService->generarContrato($prestamo);
            $prestamo->refresh()->load('contrato');
        }

        $ruta = Storage::disk('public')->path($prestamo->contrato->pdf_url);

        return response()->download($ruta, "contrato-{$prestamo->id_prestamo}.pdf");
    }
}
