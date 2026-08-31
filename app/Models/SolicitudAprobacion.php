<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SolicitudAprobacion extends Model
{
    protected $table = 'solicitud_aprobacion';

    protected $primaryKey = 'id_solicitud';

    public $timestamps = false;

    protected $fillable = [
        'tipo',
        'referencia_id',
        'id_usuario_solicito',
        'estado',
        'motivo',
        'fecha_solicitud',
        'id_usuario_resolvio',
        'fecha_resolucion',
    ];

    protected function casts(): array
    {
        return [
            'fecha_solicitud' => 'datetime',
            'fecha_resolucion' => 'datetime',
        ];
    }

    public function usuarioSolicito(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario_solicito', 'id_usuario');
    }

    public function usuarioResolvio(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario_resolvio', 'id_usuario');
    }

    public function avisoRemate(): HasOne
    {
        return $this->hasOne(AvisoRemate::class, 'id_prenda', 'referencia_id');
    }

    public function prenda(): BelongsTo
    {
        return $this->belongsTo(Prenda::class, 'referencia_id', 'id_prenda');
    }
}
