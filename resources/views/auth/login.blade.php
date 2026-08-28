@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                         style="width: 80px; height: 80px; background: rgba(212, 160, 23, 0.15); border: 2px solid #d4a017;">
                        <i class="bi bi-gem fs-1" style="color: #d4a017;"></i>
                    </div>
                    <h1 class="text-white fw-bold mb-1">Trueque Cash</h1>
                    <p class="text-white-50 mb-0">Sistema de Gestión — Casa de Empeño</p>
                </div>

                <div class="card shadow-lg border-0">
                    <div class="card-body p-4">
                        <h5 class="card-title text-center mb-4">Iniciar Sesión</h5>

                        @if($errors->any())
                        <div class="alert alert-danger py-2">
                            @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                            @endforeach
                        </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="login" class="form-label">Usuario</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="text" class="form-control @error('login') is-invalid @enderror"
                                           id="login" name="login" value="{{ old('login') }}"
                                           placeholder="Ingrese su usuario" required autofocus>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                           id="password" name="password" placeholder="Ingrese su contraseña" required>
                                </div>
                            </div>

                            <div class="mb-4 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Recordarme</label>
                            </div>

                            <button type="submit" class="btn w-100 fw-semibold"
                                    style="background-color: #d4a017; color: #1a1a2e;">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Ingresar
                            </button>
                        </form>
                    </div>
                </div>

                <p class="text-center text-white-50 mt-3 small">&copy; {{ date('Y') }} Trueque Cash</p>
            </div>
        </div>
    </div>
</div>
@endsection
