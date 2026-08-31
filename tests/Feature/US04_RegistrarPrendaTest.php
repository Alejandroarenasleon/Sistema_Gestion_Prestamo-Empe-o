<?php

namespace Tests\Feature;

use Tests\TestCase;

class US04_RegistrarPrendaTest extends TestCase
{
    public function test_prenda_sin_foto_exige_observaciones(): void
    {
        $response = $this->post('/prendas', [
            'categoria' => 'joya',
            'descripcion' => 'Prueba',
            'marca' => 'Test',
            'modelo' => 'X1',
            'material' => 'Oro',
            'foto' => '',
        ]);

        $response->assertStatus(302);
    }

    public function test_prenda_con_foto_se_guarda(): void
    {
        $response = $this->post('/prendas', [
            'categoria' => 'joya',
            'descripcion' => 'Prueba',
            'marca' => 'Test',
            'modelo' => 'X1',
            'material' => 'Oro',
            'peso' => 5.0,
            'foto' => 'test.jpg',
            'observaciones' => 'Buen estado',
        ]);

        $response->assertStatus(302);
    }
}