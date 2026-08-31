@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-0">Dashboard</h2>
        <p class="text-muted mb-0">Resumen operativo del día</p>
    </div>
    <span class="text-muted"><i class="bi bi-calendar3"></i> {{ now()->translatedFormat('l, d \d\e F Y') }}</span>
</div>

@if(Auth::user()->isAdmin() && $solicitudesPendientes > 0)
<div class="alert alert-warning d-flex align-items-center justify-content-between mb-4">
    <div>
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <strong>{{ $solicitudesPendientes }}</strong> solicitud(es) pendiente(s) de aprobación.
    </div>
    <a href="{{ route('aprobaciones.index') }}" class="btn btn-sm btn-warning">
        Revisar aprobaciones <i class="bi bi-arrow-right"></i>
    </a>
</div>
@endif

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card card-tc stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 small text-uppercase">Caja del Día</p>
                        <div class="stat-value">Bs. {{ number_format($cajaDelDia, 2) }}</div>
                    </div>
                    <div class="rounded-circle p-2" style="background: rgba(212,160,23,0.15);">
                        <i class="bi bi-safe fs-4" style="color: #d4a017;"></i>
                    </div>
                </div>
                <a href="{{ route('caja.index') }}" class="small text-decoration-none" style="color: #b8860b;">Ver caja &rarr;</a>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-tc stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 small text-uppercase">Por Vencer (Semana)</p>
                        <div class="stat-value">{{ $prendasPorVencerSemana }}</div>
                    </div>
                    <div class="rounded-circle p-2 bg-warning bg-opacity-25">
                        <i class="bi bi-clock-history fs-4 text-warning"></i>
                    </div>
                </div>
                <span class="small text-muted">Prendas con vencimiento próximo</span>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-tc stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 small text-uppercase">En Mora</p>
                        <div class="stat-value text-danger">{{ $enMora }}</div>
                    </div>
                    <div class="rounded-circle p-2 bg-danger bg-opacity-25">
                        <i class="bi bi-exclamation-circle fs-4 text-danger"></i>
                    </div>
                </div>
                <a href="{{ route('prestamos.index', ['estado' => 'MORA']) }}" class="small text-decoration-none text-danger">Ver préstamos &rarr;</a>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-tc stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 small text-uppercase">Disponibles Remate</p>
                        <div class="stat-value">{{ $disponiblesRemate }}</div>
                    </div>
                    <div class="rounded-circle p-2 bg-secondary bg-opacity-25">
                        <i class="bi bi-hammer fs-4 text-secondary"></i>
                    </div>
                </div>
                <a href="{{ route('remates.index') }}" class="small text-decoration-none text-secondary">Ver remates &rarr;</a>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-tc stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 small text-uppercase">Resultado Remates</p>
                        <div class="stat-value @if($resultadoRemates < 0) text-danger @else text-success @endif">Bs. {{ number_format($resultadoRemates, 2) }}</div>
                    </div>
                    <div class="rounded-circle p-2 bg-info bg-opacity-25">
                        <i class="bi bi-graph-up fs-4 text-info"></i>
                    </div>
                </div>
                <span class="small text-muted">Ganancia / pérdida acumulada</span>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card card-tc h-100">
            <div class="card-header"><i class="bi bi-lightning me-1"></i> Acciones Rápidas</div>
            <div class="card-body d-grid gap-2">
                <a href="{{ route('clientes.create') }}" class="btn btn-outline-tc"><i class="bi bi-person-plus"></i> Nuevo Cliente</a>
                <a href="{{ route('prestamos.create') }}" class="btn btn-outline-tc"><i class="bi bi-plus-circle"></i> Nuevo Préstamo</a>
                <a href="{{ route('pagos.create') }}" class="btn btn-tc-primary"><i class="bi bi-wallet2"></i> Registrar Cobro</a>
            </div>
        </div>
    </div>
</div>
@endsection
