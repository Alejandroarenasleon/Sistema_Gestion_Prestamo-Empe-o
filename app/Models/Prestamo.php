<?php

namespace App\Models;

use App\Services\PrestamoService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Prestamo extends Model
{
    protected $table = 'prestamo';

    protected $primaryKey = 'id_prestamo';

    public $timestamps = false;

    protected $fillable = [
        'id_cliente',
        'id_usuario_registro',
        'monto_capital',
        'tasa_interes_mensual',
        'fecha_emision',
        'fecha_vencimiento',
        'estado',
        'requiere_aprobacion',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'monto_capital' => 'decimal:2',
            'tasa_interes_mensual' => 'decimal:2',
            'fecha_emision' => 'date',
            'fecha_vencimiento' => 'date',
            'requiere_aprobacion' => 'boolean',
            'activo' => 'boolean',
        ];
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }

    public function usuarioRegistro(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario_registro', 'id_usuario');
    }

    public function prendas(): HasMany
    {
        return $this->hasMany(Prenda::class, 'id_prestamo', 'id_prestamo');
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class, 'id_prestamo', 'id_prestamo');
    }

    public function contrato(): HasOne
    {
        return $this->hasOne(Contrato::class, 'id_prestamo', 'id_prestamo');
    }

    public function saldoCapital(): float
    {
        if ($this->estado === 'CANCELADO') {
            return 0.0;
        }

        $ultimoPago = $this->pagos()->orderByDesc('fecha')->first();

        return $ultimoPago
            ? (float) $ultimoPago->saldo_capital_resultante
            : (float) $this->monto_capital;
    }

    public function interesPendiente(): float
    {
        if ($this->estado === 'CANCELADO') {
            return 0.0;
        }

        $interesPeriodo = app(PrestamoService::class)->calcularInteresPeriodo($this);

        $inicioPeriodo = $this->pagos()
            ->where('tipo', 'RENOVACION')
            ->orderByDesc('fecha')
            ->value('fecha');

        if ($inicioPeriodo === null) {
            $inicioPeriodo = $this->fecha_emision;
        }

        $pagado = (float) $this->pagos()
            ->whereIn('tipo', ['INTERES', 'RENOVACION'])
            ->where('fecha', '>=', $inicioPeriodo)
            ->sum('monto');

        return max(0, round($interesPeriodo - $pagado, 2));
    }

    public function saldoTotal(): float
    {
        return round($this->saldoCapital() + $this->interesPendiente(), 2);
    }
}
