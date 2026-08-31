<?php

namespace Tests\Feature;

use Tests\TestCase;

class US23_BandejaAprobacionesTest extends TestCase
{
    public function test_bandeja_se_visualiza(): void
    {
        $response = $this->get('/aprobaciones-pendientes');

        $response->assertStatus(200);
    }
}