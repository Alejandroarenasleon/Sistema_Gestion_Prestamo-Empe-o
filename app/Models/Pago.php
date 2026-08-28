<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pago extends Model
{
    protected $table = 'pago';

    protected $primaryKey = 'id_pago';

    public $timestamps = false;

    protected $fillable = [
        'id_prestamo',
        'id_usuario',
        'tipo',
        'monto',
        'interes_periodo_calculado',
        'saldo_capital_resultante',
        'nueva_fecha_vencimiento',
        'fecha',
    ];

    protected function casts(): array
    {
        return [
            'monto' => 'decimal:2',
            'interes_periodo_calculado' => 'decimal:2',
            'saldo_capital_resultante' => 'decimal:2',
            'nueva_fecha_vencimiento' => 'date',
            'fecha' => 'datetime',
        ];
    }

    public function prestamo(): BelongsTo
    {
        return $this->belongsTo(Prestamo::class, 'id_prestamo', 'id_prestamo');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function recibo(): HasOne
    {
        return $this->hasOne(Recibo::class, 'id_pago', 'id_pago');
    }
}
