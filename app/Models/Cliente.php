<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    protected $table = 'cliente';

    protected $primaryKey = 'id_cliente';

    public $timestamps = false;

    protected $fillable = [
        'ci',
        'nombre_completo',
        'direccion',
        'celular',
        'foto_ci_anverso',
        'foto_ci_reverso',
        'referencia_contacto',
        'comprobante_domicilio',
        'alerta_riesgo',
        'motivo_alerta',
        'fecha_registro',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'alerta_riesgo' => 'boolean',
            'activo' => 'boolean',
            'fecha_registro' => 'datetime',
        ];
    }

    public function prestamos(): HasMany
    {
        return $this->hasMany(Prestamo::class, 'id_cliente', 'id_cliente');
    }

    public function notificaciones(): HasMany
    {
        return $this->hasMany(Notificacion::class, 'id_cliente', 'id_cliente');
    }

    public function scopeActivos(Builder $query): Builder
    {
        return $query->where('activo', true);
    }

    public function scopeBuscar(Builder $query, ?string $term): Builder
    {
        if ($term === null || $term === '') {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term) {
            $q->where('ci', 'like', "%{$term}%")
                ->orWhere('nombre_completo', 'like', "%{$term}%")
                ->orWhere('celular', 'like', "%{$term}%");
        });
    }
}
