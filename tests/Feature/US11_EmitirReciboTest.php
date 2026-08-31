<?php

namespace Tests\Feature;

use Tests\TestCase;

class US11_EmitirReciboTest extends TestCase
{
    public function test_recibo_se_genera(): void
    {
        $response = $this->post('/recibos', [
            'prestamo_id' => 1,
            'monto' => 500,
            'tipo' => 'interes',
        ]);

        $response->assertStatus(302);
    }
}