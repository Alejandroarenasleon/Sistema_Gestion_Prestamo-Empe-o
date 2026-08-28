<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistorialEstadoPrenda extends Model
{
    protected $table = 'historial_estado_prenda';

    protected $primaryKey = 'id_historial';

    public $timestamps = false;

    protected $fillable = [
        'id_prenda',
        'estado_anterior',
        'estado_nuevo',
        'evento',
        'id_usuario',
        'fecha',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'datetime',
        ];
    }

    public function prenda(): BelongsTo
    {
        return $this->belongsTo(Prenda::class, 'id_prenda', 'id_prenda');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}
