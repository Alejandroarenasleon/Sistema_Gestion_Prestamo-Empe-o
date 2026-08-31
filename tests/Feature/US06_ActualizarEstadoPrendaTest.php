<?php

namespace Tests\Feature;

use Tests\TestCase;

class US06_ActualizarEstadoPrendaTest extends TestCase
{
    public function test_estado_prenda_transiciona_con_evento(): void
    {
        $response = $this->post('/eventos/prenda/1/estado', [
            'estado' => 'Vigente',
        ]);

        $response->assertStatus(302);
    }

    public function test_vencimiento_cambio_estado(): void
    {
        $response = $this->post('/verificar-estados');

        $response->assertStatus(302);
    }
}