<?php

namespace App\Http\Controllers;

use App\Models\Parametro;
use App\Services\AuditoriaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ParametroController extends Controller
{
    public function __construct(
        private AuditoriaService $auditoriaService,
    ) {}

    public function index(): View
    {
        $parametros = Parametro::query()
            ->orderBy('clave')
            ->get();

        return view('parametros.index', compact('parametros'));
    }

    public function update(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'parametros' => ['required', 'array'],
            'parametros.*.id_parametro' => ['required', 'exists:parametro,id_parametro'],
            'parametros.*.valor' => ['required', 'string', 'max:255'],
        ]);

        foreach ($datos['parametros'] as $item) {
            $parametro = Parametro::findOrFail($item['id_parametro']);
            $anterior = $parametro->toArray();

            $parametro->update([
                'valor' => $item['valor'],
                'id_usuario_modifico' => Auth::id(),
                'fecha_modificacion' => now(),
            ]);

            $this->auditoriaService->log(
                Auth::id(),
                'parametro',
                $parametro->id_parametro,
                'MODIFICAR',
                $anterior,
                $parametro->fresh()->toArray(),
            );
        }

        return back()->with('success', 'Parámetros actualizados.');
    }
}
