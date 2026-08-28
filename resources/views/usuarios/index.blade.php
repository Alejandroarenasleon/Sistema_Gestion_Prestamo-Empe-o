@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h2 class="mb-0">Usuarios</h2>
        <p class="text-muted mb-0">Gestión de cuentas del sistema</p>
    </div>
    <a href="{{ route('usuarios.create') }}" class="btn btn-tc-primary">
        <i class="bi bi-person-plus"></i> Nuevo Usuario
    </a>
</div>

<div class="card card-tc">
    <div class="table-responsive">
        <table class="table table-hover table-tc mb-0 align-middle">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Login</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Registro</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($usuarios as $usuario)
                <tr>
                    <td>{{ $usuario->nombre_completo }}</td>
                    <td><code>{{ $usuario->login }}</code></td>
                    <td>
                        <span class="user-badge">{{ $usuario->rol }}</span>
                    </td>
                    <td>
                        @if($usuario->activo)
                        <span class="badge bg-success">Activo</span>
                        @else
                        <span class="badge bg-secondary">Inactivo</span>
                        @endif
                    </td>
                    <td>{{ $usuario->fecha_creacion?->format('d/m/Y') ?? '—' }}</td>
                    <td class="text-end">
                        <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-sm btn-outline-tc" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </a>
                        @if($usuario->activo && $usuario->id_usuario !== Auth::id())
                        <form method="POST" action="{{ route('usuarios.destroy', $usuario) }}" class="d-inline"
                              onsubmit="return confirm('¿Desactivar este usuario?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Desactivar">
                                <i class="bi bi-person-slash"></i>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">No hay usuarios registrados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($usuarios->hasPages())
    <div class="card-footer bg-white">{{ $usuarios->links() }}</div>
    @endif
</div>
@endsection
