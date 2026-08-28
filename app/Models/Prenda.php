<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Prenda extends Model
{
    protected $table = 'prenda';

    protected $primaryKey = 'id_prenda';

    public $timestamps = false;

    protected $fillable = [
        'id_prestamo',
        'categoria',
        'descripcion',
        'marca',
        'modelo',
        'material',
        'peso_gramos',
        'numero_serie_imei',
        'estado_fisico_obs',
        'avaluo',
        'estado',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'peso_gramos' => 'decimal:2',
            'avaluo' => 'decimal:2',
            'activo' => 'boolean',
        ];
    }

    public function prestamo(): BelongsTo
    {
        return $this->belongsTo(Prestamo::class, 'id_prestamo', 'id_prestamo');
    }

    public function fotos(): HasMany
    {
        return $this->hasMany(FotoPrenda::class, 'id_prenda', 'id_prenda');
    }

    public function historialEstados(): HasMany
    {
        return $this->hasMany(HistorialEstadoPrenda::class, 'id_prenda', 'id_prenda');
    }

    public function remate(): HasOne
    {
        return $this->hasOne(Remate::class, 'id_prenda', 'id_prenda');
    }

    public function cambiarEstado(string $nuevo, string $evento, ?int $usuarioId = null): void
    {
        $anterior = $this->estado;

        $this->update(['estado' => $nuevo]);

        HistorialEstadoPrenda::create([
            'id_prenda' => $this->id_prenda,
            'estado_anterior' => $anterior,
            'estado_nuevo' => $nuevo,
            'evento' => $evento,
            'id_usuario' => $usuarioId,
            'fecha' => now(),
        ]);
    }
}
