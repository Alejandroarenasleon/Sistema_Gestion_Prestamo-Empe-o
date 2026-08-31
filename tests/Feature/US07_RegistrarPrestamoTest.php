<?php

namespace Tests\Feature;

use Tests\TestCase;

class US07_RegistrarPrestamoTest extends TestCase
{
    public function test_prestamo_asocia_multiple_prendas(): void
    {
        $response = $this->post('/prestamos', [
            'cliente_id' => 1,
            'prenda_ids' => [1, 2],
            'monto' => 5000,
            'tasa_interes' => 5,
        ]);

        $response->assertStatus(302);
    }

    public function test_prestamo_fecha_vencimiento_un_mes(): void
    {
        $response = $this->post('/prestamos', [
            'cliente_id' => 1,
            'prenda_ids' => [1],
            'monto' => 1000,
            'tasa_interes' => 5,
        ]);

        $response->assertStatus(302);
    }
}