<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Remate extends Model
{
    protected $table = 'remate';

    protected $primaryKey = 'id_remate';

    public $timestamps = false;

    protected $fillable = [
        'id_prenda',
        'categoria',
        'precio_venta',
        'comprador',
        'resultado',
        'fecha_venta',
        'id_usuario_aprobo',
    ];

    protected function casts(): array
    {
        return [
            'precio_venta' => 'decimal:2',
            'resultado' => 'decimal:2',
            'fecha_venta' => 'date',
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
