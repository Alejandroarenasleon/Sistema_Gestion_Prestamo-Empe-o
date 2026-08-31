<?php

namespace App\Console\Commands;

use App\Models\Parametro;
use App\Models\PlantillaMensaje;
use App\Models\Prestamo;
use App\Services\NotificacionService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class EnviarRecordatorios extends Command
{
    protected $signature = 'trueque:enviar-recordatorios';

    protected $description = 'Genera y envía (simulado V1) la cola diaria de recordatorios de vencimiento y mora';

    public function handle(NotificacionService $notificacionService): int
    {
        $hoy = now()->startOfDay();

        $diasAntes = (int) Parametro::getValor('RECORDATORIO_DIAS_ANTES', 3);
        $mismoDia = Parametro::getValor('RECORDATORIO_MISMO_DIA', '1') === '1';
        $diasMora = (int) Parametro::getValor('RECORDATORIO_DIAS_MORA', 3);

        $plantillaRecordatorio = PlantillaMensaje::where('tipo_aviso', 'RECORDATORIO')->where('activo', true)->first();
        $plantillaMora = PlantillaMensaje::where('tipo_aviso', 'MORA')->where('activo', true)->first();

        $enviados = 0;

        $prestamos = Prestamo::query()
            ->where('activo', true)
            ->whereIn('estado', ['VIGENTE', 'MORA', 'RENOVADO'])
            ->with(['cliente'])
            ->get();

        foreach ($prestamos as $prestamo) {
            if (! $prestamo->cliente) {
                continue;
            }

            $diasParaVencer = $hoy->diffInDays($prestamo->fecha_vencimiento, false);

            if ($diasParaVencer >= 0 && ($diasParaVencer <= $diasAntes || ($mismoDia && $diasParaVencer === 0))) {
                $enviados += $this->generar($notificacionService, $prestamo, 'RECORDATORIO', 'WHATSAPP', $plantillaRecordatorio);
            }

            if ($prestamo->estado === 'MORA') {
                $diasEnMora = $hoy->diffInDays($prestamo->fecha_vencimiento, false) * -1;

                if ($diasEnMora >= $diasMora) {
                    $enviados += $this->generar($notificacionService, $prestamo, 'MORA', 'WHATSAPP', $plantillaMora);
                }
            }
        }

        $this->info("Recordatorios simulados generados: {$enviados}");

        return self::SUCCESS;
    }

    private function generar(
        NotificacionService $service,
        Prestamo $prestamo,
        string $tipo,
        string $canal,
        ?PlantillaMensaje $plantilla,
    ): int {
        if ($service->yaEnviadoHoy($prestamo->id_prestamo, $tipo, $canal)) {
            return 0;
        }

        $service->enviarSimulado($prestamo->cliente, $prestamo, $plantilla, $canal, $tipo);

        return 1;
    }
}
