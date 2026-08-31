<?php

namespace Tests\Feature;

use Tests\TestCase;

class US10_RenovarPrestamoTest extends TestCase
{
    public function test_prestamo_puede_renovar(): void
    {
        $response = $this->post('/prestamos/1/renovar');

        $response->assertStatus(302);
    }
}