<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recibo extends Model
{
    protected $table = 'recibo';

    protected $primaryKey = 'id_recibo';

    public $timestamps = false;

    protected $fillable = [
        'id_pago',
        'canal',
        'pdf_url',
        'fecha_generacion',
    ];

    protected function casts(): array
    {
        return [
            'fecha_generacion' => 'datetime',
        ];
    }

    public function pago(): BelongsTo
    {
        return $this->belongsTo(Pago::class, 'id_pago', 'id_pago');
    }
}
