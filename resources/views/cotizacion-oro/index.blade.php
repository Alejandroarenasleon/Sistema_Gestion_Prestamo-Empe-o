@extends('layouts.app')

@section('title', 'Cotización de Oro')

@section('content')
<div class="mb-4">
    <h2 class="mb-0">Cotización de Oro</h2>
    <p class="text-muted mb-0">Historial y registro de precios por quilate</p>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card card-tc">
            <div class="card-header"><i class="bi bi-plus-circle me-1"></i> Nueva Cotización</div>
            <div class="card-body">
                <form method="POST" action="{{ route('cotizacion-oro.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="quilate" class="form-label">Quilate</label>
                        <select class="form-select @error('quilate') is-invalid @enderror" id="quilate" name="quilate" required>
                            <option value="">Seleccione...</option>
                            @foreach(['10K', '14K', '18K', '21K', '22K', '24K'] as $q)
                            <option value="{{ $q }}" @selected(old('quilate') === $q)>{{ $q }}</option>
                            @endforeach
                        </select>
                        @error('quilate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="precio_gramo" class="form-label">Precio por Gramo (Bs.)</label>
                        <input type="number" step="0.01" min="0.01" class="form-control @error('precio_gramo') is-invalid @enderror"
                               id="precio_gramo" name="precio_gramo" value="{{ old('precio_gramo') }}" required
                               data-numeric="decimal">
                        @error('precio_gramo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="fecha" class="form-label">Fecha</label>
                        <input type="date" class="form-control @error('fecha') is-invalid @enderror"
                               id="fecha" name="fecha" value="{{ old('fecha', now()->toDateString()) }}" required>
                        @error('fecha')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-tc-primary w-100">
                        <i class="bi bi-check-lg"></i> Registrar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card card-tc">
            <div class="card-header">Historial de Cotizaciones</div>
            <div class="table-responsive">
                <table class="table table-hover table-tc mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Quilate</th>
                            <th>Precio / Gramo</th>
                            <th>Registrado por</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cotizaciones as $cot)
                        <tr>
                            <td>{{ $cot->fecha->format('d/m/Y') }}</td>
                            <td><span class="badge badge-tc">{{ $cot->quilate }}</span></td>
                            <td class="fw-semibold">Bs. {{ number_format($cot->precio_gramo, 2) }}</td>
                            <td>{{ $cot->usuario?->nombre_completo ?? '—' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">No hay cotizaciones registradas.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($cotizaciones->hasPages())
            <div class="card-footer bg-white">{{ $cotizaciones->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
