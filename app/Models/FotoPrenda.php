<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FotoPrenda extends Model
{
    protected $table = 'foto_prenda';

    protected $primaryKey = 'id_foto';

    public $timestamps = false;

    protected $fillable = [
        'id_prenda',
        'url',
        'fecha_hora',
    ];

    protected function casts(): array
    {
        return [
            'fecha_hora' => 'datetime',
        ];
    }

    public function prenda(): BelongsTo
    {
        return $this->belongsTo(Prenda::class, 'id_prenda', 'id_prenda');
    }
}
