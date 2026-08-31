<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Services\AuditoriaService;
use App\Services\ClienteService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ClienteController extends Controller
{
    public function __construct(
        private ClienteService $clienteService,
        private AuditoriaService $auditoriaService,
    ) {}

    public function index(Request $request): View
    {
        $termino = $request->string('q')->trim()->toString();

        $clientes = Cliente::query()
            ->activos()
            ->when($termino !== '', fn ($query) => $query->buscar($termino))
            ->orderBy('nombre_completo')
            ->paginate(20)
            ->withQueryString();

        return view('clientes.index', compact('clientes', 'termino'));
    }

    public function create(): View
    {
        return view('clientes.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'ci' => ['required', 'string', 'max:20', 'unique:cliente,ci'],
            'nombre_completo' => ['required', 'string', 'max:150'],
            'direccion' => ['nullable', 'string', 'max:200'],
            'celular' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
            'referencia_contacto' => ['nullable', 'string', 'max:150'],
            'foto_ci_anverso' => ['required', 'image', 'max:5120'],
            'foto_ci_reverso' => ['required', 'image', 'max:5120'],
            'comprobante_domicilio' => ['nullable', 'file', 'max:5120'],
        ], [
            'celular.regex' => 'El celular solo puede contener números, espacios, guiones, paréntesis y el signo +.',
        ]);

        try {
            // Asegurar que los directorios existen
            Storage::disk('public')->makeDirectory('clientes/ci');
            Storage::disk('public')->makeDirectory('clientes/domicilio');

            $datos['foto_ci_anverso'] = $request->file('foto_ci_anverso')
                ->store('clientes/ci', 'public');
            $datos['foto_ci_reverso'] = $request->file('foto_ci_reverso')
                ->store('clientes/ci', 'public');

            if ($request->hasFile('comprobante_domicilio')) {
                $datos['comprobante_domicilio'] = $request->file('comprobante_domicilio')
                    ->store('clientes/domicilio', 'public');
            }
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error al subir archivos: ' . $e->getMessage());
        }

        $cliente = Cliente::create($datos);
        $this->clienteService->evaluarRiesgo($cliente);

        $this->auditoriaService->log(
            Auth::id(),
            'cliente',
            $cliente->id_cliente,
            'CREAR',
            null,
            $cliente->toArray(),
        );

        return redirect()
            ->route('clientes.show', $cliente)
            ->with('success', 'Cliente registrado correctamente.');
    }

    public function show(Cliente $cliente): View
    {
        $cliente->load([
            'prestamos' => fn ($query) => $query->orderByDesc('fecha_emision'),
            'prestamos.prendas',
            'prestamos.pagos',
        ]);

        return view('clientes.show', [
            'cliente' => $cliente,
            'historial' => $cliente->prestamos,
        ]);
    }
}
