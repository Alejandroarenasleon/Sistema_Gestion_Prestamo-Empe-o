@extends('layouts.app')

@section('title', 'Caja')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h2 class="mb-0">Caja</h2>
        <p class="text-muted mb-0">Cierre y arqueo de efectivo</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card card-tc mb-4">
            <div class="card-header">Seleccionar Fecha</div>
            <div class="card-body">
                <form method="GET" action="{{ route('caja.index') }}" class="d-flex gap-2">
                    <input type="date" name="fecha" class="form-control" value="{{ $fecha }}">
                    <button type="submit" class="btn btn-outline-tc">Consultar</button>
                </form>
            </div>
        </div>

        <div class="card card-tc stat-card mb-4">
            <div class="card-body text-center py-4">
                <p class="text-muted mb-1 text-uppercase small">Efectivo Esperado</p>
                <div class="stat-value">Bs. {{ number_format($efectivoEsperado, 2) }}</div>
                <p class="text-muted small mb-0">{{ \Carbon\Carbon::parse($fecha)->translatedFormat('d \d\e F Y') }}</p>
            </div>
        </div>

        @if(!$cierre)
        <div class="card card-tc">
            <div class="card-header">Generar Cierre</div>
            <div class="card-body">
                <p class="text-muted small">Genere el resumen de caja para confirmar el arqueo físico.</p>
                <form method="POST" action="{{ route('caja.generar') }}">
                    @csrf
                    <input type="hidden" name="fecha" value="{{ $fecha }}">
                    <button type="submit" class="btn btn-tc-primary w-100">
                        <i class="bi bi-calculator"></i> Generar Cierre de Caja
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-7">
        @if($cierre)
        <div class="card card-tc">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Resumen del Cierre</span>
                @if($cierre->confirmado)
                <span class="badge bg-success"><i class="bi bi-check-circle"></i> Confirmado</span>
                @else
                <span class="badge bg-warning text-dark">Pendiente de Arqueo</span>
                @endif
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-5">Efectivo Esperado</dt>
                    <dd class="col-sm-7 fw-semibold">Bs. {{ number_format($cierre->efectivo_esperado, 2) }}</dd>

                    @if($cierre->confirmado)
                    <dt class="col-sm-5">Efectivo Físico</dt>
                    <dd class="col-sm-7">Bs. {{ number_format($cierre->efectivo_fisico, 2) }}</dd>

                    <dt class="col-sm-5">Diferencia</dt>
                    <dd class="col-sm-7">
                        <span class="badge @if($cierre->diferencia == 0) bg-success @elseif($cierre->diferencia > 0) bg-info @else bg-danger @endif">
                            Bs. {{ number_format($cierre->diferencia, 2) }}
                        </span>
                    </dd>

                    @if($cierre->observacion)
                    <dt class="col-sm-5">Observación</dt>
                    <dd class="col-sm-7">{{ $cierre->observacion }}</dd>
                    @endif
                    @endif

                    <dt class="col-sm-5">Generado por</dt>
                    <dd class="col-sm-7">{{ $cierre->usuario?->nombre_completo ?? '—' }}</dd>
                </dl>

                @if(!$cierre->confirmado)
                <hr>
                <h6 class="mb-3">Confirmar con Arqueo Físico</h6>
                <form method="POST" action="{{ route('caja.confirmar', $cierre) }}">
                    @csrf
                    <div class="mb-3">
                        <label for="efectivo_fisico" class="form-label">Efectivo Físico Contado (Bs.)</label>
                        <input type="number" step="0.01" min="0" class="form-control @error('efectivo_fisico') is-invalid @enderror"
                               id="efectivo_fisico" name="efectivo_fisico" value="{{ old('efectivo_fisico') }}" required>
                        @error('efectivo_fisico')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="observacion" class="form-label">Observación</label>
                        <textarea class="form-control" id="observacion" name="observacion" rows="2" maxlength="255">{{ old('observacion') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-tc-primary">
                        <i class="bi bi-check2-all"></i> Confirmar Arqueo
                    </button>
                </form>
                @endif
            </div>
        </div>
        @else
        <div class="card card-tc">
            <div class="card-body text-center py-5 text-muted">
                <i class="bi bi-safe fs-1 d-block mb-3"></i>
                <p>No hay cierre generado para esta fecha.<br>Genere el cierre para proceder con el arqueo.</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
