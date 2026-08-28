<?php

namespace App\Services;

use App\Models\Auditoria;

class AuditoriaService
{
    public function log(
        int $usuarioId,
        string $entidad,
        int $entidadId,
        string $accion,
        ?array $anterior = null,
        ?array $nuevo = null
    ): Auditoria {
        return Auditoria::create([
            'id_usuario' => $usuarioId,
            'entidad' => $entidad,
            'entidad_id' => $entidadId,
            'accion' => $accion,
            'valor_anterior' => $anterior,
            'valor_nuevo' => $nuevo,
            'fecha_hora' => now(),
        ]);
    }
}
