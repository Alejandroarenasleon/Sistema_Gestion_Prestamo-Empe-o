<?php

namespace Tests\Feature;

use Tests\TestCase;

class US09_CobrarInteresTest extends TestCase
{
    public function test_pago_interes_registra_cobro(): void
    {
        $response = $this->post('/cobros', [
            'prestamo_id' => 1,
            'monto' => 500,
            'tipo' => 'interes',
        ]);

        $response->assertStatus(302);
    }
}