<?php

namespace App\Services;

use App\Models\Cliente;
use App\Models\Parametro;
use App\Models\Prenda;
use App\Models\Prestamo;

class ClienteService
{
    public function evaluarRiesgo(Cliente $cliente): bool
    {
        $motivos = [];

        $tieneRemate = Prenda::whereHas('prestamo', function ($query) use ($cliente) {
            $query->where('id_cliente', $cliente->id_cliente);
        })
            ->where('estado', 'VENDIDA')
            ->exists();

        if ($tieneRemate) {
            $motivos[] = 'Historial de prenda rematada';
        }

        $prestamosEnMora = Prestamo::where('id_cliente', $cliente->id_cliente)
            ->where('activo', true)
            ->where('estado', 'MORA')
            ->count();

        if ($prestamosEnMora > 0) {
            $motivos[] = 'Préstamo(s) en mora';
        }

        if ($prestamosEnMora >= 2) {
            $motivos[] = 'Moras recurrentes';
        }

        $prendaVencida = Prestamo::where('id_cliente', $cliente->id_cliente)
            ->where('activo', true)
            ->whereIn('estado', ['MORA'])
            ->whereDate('fecha_vencimiento', '<', now())
            ->exists();

        if ($prendaVencida && ! in_array('Préstamo(s) en mora', $motivos, true)) {
            $motivos[] = 'Prenda vencida sin pago';
        }

        $alerta = count($motivos) > 0;

        $cliente->update([
            'alerta_riesgo' => $alerta,
            'motivo_alerta' => $alerta ? implode('; ', $motivos) : null,
        ]);

        return $alerta;
    }

    public function verificarDocumentosExtra(float $monto): array
    {
        $umbral = (float) Parametro::getValor('UMBRAL_DOCUMENTOS_EXTRA', 5000);

        return [
            'requiere' => $monto > $umbral,
            'umbral' => $umbral,
        ];
    }
}
