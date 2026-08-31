<?php

namespace App\Services;

use App\Models\Cliente;
use App\Models\Notificacion;
use App\Models\PlantillaMensaje;
use App\Models\Prestamo;

class NotificacionService
{
    /**
     * Envía (simulado en V1) un aviso al cliente y registra el resultado.
     *
     * En V1 no hay integración real con WhatsApp/SMS: se registra el envío
     * (destinatario, canal, fecha y resultado) para dejar documentada la
     * interfaz del gateway real que se conectará en V2.
     *
     * V2: reemplazar el cuerpo por la llamada al proveedor (Twilio/WhatsApp
     * Business API) y conservar el mismo contrato de parámetros/retorno.
     */
    public function enviarSimulado(
        Cliente $cliente,
        ?Prestamo $prestamo,
        ?PlantillaMensaje $plantilla,
        string $canal,
        string $tipo,
    ): Notificacion {
        return Notificacion::create([
            'id_cliente' => $cliente->id_cliente,
            'id_prestamo' => $prestamo?->id_prestamo,
            'id_plantilla' => $plantilla?->id_plantilla,
            'tipo' => $tipo,
            'canal' => $canal,
            'estado_envio' => 'ENVIADO',
            'fecha_hora' => now(),
        ]);
    }

    public function yaEnviadoHoy(?int $idPrestamo, string $tipo, string $canal): bool
    {
        return Notificacion::query()
            ->where('id_prestamo', $idPrestamo)
            ->where('tipo', $tipo)
            ->where('canal', $canal)
            ->whereDate('fecha_hora', now()->toDateString())
            ->exists();
    }
}
