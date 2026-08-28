<?php

namespace App\Services;

use App\Models\CierreCaja;
use App\Models\Pago;
use App\Models\Prestamo;
use App\Models\Remate;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CajaService
{
    public function __construct(
        private readonly AuditoriaService $auditoriaService
    ) {}

    public function calcularEfectivoEsperado(Carbon|string $fecha): float
    {
        $fecha = Carbon::parse($fecha)->toDateString();

        $ingresosPagos = (float) Pago::whereDate('fecha', $fecha)->sum('monto');

        $ingresosRemates = (float) Remate::whereDate('fecha_venta', $fecha)->sum('precio_venta');

        $egresosPrestamos = (float) Prestamo::whereDate('fecha_emision', $fecha)
            ->where('activo', true)
            ->sum('monto_capital');

        return round($ingresosPagos + $ingresosRemates - $egresosPrestamos, 2);
    }

    public function crearCierreCaja(
        Carbon|string $fecha,
        int $usuarioId,
        ?float $efectivoFisico = null,
        ?string $observacion = null
    ): CierreCaja {
        return DB::transaction(function () use ($fecha, $usuarioId, $efectivoFisico, $observacion) {
            $fechaCarbon = Carbon::parse($fecha)->startOfDay();
            $efectivoEsperado = $this->calcularEfectivoEsperado($fechaCarbon);

            $diferencia = $efectivoFisico !== null
                ? round($efectivoFisico - $efectivoEsperado, 2)
                : null;

            $cierre = CierreCaja::updateOrCreate(
                ['fecha' => $fechaCarbon->toDateString()],
                [
                    'efectivo_esperado' => $efectivoEsperado,
                    'efectivo_fisico' => $efectivoFisico,
                    'diferencia' => $diferencia,
                    'observacion' => $observacion,
                    'confirmado' => $efectivoFisico !== null,
                    'id_usuario' => $usuarioId,
                ]
            );

            $this->auditoriaService->log(
                $usuarioId,
                'cierre_caja',
                $cierre->id_cierre,
                $cierre->wasRecentlyCreated ? 'CREAR' : 'MODIFICAR',
                null,
                $cierre->toArray()
            );

            return $cierre;
        });
    }
}
