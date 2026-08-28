@extends('layouts.app')

@section('title', 'Parámetros')

@section('content')
<div class="mb-4">
    <h2 class="mb-0">Parámetros del Sistema</h2>
    <p class="text-muted mb-0">Configuración operativa de Trueque Cash</p>
</div>

<div class="card card-tc">
    <div class="card-body">
        <form method="POST" action="{{ route('parametros.update') }}">
            @csrf
            @method('PUT')

            <div class="table-responsive">
                <table class="table table-tc align-middle">
                    <thead>
                        <tr>
                            <th>Clave</th>
                            <th>Descripción</th>
                            <th style="width: 200px;">Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($parametros as $index => $parametro)
                        <tr>
                            <td>
                                <code>{{ $parametro->clave }}</code>
                                <input type="hidden" name="parametros[{{ $index }}][id_parametro]" value="{{ $parametro->id_parametro }}">
                            </td>
                            <td class="text-muted">{{ $parametro->descripcion }}</td>
                            <td>
                                <input type="text" class="form-control form-control-sm"
                                       name="parametros[{{ $index }}][valor]"
                                       value="{{ old("parametros.{$index}.valor", $parametro->valor) }}" required>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-tc-primary">
                    <i class="bi bi-save"></i> Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
