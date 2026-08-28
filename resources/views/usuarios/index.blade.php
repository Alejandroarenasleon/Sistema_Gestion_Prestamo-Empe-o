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
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">No hay usuarios registrados.</td>
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
