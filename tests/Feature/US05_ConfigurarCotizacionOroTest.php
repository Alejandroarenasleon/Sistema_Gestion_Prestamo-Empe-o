<?php

namespace Tests\Feature;

use Tests\TestCase;

class US05_ConfigurarCotizacionOroTest extends TestCase
{
    public function test_administrador_actualiza_cotizacion(): void
    {
        $response = $this->put('/cotizaciones-oro', [
            'valor_gramo_quilate' => 155000,
        ]);

        $response->assertStatus(302);
    }
}