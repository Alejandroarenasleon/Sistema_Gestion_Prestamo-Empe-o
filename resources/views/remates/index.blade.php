@extends('layouts.app')

@section('title', 'Remates')

@section('content')
<div class="mb-4">
    <h2 class="mb-0">Remates</h2>
    <p class="text-muted mb-0">Prendas disponibles para remate</p>
</div>

<div class="card card-tc">
    <div class="table-responsive">
        <table class="table table-hover table-tc mb-0 align-middle">
            <thead>
                <tr>
                    <th>Prenda</th>
                    <th>Cliente</th>
                    <th>Préstamo</th>
                    <th>Avalúo</th>
                    <th>Total Adeudado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($prendas as $prenda)
                @php $adeudado = $prenda->prestamo->saldoTotal(); @endphp
                <tr>
                    <td>
                        <div class="fw-semibold">{{ $prenda->descripcion }}</div>
                        <small class="text-muted">{{ $prenda->categoria }}</small>
                    </td>
                    <td>
                        <div>{{ $prenda->prestamo->cliente->nombre_completo }}</div>
                        <small class="text-muted">{{ $prenda->prestamo->cliente->ci }}</small>
                    </td>
                    <td>
                        <a href="{{ route('prestamos.show', $prenda->prestamo) }}">#{{ $prenda->prestamo->id_prestamo }}</a>
                    </td>
                    <td>Bs. {{ number_format($prenda->avaluo, 2) }}</td>
                    <td class="fw-semibold text-danger">Bs. {{ number_format($adeudado, 2) }}</td>
                    <td class="text-end">
                        <form method="POST" action="{{ route('remates.solicitar', $prenda) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-warning" title="Solicitar venta">
                                <i class="bi bi-send"></i> Solicitar
                            </button>
                        </form>

                        @if(Auth::user()->isAdmin())
                        <button type="button" class="btn btn-sm btn-tc-primary" data-bs-toggle="modal"
                                data-bs-target="#modalRegistrar{{ $prenda->id_prenda }}">
                            <i class="bi bi-hammer"></i> Registrar
                        </button>

                        <div class="modal fade" id="modalRegistrar{{ $prenda->id_prenda }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('remates.registrar', $prenda) }}">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Registrar Venta en Remate</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
<div class="modal-body">
                                                <p class="text-muted small">{{ $prenda->descripcion }} — Adeudado: Bs. {{ number_format($adeudado, 2) }}</p>
                                                <div class="mb-3">
                                                    <label class="form-label">Precio Ofertado (Bs.)</label>
                                                    <input type="number" step="0.01" min="0.01" name="precio_ofertado" class="form-control" required data-numeric="decimal">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Comprador</label>
                                                <input type="text" name="comprador" class="form-control" maxlength="150" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-tc-primary">Registrar Venta</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">No hay prendas disponibles para remate.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($prendas->hasPages())
    <div class="card-footer bg-white">{{ $prendas->links() }}</div>
    @endif
</div>
@endsection
