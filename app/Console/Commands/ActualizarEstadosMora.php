<?php

namespace App\Console\Commands;

use App\Services\PrestamoService;
use Illuminate\Console\Command;

class ActualizarEstadosMora extends Command
{
    protected $signature = 'trueque:actualizar-mora';

    protected $description = 'Actualiza préstamos en mora, gracia y disponibles para remate';

    public function handle(PrestamoService $prestamoService): int
    {
        $count = $prestamoService->actualizarEstadosMora();
        $this->info("Estados actualizados: {$count}");

        return self::SUCCESS;
    }
}
