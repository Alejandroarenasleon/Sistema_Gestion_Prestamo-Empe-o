@extends('layouts.app')

@section('title', 'Registrar Cobro')

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('pagos.create') }}">Cobros</a></li>
            <li class="breadcrumb-item active">Registrar</li>
        </ol>
    </nav>
    <h2 class="mb-0">Registrar Cobro</h2>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card card-tc">
            <div class="card-body">
                <form method="POST" action="{{ route('pagos.store') }}" id="formPago">
                    @csrf

                    <div class="mb-3">
                        <label for="id_prestamo" class="form-label">Préstamo <span class="text-danger">*</span></label>
                        <select class="form-select @error('id_prestamo') is-invalid @enderror" id="id_prestamo" name="id_prestamo" required>
                            <option value="">Seleccione un préstamo...</option>
                            @foreach($prestamosActivos as $p)
                            <option value="{{ $p->id_prestamo }}"
                                    data-saldo="{{ $p->saldoCapital() }}"
                                    data-interes="{{ $p->interesPendiente() }}"
                                    data-total="{{ $p->saldoTotal() }}"
                                    data-cliente="{{ $p->cliente->nombre_completo }}"
                                    @selected(old('id_prestamo', $prestamo?->id_prestamo) == $p->id_prestamo)>
                                #{{ $p->id_prestamo }} — {{ $p->cliente->nombre_completo }} ({{ $p->estado }})
                            </option>
                            @endforeach
                        </select>
                        @error('id_prestamo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="tipo" class="form-label">Tipo de Cobro <span class="text-danger">*</span></label>
                        <select class="form-select @error('tipo') is-invalid @enderror" id="tipo" name="tipo" required>
                            <option value="">Seleccione...</option>
                            @foreach($tiposDisponibles as $val => $label)
                            <option value="{{ $val }}" @selected(old('tipo') === $val)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('tipo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="monto" class="form-label">Monto (Bs.) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0.01" class="form-control @error('monto') is-invalid @enderror"
                               id="monto" name="monto" value="{{ old('monto') }}" required
                               data-numeric="decimal">
                        @error('monto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3" id="renovacionField" style="display: none;">
                        <label for="nueva_fecha_vencimiento" class="form-label">Nueva Fecha de Vencimiento</label>
                        <input type="date" class="form-control @error('nueva_fecha_vencimiento') is-invalid @enderror"
                               id="nueva_fecha_vencimiento" name="nueva_fecha_vencimiento" value="{{ old('nueva_fecha_vencimiento') }}">
                        @error('nueva_fecha_vencimiento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-tc-primary">
                            <i class="bi bi-check-lg"></i> Registrar Cobro
                        </button>
                        <a href="{{ route('prestamos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card card-tc" id="infoPrestamo" style="display: none;">
            <div class="card-header">Información del Préstamo</div>
            <div class="card-body">
                <p class="mb-2"><strong>Cliente:</strong> <span id="infoCliente">—</span></p>
                <hr>
                <dl class="row mb-0">
                    <dt class="col-6">Saldo Capital</dt>
                    <dd class="col-6 text-end" id="infoSaldo">—</dd>

                    <dt class="col-6">Interés Pendiente</dt>
                    <dd class="col-6 text-end fw-semibold" id="infoInteres" style="color: #b8860b;">—</dd>

                    <dt class="col-6">Saldo Total</dt>
                    <dd class="col-6 text-end fw-bold" id="infoTotal">—</dd>
                </dl>
                <div class="mt-3">
                    <button type="button" class="btn btn-sm btn-outline-tc w-100" id="btnUsarInteres">
                        Usar monto de interés pendiente
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary w-100 mt-2" id="btnUsarTotal">
                        Usar saldo total (cancelación)
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const selectPrestamo = document.getElementById('id_prestamo');
    const selectTipo = document.getElementById('tipo');
    const inputMonto = document.getElementById('monto');
    const infoPanel = document.getElementById('infoPrestamo');
    const renovacionField = document.getElementById('renovacionField');

    function formatBs(value) {
        return 'Bs. ' + parseFloat(value).toFixed(2);
    }

    function updateInfo() {
        const opt = selectPrestamo.selectedOptions[0];
        if (!opt || !opt.value) {
            infoPanel.style.display = 'none';
            return;
        }
        infoPanel.style.display = 'block';
        document.getElementById('infoCliente').textContent = opt.dataset.cliente;
        document.getElementById('infoSaldo').textContent = formatBs(opt.dataset.saldo);
        document.getElementById('infoInteres').textContent = formatBs(opt.dataset.interes);
        document.getElementById('infoTotal').textContent = formatBs(opt.dataset.total);
    }

    selectPrestamo.addEventListener('change', updateInfo);
    selectTipo.addEventListener('change', function () {
        renovacionField.style.display = this.value === 'RENOVACION' ? 'block' : 'none';
    });

    document.getElementById('btnUsarInteres').addEventListener('click', function () {
        const opt = selectPrestamo.selectedOptions[0];
        if (opt && opt.value) {
            inputMonto.value = parseFloat(opt.dataset.interes).toFixed(2);
            selectTipo.value = 'INTERES';
        }
    });

    document.getElementById('btnUsarTotal').addEventListener('click', function () {
        const opt = selectPrestamo.selectedOptions[0];
        if (opt && opt.value) {
            inputMonto.value = parseFloat(opt.dataset.total).toFixed(2);
            selectTipo.value = 'CANCELACION';
        }
    });

    updateInfo();
    if (selectTipo.value === 'RENOVACION') renovacionField.style.display = 'block';
})();
</script>
@endpush
