@extends('layouts.app')

@section('title', 'Nuevo Cliente')

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('clientes.index') }}">Clientes</a></li>
            <li class="breadcrumb-item active">Nuevo</li>
        </ol>
    </nav>
    <h2 class="mb-0">Registrar Cliente</h2>
</div>

<div class="card card-tc">
    <div class="card-body">
        <form method="POST" action="{{ route('clientes.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="row g-3">
                <div class="col-md-4">
                    <label for="ci" class="form-label">Cédula de Identidad <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('ci') is-invalid @enderror"
                           id="ci" name="ci" value="{{ old('ci') }}" required maxlength="20">
                    @error('ci')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-8">
                    <label for="nombre_completo" class="form-label">Nombre Completo <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nombre_completo') is-invalid @enderror"
                           id="nombre_completo" name="nombre_completo" value="{{ old('nombre_completo') }}" required maxlength="150">
                    @error('nombre_completo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-8">
                    <label for="direccion" class="form-label">Dirección</label>
                    <input type="text" class="form-control @error('direccion') is-invalid @enderror"
                           id="direccion" name="direccion" value="{{ old('direccion') }}" maxlength="200">
                    @error('direccion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

<div class="col-md-4">
                    <label for="celular" class="form-label">Celular <span class="text-danger">*</span></label>
                    <input type="tel" class="form-control @error('celular') is-invalid @enderror"
                           id="celular" name="celular" value="{{ old('celular') }}" required maxlength="20"
                           pattern="[0-9+\-\s()]+" title="Solo números, +, -, espacios y paréntesis"
                           data-numeric="celular">
                    @error('celular')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text">Solo números, +, -, espacios y paréntesis</div>
                </div>

                <div class="col-md-6">
                    <label for="referencia_contacto" class="form-label">Referencia de Contacto</label>
                    <input type="text" class="form-control @error('referencia_contacto') is-invalid @enderror"
                           id="referencia_contacto" name="referencia_contacto" value="{{ old('referencia_contacto') }}" maxlength="150">
                    @error('referencia_contacto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label for="comprobante_domicilio" class="form-label">Comprobante de Domicilio</label>
                    <input type="file" class="form-control @error('comprobante_domicilio') is-invalid @enderror"
                           id="comprobante_domicilio" name="comprobante_domicilio" accept="image/*,.pdf">
                    @error('comprobante_domicilio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label for="foto_ci_anverso" class="form-label">Foto CI — Anverso <span class="text-danger">*</span></label>
                    <input type="file" class="form-control @error('foto_ci_anverso') is-invalid @enderror"
                           id="foto_ci_anverso" name="foto_ci_anverso" accept="image/*" required>
                    @error('foto_ci_anverso')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label for="foto_ci_reverso" class="form-label">Foto CI — Reverso <span class="text-danger">*</span></label>
                    <input type="file" class="form-control @error('foto_ci_reverso') is-invalid @enderror"
                           id="foto_ci_reverso" name="foto_ci_reverso" accept="image/*" required>
                    @error('foto_ci_reverso')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-tc-primary">
                    <i class="bi bi-check-lg"></i> Guardar Cliente
                </button>
                <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
