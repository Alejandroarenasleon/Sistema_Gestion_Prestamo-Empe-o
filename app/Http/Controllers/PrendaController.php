<?php

namespace App\Http\Controllers;

use App\Models\Prestamo;
use App\Services\AuditoriaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrendaController extends Controller
{
    public function __construct(
        private AuditoriaService $auditoriaService,
    ) {}

    public function store(Request $request, Prestamo $prestamo): RedirectResponse
    {
        $datos = $request->validate([
            'categoria' => ['required', 'string', 'max:40'],
            'descripcion' => ['required', 'string', 'max:255'],
            'marca' => ['nullable', 'string', 'max:60'],
            'modelo' => ['nullable', 'string', 'max:60'],
            'material' => ['nullable', 'string', 'max:60'],
            'peso_gramos' => ['nullable', 'numeric', 'min:0'],
            'numero_serie_imei' => ['nullable', 'string', 'max:60'],
            'estado_fisico_obs' => ['nullable', 'string'],
            'avaluo' => ['required', 'numeric', 'min:0'],
            'fotos' => ['nullable', 'array'],
            'fotos.*' => ['image', 'max:5120'],
        ]);

        $prenda = $prestamo->prendas()->create([
            ...$datos,
            'estado' => 'RECIBIDA',
            'activo' => true,
        ]);

        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $foto) {
                $prenda->fotos()->create([
                    'url' => $foto->store("prendas/{$prenda->id_prenda}", 'public'),
                ]);
            }
        }

        $prenda->cambiarEstado('VIGENTE', 'Prenda agregada al préstamo', Auth::id());

        $this->auditoriaService->log(
            Auth::id(),
            'prenda',
            $prenda->id_prenda,
            'CREAR',
            null,
            $prenda->toArray(),
        );

        return redirect()
            ->route('prestamos.show', $prestamo)
            ->with('success', 'Prenda registrada correctamente.');
    }
}
