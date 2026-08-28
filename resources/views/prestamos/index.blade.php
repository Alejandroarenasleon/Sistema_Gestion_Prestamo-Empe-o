@extends('layouts.app')

@section('title', 'Préstamos')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h2 class="mb-0">Préstamos</h2>
        <p class="text-muted mb-0">Gestión de préstamos activos e históricos</p>
    </div>
    <a href="{{ route('prestamos.create') }}" class="btn btn-tc-primary">
        <i class="bi bi-plus-circle"></i> Nuevo Préstamo
    </a>
</div>

<div class="card card-tc mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('prestamos.index') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label for="estado" class="form-label">Estado</label>
                <select class="form-select" id="estado" name="estado">
                    <option value="">Todos</option>
                    @foreach(['VIGENTE', 'MORA', 'CANCELADO', 'VENCIDO'] as $est)
                    <option value="{{ $est }}" @selected(request('estado') === $est)>{{ $est }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label for="q" class="form-label">Buscar cliente</label>
                <input type="text" class="form-control" id="q" name="q"
                       value="{{ request('q') }}" placeholder="CI, nombre o celular...">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-tc-primary flex-grow-1"><i class="bi bi-funnel"></i> Filtrar</button>
                @if(request()->hasAny(['estado', 'q']))
                <a href="{{ route('prestamos.index') }}" class="btn btn-outline-secondary">Limpiar</a>
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
                    <th>#</th>
                    <th>Cliente</th>
                    <th>Capital</th>
                    <th>Tasa</th>
                    <th>Emisión</th>
                    <th>Vencimiento</th>
                    <th>Estado</th>
                    <th>Prendas</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($prestamos as $prestamo)
                <tr>
                    <td>{{ $prestamo->id_prestamo }}</td>
                    <td>
                        <div>{{ $prestamo->cliente->nombre_completo }}</div>
                        <small class="text-muted">{{ $prestamo->cliente->ci }}</small>
                    </td>
                    <td>Bs. {{ number_format($prestamo->monto_capital, 2) }}</td>
                    <td>{{ number_format($prestamo->tasa_interes_mensual, 2) }}%</td>
                    <td>{{ $prestamo->fecha_emision->format('d/m/Y') }}</td>
                    <td>{{ $prestamo->fecha_vencimiento->format('d/m/Y') }}</td>
                    <td>
                        <span class="badge @if($prestamo->estado === 'VIGENTE') bg-success @elseif($prestamo->estado === 'MORA') bg-danger @else bg-secondary @endif">
                            {{ $prestamo->estado }}
                        </span>
                    </td>
                    <td>{{ $prestamo->prendas->count() }}</td>
                    <td class="text-end">
                        <a href="{{ route('prestamos.show', $prestamo) }}" class="btn btn-sm btn-outline-tc">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-muted py-4">No se encontraron préstamos.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($prestamos->hasPages())
    <div class="card-footer bg-white">{{ $prestamos->links() }}</div>
    @endif
</div>
@endsection
