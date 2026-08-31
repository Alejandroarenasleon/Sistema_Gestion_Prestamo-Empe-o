@extends('layouts.app')

@section('title', 'Préstamo #' . $prestamo->id_prestamo)

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('prestamos.index') }}">Préstamos</a></li>
            <li class="breadcrumb-item active">#{{ $prestamo->id_prestamo }}</li>
        </ol>
    </nav>

    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2">
        <div>
            <h2 class="mb-1">Préstamo #{{ $prestamo->id_prestamo }}</h2>
            <span class="badge @if($prestamo->estado === 'VIGENTE') bg-success @elseif($prestamo->estado === 'MORA') bg-danger @else bg-secondary @endif fs-6">
                {{ $prestamo->estado }}
            </span>
            @if($prestamo->requiere_aprobacion)
            <span class="badge bg-warning text-dark ms-1">Requiere Aprobación</span>
            @endif
        </div>
        <div class="d-flex flex-wrap gap-2">
            @if(in_array($prestamo->estado, ['VIGENTE', 'MORA']))
            <a href="{{ route('pagos.create', ['prestamo' => $prestamo->id_prestamo]) }}" class="btn btn-tc-primary">
                <i class="bi bi-wallet2"></i> Cobrar
            </a>
            @endif
            <a href="{{ route('prestamos.contrato', $prestamo) }}" class="btn btn-outline-tc">
                <i class="bi bi-file-earmark-pdf"></i> Descargar Contrato
            </a>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-6">
        <div class="card card-tc h-100">
            <div class="card-header">Datos del Préstamo</div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-5">Cliente</dt>
                    <dd class="col-sm-7">
                        <a href="{{ route('clientes.show', $prestamo->cliente) }}">{{ $prestamo->cliente->nombre_completo }}</a>
                        <br><small class="text-muted">{{ $prestamo->cliente->ci }}</small>
                    </dd>

                    <dt class="col-sm-5">Capital</dt>
                    <dd class="col-sm-7">Bs. {{ number_format($prestamo->monto_capital, 2) }}</dd>

                    <dt class="col-sm-5">Saldo Capital</dt>
                    <dd class="col-sm-7 fw-semibold">Bs. {{ number_format($prestamo->saldoCapital(), 2) }}</dd>

                    <dt class="col-sm-5">Interés Pendiente</dt>
                    <dd class="col-sm-7">Bs. {{ number_format($prestamo->interesPendiente(), 2) }}</dd>

                    <dt class="col-sm-5">Saldo Total</dt>
                    <dd class="col-sm-7 fw-bold" style="color: #b8860b;">Bs. {{ number_format($prestamo->saldoTotal(), 2) }}</dd>

                    <dt class="col-sm-5">Tasa Mensual</dt>
                    <dd class="col-sm-7">{{ number_format($prestamo->tasa_interes_mensual, 2) }}%</dd>

                    <dt class="col-sm-5">Emisión / Vencimiento</dt>
                    <dd class="col-sm-7">
                        {{ $prestamo->fecha_emision->format('d/m/Y') }} — {{ $prestamo->fecha_vencimiento->format('d/m/Y') }}
                    </dd>

                    <dt class="col-sm-5">Registrado por</dt>
                    <dd class="col-sm-7">{{ $prestamo->usuarioRegistro?->nombre_completo ?? '—' }}</dd>
                </dl>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card card-tc h-100">
            <div class="card-header">Prendas ({{ $prestamo->prendas->count() }})</div>
            <div class="card-body p-0">
                @foreach($prestamo->prendas as $prenda)
                <div class="border-bottom p-3">
                    <div class="d-flex justify-content-between">
                        <strong>{{ $prenda->descripcion }}</strong>
                        <span class="badge bg-secondary">{{ $prenda->estado }}</span>
                    </div>
                    <div class="small text-muted mt-1">
                        {{ $prenda->categoria }} · Avalúo: Bs. {{ number_format($prenda->avaluo, 2) }}
                        @if($prenda->marca) · {{ $prenda->marca }} @endif
                        @if($prenda->peso_gramos) · {{ $prenda->peso_gramos }}g @endif
                    </div>
                    @if($prenda->fotos->isNotEmpty())
                    <div class="d-flex gap-1 mt-2 flex-wrap">
                        @foreach($prenda->fotos as $foto)
                        <a href="{{ Storage::url($foto->url) }}" target="_blank">
                            <img src="{{ Storage::url($foto->url) }}" class="rounded border" style="width: 60px; height: 60px; object-fit: cover;" alt="Foto prenda">
                        </a>
                        @endforeach
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="card card-tc">
    <div class="card-header"><i class="bi bi-clock-history me-1"></i> Historial de Pagos</div>
    <div class="table-responsive">
        <table class="table table-hover table-tc mb-0 align-middle">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Tipo</th>
                    <th>Monto</th>
                    <th>Interés Período</th>
                    <th>Saldo Capital</th>
                    <th>Nuevo Vencimiento</th>
                    <th class="text-end">Recibo</th>
                </tr>
            </thead>
            <tbody>
                @forelse($prestamo->pagos->sortByDesc('fecha') as $pago)
                <tr>
                    <td>{{ $pago->fecha->format('d/m/Y H:i') }}</td>
                    <td><span class="badge bg-info text-dark">{{ $pago->tipo }}</span></td>
                    <td>Bs. {{ number_format($pago->monto, 2) }}</td>
                    <td>Bs. {{ number_format($pago->interes_periodo_calculado, 2) }}</td>
                    <td>Bs. {{ number_format($pago->saldo_capital_resultante, 2) }}</td>
                    <td>{{ $pago->nueva_fecha_vencimiento?->format('d/m/Y') ?? '—' }}</td>
                    <td class="text-end">
                        <a href="{{ route('pagos.recibo', $pago) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-file-earmark-pdf"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">Sin pagos registrados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
