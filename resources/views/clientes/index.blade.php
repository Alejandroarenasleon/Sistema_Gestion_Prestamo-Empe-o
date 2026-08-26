@extends('layouts.app')

@section('title', 'Clientes')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h2 class="mb-0">Clientes</h2>
        <p class="text-muted mb-0">Directorio de clientes registrados</p>
    </div>
    <a href="{{ route('clientes.create') }}" class="btn btn-tc-primary">
        <i class="bi bi-person-plus"></i> Nuevo Cliente
    </a>
</div>

<div class="card card-tc mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('clientes.index') }}" class="row g-2 align-items-end">
            <div class="col-md-8">
                <label for="q" class="form-label">Buscar</label>
                <input type="text" class="form-control" id="q" name="q"
                       value="{{ $termino ?? request('q') }}"
                       placeholder="CI, nombre o celular...">
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-tc-primary flex-grow-1">
                    <i class="bi bi-search"></i> Buscar
                </button>
                @if(($termino ?? request('q')))
                <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card card-tc">
    <div class="table-responsive">
        <table class="table table-hover table-tc mb-0 align-middle">
            <thead>
                <tr>
                    <th>CI</th>
                    <th>Nombre</th>
                    <th>Celular</th>
                    <th>Alerta</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clientes as $cliente)
                <tr>
                    <td><code>{{ $cliente->ci }}</code></td>
                    <td>{{ $cliente->nombre_completo }}</td>
                    <td>{{ $cliente->celular }}</td>
                    <td>
                        @if($cliente->alerta_riesgo)
                        <span class="badge bg-danger" title="{{ $cliente->motivo_alerta }}">
                            <i class="bi bi-exclamation-triangle"></i> Riesgo
                        </span>
                        @else
                        <span class="badge bg-success">OK</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('clientes.show', $cliente) }}" class="btn btn-sm btn-outline-tc">
                            <i class="bi bi-eye"></i> Ver
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">No se encontraron clientes.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($clientes->hasPages())
    <div class="card-footer bg-white">
        {{ $clientes->links() }}
    </div>
    @endif
</div>
@endsection
