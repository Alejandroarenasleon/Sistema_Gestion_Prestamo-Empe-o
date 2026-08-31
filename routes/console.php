<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Proceso programado diario (US-13): actualiza estados de mora/remate y
// genera la cola de recordatorios simulados sin intervención del operador.
Schedule::command('trueque:actualizar-mora')->dailyAt('00:01');
Schedule::command('trueque:enviar-recordatorios')->dailyAt('08:00');
