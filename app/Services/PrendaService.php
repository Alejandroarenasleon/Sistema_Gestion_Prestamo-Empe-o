<?php

namespace App\Services;

use App\Models\CotizacionOro;
use App\Models\Parametro;

class PrendaService
{
    public function calcularMaximoPrestamo(float $avaluo, string $categoria): float
    {
        $porcentaje = $this->porcentajePorCategoria($categoria);

        return round($avaluo * ($porcentaje / 100), 2);
    }

    public function calcularAvaluoOro(float $peso, string $quilate): float
    {
        $cotizacion = CotizacionOro::where('quilate', $quilate)
            ->orderByDesc('fecha')
            ->first();

        if ($cotizacion === null) {
            return 0.0;
        }

        return round($peso * (float) $cotizacion->precio_gramo, 2);
    }

    private function porcentajePorCategoria(string $categoria): float
    {
        $categoria = mb_strtolower(trim($categoria));

        $categoriasAlto = ['oro', 'joyas', 'joya', 'joyería', 'joyeria'];

        if (in_array($categoria, $categoriasAlto, true)) {
            return (float) Parametro::getValor('PORCENTAJE_PRESTAMO_ORO', 65);
        }

        return (float) Parametro::getValor('PORCENTAJE_PRESTAMO_ELECTRONICOS', 35);
    }
}
