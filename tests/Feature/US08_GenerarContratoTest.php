<?php

namespace Tests\Feature;

use Tests\TestCase;

class US08_GenerarContratoTest extends TestCase
{
    public function test_contrato_se_puede_generar(): void
    {
        $response = $this->get('/prestamos/1/contrato');

        $response->assertStatus(200);
    }
}