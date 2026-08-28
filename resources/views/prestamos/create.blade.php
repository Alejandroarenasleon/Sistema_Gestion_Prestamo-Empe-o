@extends('layouts.app')

@section('title', 'Nuevo Préstamo')

@php
    $clientesLista = \App\Models\Cliente::activos()->orderBy('nombre_completo')->get(['id_cliente', 'ci', 'nombre_completo']);
@endphp

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('prestamos.index') }}">Préstamos</a></li>
            <li class="breadcrumb-item active">Nuevo</li>
        </ol>
    </nav>
    <h2 class="mb-0">Registrar Préstamo</h2>
</div>

<form method="POST" action="{{ route('prestamos.store') }}" enctype="multipart/form-data" id="formPrestamo">
    @csrf

    <div class="card card-tc mb-4">
        <div class="card-header">Datos del Préstamo</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="id_cliente" class="form-label">Cliente <span class="text-danger">*</span></label>
                    <select class="form-select @error('id_cliente') is-invalid @enderror" id="id_cliente" name="id_cliente" required>
                        <option value="">Seleccione un cliente...</option>
                        @foreach($clientesLista as $c)
                        <option value="{{ $c->id_cliente }}" @selected(old('id_cliente', $cliente?->id_cliente) == $c->id_cliente)>
                            {{ $c->nombre_completo }} — {{ $c->ci }}
                        </option>
                        @endforeach
                    </select>
                    @error('id_cliente')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-3">
                    <label for="monto_capital" class="form-label">Capital (Bs.) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="1" class="form-control @error('monto_capital') is-invalid @enderror"
                           id="monto_capital" name="monto_capital" value="{{ old('monto_capital') }}" required>
                    @error('monto_capital')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-3">
                    <label for="tasa_interes_mensual" class="form-label">Tasa Mensual (%) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0" class="form-control @error('tasa_interes_mensual') is-invalid @enderror"
                           id="tasa_interes_mensual" name="tasa_interes_mensual"
                           value="{{ old('tasa_interes_mensual', \App\Models\Parametro::getValor('TASA_INTERES_DEFAULT', '10')) }}" required>
                    @error('tasa_interes_mensual')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-3">
                    <label for="fecha_emision" class="form-label">Fecha Emisión <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('fecha_emision') is-invalid @enderror"
                           id="fecha_emision" name="fecha_emision" value="{{ old('fecha_emision', now()->toDateString()) }}" required>
                    @error('fecha_emision')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-3">
                    <label for="fecha_vencimiento" class="form-label">Fecha Vencimiento <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('fecha_vencimiento') is-invalid @enderror"
                           id="fecha_vencimiento" name="fecha_vencimiento"
                           value="{{ old('fecha_vencimiento', now()->addMonth()->toDateString()) }}" required>
                    @error('fecha_vencimiento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>
    </div>

    <div class="card card-tc mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Prendas en Garantía</span>
            <button type="button" class="btn btn-sm btn-outline-tc" id="btnAddPrenda">
                <i class="bi bi-plus-lg"></i> Agregar Prenda
            </button>
        </div>
        <div class="card-body" id="prendasContainer">
            {{-- Las filas de prendas se generan dinámicamente --}}
        </div>
        @error('prendas')<div class="card-footer text-danger">{{ $message }}</div>@enderror
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-tc-primary">
            <i class="bi bi-check-lg"></i> Registrar Préstamo
        </button>
        <a href="{{ route('prestamos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    </div>
</form>

<template id="prendaTemplate">
    <div class="prenda-row border rounded p-3 mb-3 position-relative">
        <button type="button" class="btn btn-sm btn-outline-danger position-absolute top-0 end-0 m-2 btn-remove-prenda" title="Eliminar">
            <i class="bi bi-x-lg"></i>
        </button>
        <h6 class="mb-3 text-muted">Prenda <span class="prenda-num"></span></h6>
        <div class="row g-2">
            <div class="col-md-3">
                <label class="form-label">Categoría *</label>
                <select class="form-select" name="prendas[__INDEX__][categoria]" required>
                    <option value="">Seleccione...</option>
                    <option value="ORO">Oro / Joyas</option>
                    <option value="ELECTRONICO">Electrónico</option>
                    <option value="HERRAMIENTA">Herramienta</option>
                    <option value="VEHICULO">Vehículo</option>
                    <option value="OTRO">Otro</option>
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label">Descripción *</label>
                <input type="text" class="form-control" name="prendas[__INDEX__][descripcion]" required maxlength="255">
            </div>
            <div class="col-md-2">
                <label class="form-label">Avalúo (Bs.) *</label>
                <input type="number" step="0.01" min="0" class="form-control" name="prendas[__INDEX__][avaluo]" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Peso (g)</label>
                <input type="number" step="0.01" min="0" class="form-control" name="prendas[__INDEX__][peso_gramos]">
            </div>
            <div class="col-md-3">
                <label class="form-label">Marca</label>
                <input type="text" class="form-control" name="prendas[__INDEX__][marca]" maxlength="60">
            </div>
            <div class="col-md-3">
                <label class="form-label">Modelo</label>
                <input type="text" class="form-control" name="prendas[__INDEX__][modelo]" maxlength="60">
            </div>
            <div class="col-md-3">
                <label class="form-label">Material</label>
                <input type="text" class="form-control" name="prendas[__INDEX__][material]" maxlength="60">
            </div>
            <div class="col-md-3">
                <label class="form-label">Serie / IMEI</label>
                <input type="text" class="form-control" name="prendas[__INDEX__][numero_serie_imei]" maxlength="60">
            </div>
            <div class="col-md-8">
                <label class="form-label">Estado Físico / Observaciones</label>
                <input type="text" class="form-control" name="prendas[__INDEX__][estado_fisico_obs]">
            </div>
            <div class="col-md-4">
                <label class="form-label">Fotos</label>
                <input type="file" class="form-control" name="prendas[__INDEX__][fotos][]" accept="image/*" multiple>
            </div>
        </div>
    </div>
</template>
@endsection

@push('scripts')
<script>
(function () {
    let prendaIndex = 0;
    const container = document.getElementById('prendasContainer');
    const template = document.getElementById('prendaTemplate');

    function addPrenda() {
        const html = template.innerHTML.replace(/__INDEX__/g, prendaIndex);
        const wrapper = document.createElement('div');
        wrapper.innerHTML = html;
        const row = wrapper.firstElementChild;
        row.querySelector('.prenda-num').textContent = prendaIndex + 1;
        row.querySelector('.btn-remove-prenda').addEventListener('click', function () {
            row.remove();
            renumberPrendas();
        });
        container.appendChild(row);
        prendaIndex++;
    }

    function renumberPrendas() {
        container.querySelectorAll('.prenda-row').forEach(function (row, i) {
            row.querySelector('.prenda-num').textContent = i + 1;
        });
    }

    document.getElementById('btnAddPrenda').addEventListener('click', addPrenda);
    addPrenda();
})();
</script>
@endpush
