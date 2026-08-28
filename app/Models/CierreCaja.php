<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CierreCaja extends Model
{
    protected $table = 'cierre_caja';

    protected $primaryKey = 'id_cierre';

    public $timestamps = false;

    protected $fillable = [
        'fecha',
        'efectivo_esperado',
        'efectivo_fisico',
        'diferencia',
        'observacion',
        'confirmado',
        'id_usuario',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'efectivo_esperado' => 'decimal:2',
            'efectivo_fisico' => 'decimal:2',
            'diferencia' => 'decimal:2',
            'confirmado' => 'boolean',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}
