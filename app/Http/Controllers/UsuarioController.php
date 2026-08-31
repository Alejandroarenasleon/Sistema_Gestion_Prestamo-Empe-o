<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Services\AuditoriaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UsuarioController extends Controller
{
    public function __construct(
        private AuditoriaService $auditoriaService,
    ) {}

    public function index(): View
    {
        $usuarios = Usuario::query()
            ->orderBy('nombre_completo')
            ->paginate(20);

        return view('usuarios.index', compact('usuarios'));
    }

    public function create(): View
    {
        return view('usuarios.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'nombre_completo' => ['required', 'string', 'max:120'],
            'login' => ['required', 'string', 'max:40', 'unique:usuario,login'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'rol' => ['required', 'in:ADMIN,OPERADOR'],
            'activo' => ['boolean'],
        ]);

        $usuario = Usuario::create([
            'nombre_completo' => $datos['nombre_completo'],
            'login' => $datos['login'],
            'password_hash' => Hash::make($datos['password']),
            'rol' => $datos['rol'],
            'activo' => $request->boolean('activo', true),
        ]);

        $this->auditoriaService->log(
            Auth::id(),
            'usuario',
            $usuario->id_usuario,
            'CREAR',
            null,
            $usuario->toArray(),
        );

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    public function edit(Usuario $usuario): View
    {
        return view('usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, Usuario $usuario): RedirectResponse
    {
        $datos = $request->validate([
            'nombre_completo' => ['required', 'string', 'max:120'],
            'login' => ['required', 'string', 'max:40', 'unique:usuario,login,' . $usuario->id_usuario . ',id_usuario'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'rol' => ['required', 'in:ADMIN,OPERADOR'],
            'activo' => ['boolean'],
        ]);

        $anterior = $usuario->toArray();

        $updateData = [
            'nombre_completo' => $datos['nombre_completo'],
            'login' => $datos['login'],
            'rol' => $datos['rol'],
            'activo' => $request->boolean('activo', true),
        ];

        if (! empty($datos['password'])) {
            $updateData['password_hash'] = Hash::make($datos['password']);
        }

        $usuario->update($updateData);

        $this->auditoriaService->log(
            Auth::id(),
            'usuario',
            $usuario->id_usuario,
            'MODIFICAR',
            $anterior,
            $usuario->fresh()->toArray(),
        );

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(Usuario $usuario): RedirectResponse
    {
        $anterior = $usuario->toArray();

        $usuario->update(['activo' => false]);

        $this->auditoriaService->log(
            Auth::id(),
            'usuario',
            $usuario->id_usuario,
            'MODIFICAR',
            $anterior,
            $usuario->fresh()->toArray(),
        );

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario desactivado correctamente.');
    }
}
