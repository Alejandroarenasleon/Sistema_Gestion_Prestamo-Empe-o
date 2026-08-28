@extends('layouts.app')

@section('title', 'Editar Usuario')

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('usuarios.index') }}">Usuarios</a></li>
            <li class="breadcrumb-item active">Editar</li>
        </ol>
    </nav>
    <h2 class="mb-0">Editar Usuario</h2>
</div>

<div class="card card-tc">
    <div class="card-body">
        <form method="POST" action="{{ route('usuarios.update', $usuario) }}">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="nombre_completo" class="form-label">Nombre Completo <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nombre_completo') is-invalid @enderror"
                           id="nombre_completo" name="nombre_completo" value="{{ old('nombre_completo', $usuario->nombre_completo) }}" required maxlength="120">
                    @error('nombre_completo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label for="login" class="form-label">Login <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('login') is-invalid @enderror"
                           id="login" name="login" value="{{ old('login', $usuario->login) }}" required maxlength="40">
                    @error('login')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label for="password" class="form-label">Nueva Contraseña</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                           id="password" name="password" minlength="8" placeholder="Dejar vacío para mantener la actual">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" minlength="8">
                </div>

                <div class="col-md-4">
                    <label for="rol" class="form-label">Rol <span class="text-danger">*</span></label>
                    <select class="form-select @error('rol') is-invalid @enderror" id="rol" name="rol" required>
                        <option value="ADMIN" @selected(old('rol', $usuario->rol) === 'ADMIN')>ADMIN</option>
                        <option value="OPERADOR" @selected(old('rol', $usuario->rol) === 'OPERADOR')>OPERADOR</option>
                    </select>
                    @error('rol')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="activo" name="activo" value="1" @checked(old('activo', $usuario->activo))>
                        <label class="form-check-label" for="activo">Usuario activo</label>
                    </div>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-tc-primary">
                    <i class="bi bi-check-lg"></i> Guardar Cambios
                </button>
                <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
