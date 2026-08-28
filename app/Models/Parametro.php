<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Parametro extends Model
{
    protected $table = 'parametro';

    protected $primaryKey = 'id_parametro';

    public $timestamps = false;

    protected $fillable = [
        'clave',
        'valor',
        'descripcion',
        'id_usuario_modifico',
        'fecha_modificacion',
    ];

    protected function casts(): array
    {
        return [
            'fecha_modificacion' => 'datetime',
        ];
    }

    public static function getValor(string $clave, mixed $default = null): mixed
    {
        $parametro = static::where('clave', $clave)->first();

        return $parametro !== null ? $parametro->valor : $default;
    }

    public function usuarioModifico(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario_modifico', 'id_usuario');
    }
}
