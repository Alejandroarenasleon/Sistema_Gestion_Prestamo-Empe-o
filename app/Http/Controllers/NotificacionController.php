<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Notificacion;
use App\Models\PlantillaMensaje;
use App\Models\Prestamo;
use App\Services\NotificacionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificacionController extends Controller
{
    public function __construct(
        private NotificacionService $notificacionService,
    ) {}

    public function index(Request $request): View
    {
        $notificaciones = Notificacion::query()
            ->with(['cliente', 'prestamo', 'plantilla'])
            ->when($request->filled('cliente'), fn ($query) => $query->where('id_cliente', $request->integer('cliente')))
            ->when($request->filled('canal'), fn ($query) => $query->where('canal', $request->canal))
            ->orderByDesc('fecha_hora')
            ->paginate(30)
            ->withQueryString();

        return view('notificaciones.index', compact('notificaciones'));
    }

    public function enviarSimulado(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'id_cliente' => ['required', 'exists:cliente,id_cliente'],
            'id_prestamo' => ['nullable', 'exists:prestamo,id_prestamo'],
            'id_plantilla' => ['nullable', 'exists:plantilla_mensaje,id_plantilla'],
            'tipo' => ['required', 'string', 'max:30'],
            'canal' => ['required', 'in:WHATSAPP,SMS'],
        ]);

        $cliente = Cliente::findOrFail($datos['id_cliente']);
        $prestamo = isset($datos['id_prestamo']) ? Prestamo::find($datos['id_prestamo']) : null;
        $plantilla = isset($datos['id_plantilla']) ? PlantillaMensaje::find($datos['id_plantilla']) : null;

        $notificacion = $this->notificacionService->enviarSimulado(
            $cliente,
            $prestamo,
            $plantilla,
            $datos['canal'],
            $datos['tipo'],
        );

        return back()->with(
            'success',
            "Notificación simulada enviada a {$cliente->nombre_completo} por {$notificacion->canal}.",
        );
    }
}
