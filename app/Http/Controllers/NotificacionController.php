<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Notificacion;
use App\Models\PlantillaMensaje;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificacionController extends Controller
{
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
        $plantilla = isset($datos['id_plantilla'])
            ? PlantillaMensaje::find($datos['id_plantilla'])
            : null;

        Notificacion::create([
            'id_cliente' => $cliente->id_cliente,
            'id_prestamo' => $datos['id_prestamo'] ?? null,
            'id_plantilla' => $plantilla?->id_plantilla,
            'tipo' => $datos['tipo'],
            'canal' => $datos['canal'],
            'estado_envio' => 'EXITO',
        ]);

        return back()->with(
            'success',
            "Notificación simulada enviada a {$cliente->nombre_completo} por {$datos['canal']}.",
        );
    }
}
