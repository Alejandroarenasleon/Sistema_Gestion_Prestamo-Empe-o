<?php

namespace App\Services;

use App\Models\Pago;
use App\Models\Parametro;
use App\Models\Prenda;
use App\Models\Prestamo;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class PrestamoService
{
    public function __construct(
        private readonly AuditoriaService $auditoriaService
    ) {}

    public function crearPrestamo(array $datos, int $usuarioId): Prestamo
    {
        return DB::transaction(function () use ($datos, $usuarioId) {
            $fechaEmision = isset($datos['fecha_emision'])
                ? Carbon::parse($datos['fecha_emision'])
                : now();

            $prestamo = Prestamo::create([
                'id_cliente' => $datos['id_cliente'],
                'id_usuario_registro' => $usuarioId,
                'monto_capital' => $datos['monto_capital'],
                'tasa_interes_mensual' => $datos['tasa_interes_mensual'],
                'fecha_emision' => $fechaEmision,
                'fecha_vencimiento' => $datos['fecha_vencimiento'] ?? $fechaEmision->copy()->addMonth(),
                'estado' => 'VIGENTE',
                'requiere_aprobacion' => $datos['requiere_aprobacion'] ?? false,
                'activo' => true,
            ]);

            $this->auditoriaService->log(
                $usuarioId,
                'prestamo',
                $prestamo->id_prestamo,
                'CREAR',
                null,
                $prestamo->toArray()
            );

            return $prestamo->load(['cliente', 'prendas', 'usuarioRegistro']);
        });
    }

    public function registrarPago(
        Prestamo $prestamo,
        string $tipo,
        float $monto,
        int $usuarioId
    ): Pago {
        $tiposValidos = ['INTERES', 'ABONO', 'CANCELACION', 'RENOVACION'];

        if (! in_array($tipo, $tiposValidos, true)) {
            throw new InvalidArgumentException("Tipo de pago inválido: {$tipo}");
        }

        return DB::transaction(function () use ($prestamo, $tipo, $monto, $usuarioId) {
            $prestamo->refresh();

            $saldoCapital = $prestamo->saldoCapital();
            $interesPeriodo = $this->calcularInteresPeriodo($prestamo);
            $nuevoSaldoCapital = $saldoCapital;
            $nuevaFechaVencimiento = null;

            match ($tipo) {
                'INTERES' => null,
                'ABONO' => $nuevoSaldoCapital = max(0, round($saldoCapital - $monto, 2)),
                'CANCELACION' => $nuevoSaldoCapital = 0,
                'RENOVACION' => $nuevaFechaVencimiento = $prestamo->fecha_vencimiento->copy()->addMonth(),
            };

            $pago = $prestamo->pagos()->create([
                'id_usuario' => $usuarioId,
                'tipo' => $tipo,
                'monto' => $monto,
                'interes_periodo_calculado' => in_array($tipo, ['INTERES', 'RENOVACION', 'CANCELACION'], true)
                    ? $interesPeriodo
                    : null,
                'saldo_capital_resultante' => $nuevoSaldoCapital,
                'nueva_fecha_vencimiento' => $nuevaFechaVencimiento,
                'fecha' => now(),
            ]);

            if ($tipo === 'RENOVACION' && $nuevaFechaVencimiento !== null) {
                $prestamo->update([
                    'fecha_vencimiento' => $nuevaFechaVencimiento,
                    'estado' => 'RENOVADO',
                ]);

                foreach ($prestamo->prendas as $prenda) {
                    $prenda->cambiarEstado('RENOVADA', 'Renovación de préstamo', $usuarioId);
                    $prenda->cambiarEstado('VIGENTE', 'Renovación activa', $usuarioId);
                }
            }

            if ($tipo === 'CANCELACION') {
                $prestamo->update(['estado' => 'CANCELADO']);

                foreach ($prestamo->prendas as $prenda) {
                    $prenda->cambiarEstado('DEVUELTA', 'Cancelación total del préstamo', $usuarioId);
                }
            }

            $this->auditoriaService->log(
                $usuarioId,
                'pago',
                $pago->id_pago,
                'CREAR',
                null,
                $pago->toArray()
            );

            return $pago;
        });
    }

    public function calcularInteresPeriodo(Prestamo $prestamo): float
    {
        if ($prestamo->estado === 'CANCELADO') {
            return 0.0;
        }

        return round($prestamo->saldoCapital() * ((float) $prestamo->tasa_interes_mensual / 100), 2);
    }

    public function actualizarEstadosMora(): int
    {
        $hoy = now()->startOfDay();
        $diasGracia = (int) Parametro::getValor('DIAS_GRACIA', 15);
        $actualizados = 0;

        $prestamosMora = Prestamo::where('activo', true)
            ->whereIn('estado', ['VIGENTE', 'RENOVADO'])
            ->whereDate('fecha_vencimiento', '<', $hoy)
            ->get();

        foreach ($prestamosMora as $prestamo) {
            $prestamo->update(['estado' => 'MORA']);
            $actualizados++;

            foreach ($prestamo->prendas as $prenda) {
                if ($prenda->estado === 'VIGENTE') {
                    $prenda->cambiarEstado('EN_MORA', 'Vencimiento sin pago', null);
                }
            }
        }

        $prendasEnGracia = Prenda::where('activo', true)
            ->where('estado', 'EN_MORA')
            ->whereHas('prestamo', function ($query) use ($hoy, $diasGracia) {
                $query->where('estado', 'MORA')
                    ->whereDate('fecha_vencimiento', '<=', $hoy->copy()->subDays($diasGracia));
            })
            ->get();

        foreach ($prendasEnGracia as $prenda) {
            $prenda->cambiarEstado('EN_GRACIA', 'Inicio de periodo de gracia', null);
            $actualizados++;
        }

        $prendasRemate = Prenda::where('activo', true)
            ->where('estado', 'EN_GRACIA')
            ->whereHas('prestamo', function ($query) use ($hoy, $diasGracia) {
                $query->where('estado', 'MORA')
                    ->whereDate('fecha_vencimiento', '<=', $hoy->copy()->subDays($diasGracia * 2));
            })
            ->get();

        foreach ($prendasRemate as $prenda) {
            $prenda->cambiarEstado('DISPONIBLE_REMATE', 'Periodo de gracia cumplido', null);
            $actualizados++;
        }

        return $actualizados;
    }
}
