<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuario';

    protected $primaryKey = 'id_usuario';

    public $timestamps = false;

    protected $fillable = [
        'nombre_completo',
        'login',
        'password_hash',
        'rol',
        'activo',
        'fecha_creacion',
    ];

    protected $hidden = [
        'password_hash',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
            'fecha_creacion' => 'datetime',
        ];
    }

    public function getAuthPassword(): string
    {
        return $this->password_hash;
    }

    public function username(): string
    {
        return 'login';
    }

    public function isAdmin(): bool
    {
        return $this->rol === 'ADMIN';
    }

    public function prestamosRegistrados(): HasMany
    {
        return $this->hasMany(Prestamo::class, 'id_usuario_registro', 'id_usuario');
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class, 'id_usuario', 'id_usuario');
    }

    public function auditorias(): HasMany
    {
        return $this->hasMany(Auditoria::class, 'id_usuario', 'id_usuario');
    }

    public function cierresCaja(): HasMany
    {
        return $this->hasMany(CierreCaja::class, 'id_usuario', 'id_usuario');
    }
}
