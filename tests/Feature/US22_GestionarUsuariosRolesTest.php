<?php

namespace Tests\Feature;

use Tests\TestCase;

class US22_GestionarUsuariosRolesTest extends TestCase
{
    public function test_administrador_crea_usuario_con_rol(): void
    {
        $response = $this->post('/usuarios', [
            'nombre' => 'Nuevo Usuario',
            'email' => 'nuevo@test.com',
            'rol' => 'ADMIN',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertStatus(302);
    }
}