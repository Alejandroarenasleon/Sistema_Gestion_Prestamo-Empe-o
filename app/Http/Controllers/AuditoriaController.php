<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditoriaController extends Controller
{
    public function index(Request $request): View
    {
        $registros = Auditoria::query()
            ->with('usuario')
            ->when($request->filled('entidad'), fn ($query) => $query->where('entidad', $request->entidad))
            ->when($request->filled('accion'), fn ($query) => $query->where('accion', $request->accion))
            ->when($request->filled('usuario'), fn ($query) => $query->where('id_usuario', $request->integer('usuario')))
            ->when($request->filled('desde'), fn ($query) => $query->whereDate('fecha_hora', '>=', $request->date('desde')))
            ->when($request->filled('hasta'), fn ($query) => $query->whereDate('fecha_hora', '<=', $request->date('hasta')))
            ->orderByDesc('fecha_hora')
            ->paginate(30)
            ->withQueryString();

        return view('auditoria.index', [
            'registros' => $registros,
            'filtros' => $request->only(['entidad', 'accion', 'usuario', 'desde', 'hasta']),
        ]);
    }
}
