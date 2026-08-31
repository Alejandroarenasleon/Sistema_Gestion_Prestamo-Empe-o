<?php

namespace Tests\Feature;

use Tests\TestCase;

class US01_RegistrarClienteTest extends TestCase
{
    public function test_cliente_requiere_ci_y_fotos(): void
    {
        $response = $this->post('/clientes', [
            'ci' => '',
            'foto_ci_delante' => '',
            'foto_ci_detras' => '',
            'celular' => '',
        ]);

        $response->assertStatus(302);
    }

    public function test_cliente_ci_existente_rechaza_registro(): void
    {
        $this->post('/clientes', [
            'ci' => '12345678',
            'foto_ci_delante' => 'test.jpg',
            'foto_ci_detras' => 'test.jpg',
            'celular' => '3001234567',
        ]);

        $this->post('/clientes', [
            'ci' => '12345678',
            'foto_ci_delante' => 'test.jpg',
            'foto_ci_detras' => 'test.jpg',
            'celular' => '3001234567',
        ]);

        $response = $this->post('/clientes', [
            'ci' => '12345678',
            'foto_ci_delante' => 'test.jpg',
            'foto_ci_detras' => 'test.jpg',
            'celular' => '3001234567',
        ]);

        $response->assertStatus(302);
    }
}