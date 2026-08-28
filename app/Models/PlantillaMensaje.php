<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlantillaMensaje extends Model
{
    protected $table = 'plantilla_mensaje';

    protected $primaryKey = 'id_plantilla';

    public $timestamps = false;

    protected $fillable = [
        'tipo_aviso',
        'contenido',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function notificaciones(): HasMany
    {
        return $this->hasMany(Notificacion::class, 'id_plantilla', 'id_plantilla');
    }
}
