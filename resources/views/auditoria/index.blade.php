@extends('layouts.app')

@section('title', 'Auditoría')

@php
    $usuariosLista = \App\Models\Usuario::orderBy('nombre_completo')->get(['id_usuario', 'nombre_completo']);
@endphp

@section('content')
<div class="mb-4">
    <h2 class="mb-0">Auditoría</h2>
    <p class="text-muted mb-0">Registro de acciones del sistema</p>
</div>

<div class="card card-tc mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('auditoria.index') }}" class="row g-2 align-items-end">
            <div class="col-md-2">
                <label for="entidad" class="form-label">Entidad</label>
                <select class="form-select" id="entidad" name="entidad">
                    <option value="">Todas</option>
                    @foreach(['cliente', 'prestamo', 'pago', 'prenda', 'remate', 'parametro', 'usuario', 'cierre_caja', 'solicitud_aprobacion', 'cotizacion_oro'] as $ent)
                    <option value="{{ $ent }}" @selected(($filtros['entidad'] ?? '') === $ent)>{{ $ent }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="accion" class="form-label">Acción</label>
                <select class="form-select" id="accion" name="accion">
                    <option value="">Todas</option>
                    @foreach(['CREAR', 'MODIFICAR', 'ELIMINAR'] as $acc)
                    <option value="{{ $acc }}" @selected(($filtros['accion'] ?? '') === $acc)>{{ $acc }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="usuario" class="form-label">Usuario</label>
                <select class="form-select" id="usuario" name="usuario">
                    <option value="">Todos</option>
                    @foreach($usuariosLista as $u)
                    <option value="{{ $u->id_usuario }}" @selected(($filtros['usuario'] ?? '') == $u->id_usuario)>{{ $u->nombre_completo }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="desde" class="form-label">Desde</label>
                <input type="date" class="form-select" id="desde" name="desde" value="{{ $filtros['desde'] ?? '' }}">
            </div>
            <div class="col-md-2">
                <label for="hasta" class="form-label">Hasta</label>
                <input type="date" class="form-select" id="hasta" name="hasta" value="{{ $filtros['hasta'] ?? '' }}">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-tc-primary w-100"><i class="bi bi-funnel"></i></button>
            </div>
        </form>
    </div>
</div>

<div class="card card-tc">
    <div class="table-responsive">
        <table class="table table-hover table-tc mb-0 align-middle small">
            <thead>
                <tr>
                    <th>Fecha/Hora</th>
                    <th>Usuario</th>
                    <th>Entidad</th>
                    <th>ID Ref.</th>
                    <th>Acción</th>
                    <th>Detalle</th>
                </tr>
            </thead>
            <tbody>
                @forelse($registros as $reg)
                <tr>
                    <td nowrap>{{ $reg->fecha_hora?->format('d/m/Y H:i:s') ?? '—' }}</td>
                    <td>{{ $reg->usuario?->nombre_completo ?? '—' }}</td>
                    <td><code>{{ $reg->entidad }}</code></td>
                    <td>{{ $reg->entidad_id }}</td>
                    <td>
                        <span class="badge @if($reg->accion === 'CREAR') bg-success @elseif($reg->accion === 'MODIFICAR') bg-warning text-dark @else bg-danger @endif">
                            {{ $reg->accion }}
                        </span>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="collapse"
                                data-bs-target="#detalle{{ $reg->id_auditoria }}">
                            Ver
                        </button>
                        <div class="collapse mt-1" id="detalle{{ $reg->id_auditoria }}">
                            @if($reg->valor_anterior)
                            <pre class="small bg-light p-2 rounded mb-1">{{ json_encode($reg->valor_anterior, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            @endif
                            @if($reg->valor_nuevo)
                            <pre class="small bg-light p-2 rounded mb-0">{{ json_encode($reg->valor_nuevo, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">No se encontraron registros.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($registros->hasPages())
    <div class="card-footer bg-white">{{ $registros->links() }}</div>
    @endif
</div>
@endsection
