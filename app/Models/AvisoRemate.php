<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AvisoRemate extends Model
{
    protected $table = 'aviso_remate';

    protected $primaryKey = 'id_aviso';

    public $timestamps = false;

    protected $fillable = [
        'id_prenda',
        'precio_ofertado',
        'fecha_solicitud',
        'aprobado',
        'id_usuario_aprobo',
        'fecha_aprobacion',
    ];

    protected function casts(): array
    {
        return [
            'fecha_solicitud' => 'datetime',
            'aprobado' => 'boolean',
            'fecha_aprobacion' => 'datetime',
        ];
    }

    public function prenda(): BelongsTo
    {
        return $this->belongsTo(Prenda::class, 'id_prenda', 'id_prenda');
    }

    public function usuarioAprobo(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario_aprobo', 'id_usuario');
    }
}
