@extends('layouts.app')

@section('title', 'Reportes')

@section('content')
<div class="mb-4">
    <h2 class="mb-0">Reportes</h2>
    <p class="text-muted mb-0">Exportación de reportes operativos en PDF</p>
</div>

<div class="row g-4">
    @foreach([
        ['tipo' => 'caja', 'icon' => 'bi-safe', 'titulo' => 'Cierre de Caja', 'desc' => 'Resumen de cierres y arqueos por período'],
        ['tipo' => 'intereses', 'icon' => 'bi-percent', 'titulo' => 'Intereses Cobrados', 'desc' => 'Pagos de interés y renovaciones'],
        ['tipo' => 'remates', 'icon' => 'bi-hammer', 'titulo' => 'Ventas en Remate', 'desc' => 'Prendas vendidas y resultados'],
    ] as $reporte)
    <div class="col-md-4">
        <div class="card card-tc h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="rounded-circle p-3" style="background: rgba(212,160,23,0.15);">
                        <i class="bi {{ $reporte['icon'] }} fs-4" style="color: #d4a017;"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">{{ $reporte['titulo'] }}</h5>
                        <p class="text-muted small mb-0">{{ $reporte['desc'] }}</p>
                    </div>
                </div>

                <form method="GET" action="{{ route('reportes.exportar') }}" target="_blank">
                    <input type="hidden" name="tipo" value="{{ $reporte['tipo'] }}">
                    <div class="mb-2">
                        <label class="form-label small">Desde</label>
                        <input type="date" name="desde" class="form-control form-control-sm"
                               value="{{ now()->startOfMonth()->toDateString() }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Hasta</label>
                        <input type="date" name="hasta" class="form-control form-control-sm"
                               value="{{ now()->toDateString() }}">
                    </div>
                    <button type="submit" class="btn btn-tc-primary w-100">
                        <i class="bi bi-file-earmark-pdf"></i> Exportar PDF
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
