<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\CotizacionOro;
use App\Models\Parametro;
use App\Models\PlantillaMensaje;
use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Usuario::create([
            'nombre_completo' => 'Administrador Trueque Cash',
            'login' => 'admin',
            'password_hash' => Hash::make('admin123'),
            'rol' => 'ADMIN',
            'activo' => true,
        ]);

        Usuario::create([
            'nombre_completo' => 'Operador Mostrador',
            'login' => 'operador',
            'password_hash' => Hash::make('operador123'),
            'rol' => 'OPERADOR',
            'activo' => true,
        ]);

        $parametros = [
            ['clave' => 'TASA_INTERES_DEFAULT', 'valor' => '10', 'descripcion' => 'Tasa de interés mensual por defecto (%)'],
            ['clave' => 'PORCENTAJE_PRESTAMO_ORO', 'valor' => '65', 'descripcion' => 'Porcentaje máximo sobre avalúo para joyas/oro'],
            ['clave' => 'PORCENTAJE_PRESTAMO_ELECTRONICOS', 'valor' => '35', 'descripcion' => 'Porcentaje máximo sobre avalúo para electrónicos/herramientas'],
            ['clave' => 'UMBRAL_DOCUMENTOS_EXTRA', 'valor' => '3000', 'descripcion' => 'Monto (Bs) que exige referencia o comprobante de domicilio'],
            ['clave' => 'DIAS_GRACIA', 'valor' => '15', 'descripcion' => 'Días de gracia tras mora antes de remate'],
            ['clave' => 'RECORDATORIO_DIAS_ANTES', 'valor' => '3', 'descripcion' => 'Anticipación (días antes del vencimiento) para recordatorio de vencimiento'],
            ['clave' => 'RECORDATORIO_MISMO_DIA', 'valor' => '1', 'descripcion' => 'Enviar recordatorio el mismo día del vencimiento (1=si, 0=no)'],
            ['clave' => 'RECORDATORIO_DIAS_MORA', 'valor' => '3', 'descripcion' => 'Días en mora para enviar aviso de mora'],
            ['clave' => 'AVISO_FIRME_DIAS_MORA', 'valor' => '15', 'descripcion' => 'Días en mora para aviso firme de remate'],
        ];

        foreach ($parametros as $p) {
            Parametro::create($p);
        }

        CotizacionOro::create([
            'quilate' => '18K',
            'precio_gramo' => 45.50,
            'fecha' => now()->toDateString(),
            'id_usuario' => 1,
        ]);

        PlantillaMensaje::insert([
            ['tipo_aviso' => 'RECORDATORIO', 'contenido' => 'Estimado/a {nombre}, le recordamos que su préstamo vence el {fecha}. Trueque Cash.', 'activo' => true],
            ['tipo_aviso' => 'MORA', 'contenido' => 'Estimado/a {nombre}, su préstamo está en mora. Acérquese a Trueque Cash.', 'activo' => true],
            ['tipo_aviso' => 'AVISO_REMATE', 'contenido' => 'Estimado/a {nombre}, su prenda está disponible para remate. Trueque Cash.', 'activo' => true],
        ]);

        Cliente::create([
            'ci' => '1234567-TJ',
            'nombre_completo' => 'María Ejemplo Pérez',
            'direccion' => 'Av. Las Américas, Tarija',
            'celular' => '77123456',
            'foto_ci_anverso' => 'demo/ci_anverso.jpg',
            'foto_ci_reverso' => 'demo/ci_reverso.jpg',
            'alerta_riesgo' => false,
            'activo' => true,
        ]);
    }
}
