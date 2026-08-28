<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CotizacionOro extends Model
{
    protected $table = 'cotizacion_oro';

    protected $primaryKey = 'id_cotizacion';

    public $timestamps = false;

    protected $fillable = [
        'quilate',
        'precio_gramo',
        'fecha',
        'id_usuario',
    ];

    protected function casts(): array
    {
        return [
            'precio_gramo' => 'decimal:2',
            'fecha' => 'date',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}
