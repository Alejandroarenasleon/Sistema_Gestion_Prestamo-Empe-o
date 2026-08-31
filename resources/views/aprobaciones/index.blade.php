@extends('layouts.app')

@section('title', 'Aprobaciones')

@section('content')
<div class="mb-4">
    <h2 class="mb-0">Aprobaciones</h2>
    <p class="text-muted mb-0">Solicitudes pendientes de revisión administrativa</p>
</div>

<div class="card card-tc">
    <div class="table-responsive">
        <table class="table table-hover table-tc mb-0 align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tipo</th>
                    <th>Referencia</th>
                    <th>Solicitó</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($solicitudes as $solicitud)
                <tr>
                    <td>{{ $solicitud->id_solicitud }}</td>
                    <td>
                        <span class="badge bg-secondary">{{ $solicitud->tipo }}</span>
                        @if($solicitud->tipo === 'VENTA_PRENDA' && $solicitud->avisoRemate)
                        @php $adeudado = $solicitud->prenda?->prestamo?->saldoTotal() ?? 0; @endphp
                        <div class="small text-muted mt-1">
                            Prec. ofertado: <strong>Bs. {{ number_format($solicitud->avisoRemate->precio_ofertado, 2) }}</strong><br>
                            Adeudado: Bs. {{ number_format($adeudado, 2) }}
                        </div>
                        @endif
                    </td>
                    <td>#{{ $solicitud->referencia_id }}</td>
                    <td>{{ $solicitud->usuarioSolicito?->nombre_completo ?? '—' }}</td>
                    <td>{{ $solicitud->fecha_solicitud?->format('d/m/Y H:i') ?? '—' }}</td>
                    <td>
                        @if($solicitud->estado === 'PENDIENTE')
                        <span class="badge bg-warning text-dark">Pendiente</span>
                        @elseif($solicitud->estado === 'APROBADO')
                        <span class="badge bg-success">Aprobado</span>
                        @else
                        <span class="badge bg-danger">Rechazado</span>
                        @endif
                    </td>
                    <td class="text-end">
                        @if($solicitud->estado === 'PENDIENTE')
                        <form method="POST" action="{{ route('aprobaciones.aprobar', $solicitud) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success" title="Aprobar">
                                <i class="bi bi-check-lg"></i>
                            </button>
                        </form>
                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                data-bs-target="#modalRechazar{{ $solicitud->id_solicitud }}" title="Rechazar">
                            <i class="bi bi-x-lg"></i>
                        </button>

                        <div class="modal fade" id="modalRechazar{{ $solicitud->id_solicitud }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('aprobaciones.rechazar', $solicitud) }}">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Rechazar Solicitud #{{ $solicitud->id_solicitud }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Motivo del Rechazo</label>
                                                <textarea name="motivo" class="form-control" rows="3" maxlength="255" required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-danger">Rechazar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @else
                        <small class="text-muted">
                            @if($solicitud->usuarioResolvio)
                            {{ $solicitud->usuarioResolvio->nombre_completo }}
                            @endif
                            @if($solicitud->motivo)
                            <br>{{ $solicitud->motivo }}
                            @endif
                        </small>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">No hay solicitudes registradas.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($solicitudes->hasPages())
    <div class="card-footer bg-white">{{ $solicitudes->links() }}</div>
    @endif
</div>
@endsection
