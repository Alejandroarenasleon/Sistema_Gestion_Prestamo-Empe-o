@extends('layouts.app')

@section('title', 'Notificaciones')

@php
    $clientesLista = \App\Models\Cliente::activos()->orderBy('nombre_completo')->get(['id_cliente', 'nombre_completo', 'celular']);
    $plantillas = \App\Models\PlantillaMensaje::where('activo', true)->get();
@endphp

@section('content')
<div class="mb-4">
    <h2 class="mb-0">Notificaciones</h2>
    <p class="text-muted mb-0">Historial y envío simulado de avisos a clientes</p>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-5">
        <div class="card card-tc">
            <div class="card-header"><i class="bi bi-send me-1"></i> Enviar Notificación Simulada</div>
            <div class="card-body">
                <form method="POST" action="{{ url('notificaciones/enviar-simulado') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="id_cliente" class="form-label">Cliente <span class="text-danger">*</span></label>
                        <select class="form-select @error('id_cliente') is-invalid @enderror" id="id_cliente" name="id_cliente" required>
                            <option value="">Seleccione...</option>
                            @foreach($clientesLista as $c)
                            <option value="{{ $c->id_cliente }}" @selected(old('id_cliente') == $c->id_cliente)>
                                {{ $c->nombre_completo }} ({{ $c->celular }})
                            </option>
                            @endforeach
                        </select>
                        @error('id_cliente')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="id_prestamo" class="form-label">Préstamo (opcional)</label>
                        <input type="number" class="form-control" id="id_prestamo" name="id_prestamo"
                               value="{{ old('id_prestamo') }}" placeholder="ID del préstamo">
                    </div>

                    <div class="mb-3">
                        <label for="id_plantilla" class="form-label">Plantilla (opcional)</label>
                        <select class="form-select" id="id_plantilla" name="id_plantilla">
                            <option value="">Sin plantilla</option>
                            @foreach($plantillas as $p)
                            <option value="{{ $p->id_plantilla }}" @selected(old('id_plantilla') == $p->id_plantilla)>
                                {{ $p->tipo_aviso }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="tipo" class="form-label">Tipo <span class="text-danger">*</span></label>
                        <select class="form-select @error('tipo') is-invalid @enderror" id="tipo" name="tipo" required>
                            <option value="">Seleccione...</option>
                            @foreach(['RECORDATORIO', 'MORA', 'AVISO_REMATE'] as $t)
                            <option value="{{ $t }}" @selected(old('tipo') === $t)>{{ $t }}</option>
                            @endforeach
                        </select>
                        @error('tipo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="canal" class="form-label">Canal <span class="text-danger">*</span></label>
                        <select class="form-select @error('canal') is-invalid @enderror" id="canal" name="canal" required>
                            <option value="">Seleccione...</option>
                            <option value="WHATSAPP" @selected(old('canal') === 'WHATSAPP')>WhatsApp</option>
                            <option value="SMS" @selected(old('canal') === 'SMS')>SMS</option>
                        </select>
                        @error('canal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <button type="submit" class="btn btn-tc-primary w-100">
                        <i class="bi bi-send"></i> Simular Envío
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card card-tc">
            <div class="card-header">Historial de Notificaciones</div>
            <div class="table-responsive">
                <table class="table table-hover table-tc mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Tipo</th>
                            <th>Canal</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($notificaciones as $notif)
                        <tr>
                            <td nowrap>{{ $notif->fecha_hora?->format('d/m/Y H:i') ?? '—' }}</td>
                            <td>{{ $notif->cliente?->nombre_completo ?? '—' }}</td>
                            <td><span class="badge bg-secondary">{{ $notif->tipo }}</span></td>
                            <td>{{ $notif->canal }}</td>
                            <td>
                                <span class="badge @if($notif->estado_envio === 'SIMULADO') bg-info @elseif($notif->estado_envio === 'ENVIADO') bg-success @else bg-warning text-dark @endif">
                                    {{ $notif->estado_envio }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No hay notificaciones registradas.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($notificaciones->hasPages())
            <div class="card-footer bg-white">{{ $notificaciones->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
