@extends('layouts.app')

@section('title', $cliente->nombre_completo)

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('clientes.index') }}">Clientes</a></li>
            <li class="breadcrumb-item active">{{ $cliente->nombre_completo }}</li>
        </ol>
    </nav>

    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2">
        <div>
            <h2 class="mb-1">{{ $cliente->nombre_completo }}</h2>
            <span class="text-muted">CI: {{ $cliente->ci }}</span>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('prestamos.create', ['cliente' => $cliente->id_cliente]) }}" class="btn btn-tc-primary">
                <i class="bi bi-plus-circle"></i> Nuevo Préstamo
            </a>
        </div>
    </div>
</div>

@if($cliente->alerta_riesgo)
<div class="alert alert-danger">
    <i class="bi bi-exclamation-triangle-fill me-2"></i>
    <strong>Alerta de Riesgo:</strong> {{ $cliente->motivo_alerta ?? 'Cliente marcado con alerta de riesgo.' }}
</div>
@endif

<div class="row g-3 mb-4">
    <div class="col-lg-6">
        <div class="card card-tc h-100">
            <div class="card-header"><i class="bi bi-person-vcard me-1"></i> Datos del Cliente</div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Celular</dt>
                    <dd class="col-sm-8">{{ $cliente->celular }}</dd>

                    <dt class="col-sm-4">Dirección</dt>
                    <dd class="col-sm-8">{{ $cliente->direccion ?? '—' }}</dd>

                    <dt class="col-sm-4">Referencia</dt>
                    <dd class="col-sm-8">{{ $cliente->referencia_contacto ?? '—' }}</dd>

                    <dt class="col-sm-4">Registro</dt>
                    <dd class="col-sm-8">{{ $cliente->fecha_registro?->format('d/m/Y H:i') ?? '—' }}</dd>

                    <dt class="col-sm-4">Estado</dt>
                    <dd class="col-sm-8">
                        @if($cliente->alerta_riesgo)
                        <span class="badge bg-danger">Alerta Riesgo</span>
                        @else
                        <span class="badge bg-success">Normal</span>
                        @endif
                    </dd>
                </dl>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card card-tc h-100">
            <div class="card-header"><i class="bi bi-images me-1"></i> Documentos</div>
            <div class="card-body">
                <div class="row g-2">
                    @if($cliente->foto_ci_anverso)
                    <div class="col-6">
                        <p class="small text-muted mb-1">CI Anverso</p>
                        <a href="{{ Storage::url($cliente->foto_ci_anverso) }}" target="_blank">
                            <img src="{{ Storage::url($cliente->foto_ci_anverso) }}" class="img-fluid rounded border" alt="CI Anverso">
                        </a>
                    </div>
                    @endif
                    @if($cliente->foto_ci_reverso)
                    <div class="col-6">
                        <p class="small text-muted mb-1">CI Reverso</p>
                        <a href="{{ Storage::url($cliente->foto_ci_reverso) }}" target="_blank">
                            <img src="{{ Storage::url($cliente->foto_ci_reverso) }}" class="img-fluid rounded border" alt="CI Reverso">
                        </a>
                    </div>
                    @endif
                    @if($cliente->comprobante_domicilio)
                    <div class="col-12">
                        <p class="small text-muted mb-1">Comprobante Domicilio</p>
                        <a href="{{ Storage::url($cliente->comprobante_domicilio) }}" target="_blank" class="btn btn-sm btn-outline-tc">
                            <i class="bi bi-file-earmark"></i> Ver comprobante
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card card-tc">
    <div class="card-header"><i class="bi bi-clock-history me-1"></i> Historial de Préstamos y Pagos</div>
    <div class="card-body p-0">
        @forelse($historial as $prestamo)
        <div class="border-bottom p-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-2 gap-2">
                <div>
                    <a href="{{ route('prestamos.show', $prestamo) }}" class="fw-semibold text-decoration-none">
                        Préstamo #{{ $prestamo->id_prestamo }}
                    </a>
                    <span class="ms-2 badge @if($prestamo->estado === 'VIGENTE') bg-success @elseif($prestamo->estado === 'MORA') bg-danger @else bg-secondary @endif">
                        {{ $prestamo->estado }}
                    </span>
                </div>
                <div class="text-muted small">
                    {{ $prestamo->fecha_emision->format('d/m/Y') }} — Vence: {{ $prestamo->fecha_vencimiento->format('d/m/Y') }}
                </div>
            </div>
            <div class="row small mb-2">
                <div class="col-md-3"><strong>Capital:</strong> Bs. {{ number_format($prestamo->monto_capital, 2) }}</div>
                <div class="col-md-3"><strong>Tasa:</strong> {{ number_format($prestamo->tasa_interes_mensual, 2) }}%</div>
                <div class="col-md-3"><strong>Saldo:</strong> Bs. {{ number_format($prestamo->saldoCapital(), 2) }}</div>
                <div class="col-md-3"><strong>Prendas:</strong> {{ $prestamo->prendas->count() }}</div>
            </div>
            @if($prestamo->pagos->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-sm table-bordered mb-0 small">
                    <thead class="table-light">
                        <tr>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Monto</th>
                            <th>Saldo Capital</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($prestamo->pagos as $pago)
                        <tr>
                            <td>{{ $pago->fecha->format('d/m/Y H:i') }}</td>
                            <td>{{ $pago->tipo }}</td>
                            <td>Bs. {{ number_format($pago->monto, 2) }}</td>
                            <td>Bs. {{ number_format($pago->saldo_capital_resultante, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-muted small mb-0">Sin pagos registrados.</p>
            @endif
        </div>
        @empty
        <div class="p-4 text-center text-muted">Este cliente no tiene préstamos registrados.</div>
        @endforelse
    </div>
</div>

@php
    $rematesCliente = \App\Models\Remate::query()
        ->with(['prenda', 'usuarioAprobo'])
        ->whereHas('prenda.prestamo', fn ($q) => $q->where('id_cliente', $cliente->id_cliente))
        ->orderByDesc('fecha_venta')
        ->get();
@endphp

@if($rematesCliente->isNotEmpty())
<div class="card card-tc mt-4">
    <div class="card-header"><i class="bi bi-hammer me-1"></i> Ventas en Remate (Ganancia / Pérdida)</div>
    <div class="table-responsive">
        <table class="table table-hover table-tc mb-0 align-middle">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Prenda</th>
                    <th>Categoría</th>
                    <th>Precio Venta</th>
                    <th>Resultado</th>
                    <th>Comprador</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rematesCliente as $remate)
                <tr>
                    <td>{{ $remate->fecha_venta?->format('d/m/Y') ?? '—' }}</td>
                    <td>{{ $remate->prenda->descripcion ?? '—' }}</td>
                    <td>{{ $remate->categoria ?? '—' }}</td>
                    <td>Bs. {{ number_format($remate->precio_venta, 2) }}</td>
                    <td class="fw-semibold @if($remate->resultado < 0) text-danger @else text-success @endif">
                        Bs. {{ number_format($remate->resultado, 2) }}
                    </td>
                    <td>{{ $remate->comprador ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
