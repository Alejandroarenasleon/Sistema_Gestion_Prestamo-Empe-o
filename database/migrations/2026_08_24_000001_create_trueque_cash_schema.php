<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');

        Schema::create('usuario', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->string('nombre_completo', 120);
            $table->string('login', 40)->unique();
            $table->string('password_hash', 255);
            $table->enum('rol', ['ADMIN', 'OPERADOR']);
            $table->boolean('activo')->default(true);
            $table->timestamp('fecha_creacion')->useCurrent();
        });

        Schema::create('cliente', function (Blueprint $table) {
            $table->id('id_cliente');
            $table->string('ci', 20)->unique();
            $table->string('nombre_completo', 150);
            $table->string('direccion', 200)->nullable();
            $table->string('celular', 20);
            $table->string('foto_ci_anverso', 255);
            $table->string('foto_ci_reverso', 255);
            $table->string('referencia_contacto', 150)->nullable();
            $table->string('comprobante_domicilio', 255)->nullable();
            $table->boolean('alerta_riesgo')->default(false);
            $table->string('motivo_alerta', 255)->nullable();
            $table->timestamp('fecha_registro')->useCurrent();
            $table->boolean('activo')->default(true);
            $table->index('ci');
            $table->index('celular');
        });

        Schema::create('parametro', function (Blueprint $table) {
            $table->id('id_parametro');
            $table->string('clave', 60)->unique();
            $table->string('valor', 255);
            $table->string('descripcion', 255)->nullable();
            $table->foreignId('id_usuario_modifico')->nullable()->constrained('usuario', 'id_usuario');
            $table->timestamp('fecha_modificacion')->useCurrent();
        });

        Schema::create('cotizacion_oro', function (Blueprint $table) {
            $table->id('id_cotizacion');
            $table->string('quilate', 10);
            $table->decimal('precio_gramo', 10, 2);
            $table->date('fecha');
            $table->foreignId('id_usuario')->nullable()->constrained('usuario', 'id_usuario');
        });

        Schema::create('prestamo', function (Blueprint $table) {
            $table->id('id_prestamo');
            $table->foreignId('id_cliente')->constrained('cliente', 'id_cliente');
            $table->foreignId('id_usuario_registro')->constrained('usuario', 'id_usuario');
            $table->decimal('monto_capital', 12, 2);
            $table->decimal('tasa_interes_mensual', 5, 2);
            $table->date('fecha_emision');
            $table->date('fecha_vencimiento');
            $table->enum('estado', ['VIGENTE', 'MORA', 'RENOVADO', 'CANCELADO'])->default('VIGENTE');
            $table->boolean('requiere_aprobacion')->default(false);
            $table->boolean('activo')->default(true);
            $table->index('id_cliente');
            $table->index('estado');
            $table->index('fecha_vencimiento');
        });

        Schema::create('prenda', function (Blueprint $table) {
            $table->id('id_prenda');
            $table->foreignId('id_prestamo')->constrained('prestamo', 'id_prestamo');
            $table->string('categoria', 40);
            $table->string('descripcion', 255);
            $table->string('marca', 60)->nullable();
            $table->string('modelo', 60)->nullable();
            $table->string('material', 60)->nullable();
            $table->decimal('peso_gramos', 8, 2)->nullable();
            $table->string('numero_serie_imei', 60)->nullable();
            $table->text('estado_fisico_obs')->nullable();
            $table->decimal('avaluo', 12, 2);
            $table->enum('estado', [
                'RECIBIDA', 'VIGENTE', 'EN_MORA', 'EN_GRACIA',
                'DISPONIBLE_REMATE', 'VENDIDA', 'RENOVADA', 'DEVUELTA',
            ])->default('RECIBIDA');
            $table->boolean('activo')->default(true);
            $table->index('id_prestamo');
            $table->index('estado');
        });

        Schema::create('foto_prenda', function (Blueprint $table) {
            $table->id('id_foto');
            $table->foreignId('id_prenda')->constrained('prenda', 'id_prenda');
            $table->string('url', 255);
            $table->timestamp('fecha_hora')->useCurrent();
        });

        Schema::create('historial_estado_prenda', function (Blueprint $table) {
            $table->id('id_historial');
            $table->foreignId('id_prenda')->constrained('prenda', 'id_prenda');
            $table->string('estado_anterior', 25)->nullable();
            $table->string('estado_nuevo', 25);
            $table->string('evento', 120);
            $table->foreignId('id_usuario')->nullable()->constrained('usuario', 'id_usuario');
            $table->timestamp('fecha')->useCurrent();
            $table->index('id_prenda');
        });

        Schema::create('pago', function (Blueprint $table) {
            $table->id('id_pago');
            $table->foreignId('id_prestamo')->constrained('prestamo', 'id_prestamo');
            $table->foreignId('id_usuario')->constrained('usuario', 'id_usuario');
            $table->enum('tipo', ['INTERES', 'ABONO', 'CANCELACION', 'RENOVACION']);
            $table->decimal('monto', 12, 2);
            $table->decimal('interes_periodo_calculado', 12, 2)->nullable();
            $table->decimal('saldo_capital_resultante', 12, 2);
            $table->date('nueva_fecha_vencimiento')->nullable();
            $table->timestamp('fecha')->useCurrent();
            $table->index('id_prestamo');
            $table->index('fecha');
        });

        Schema::create('contrato', function (Blueprint $table) {
            $table->id('id_contrato');
            $table->foreignId('id_prestamo')->unique()->constrained('prestamo', 'id_prestamo');
            $table->string('pdf_url', 255);
            $table->timestamp('fecha_generacion')->useCurrent();
        });

        Schema::create('recibo', function (Blueprint $table) {
            $table->id('id_recibo');
            $table->foreignId('id_pago')->unique()->constrained('pago', 'id_pago');
            $table->enum('canal', ['TERMICA', 'PDF', 'WHATSAPP', 'SMS']);
            $table->string('pdf_url', 255)->nullable();
            $table->timestamp('fecha_generacion')->useCurrent();
        });

        Schema::create('remate', function (Blueprint $table) {
            $table->id('id_remate');
            $table->foreignId('id_prenda')->unique()->constrained('prenda', 'id_prenda');
            $table->decimal('precio_venta', 12, 2);
            $table->string('comprador', 150)->nullable();
            $table->decimal('resultado', 12, 2);
            $table->date('fecha_venta');
            $table->foreignId('id_usuario_aprobo')->constrained('usuario', 'id_usuario');
        });

        Schema::create('aviso_remate', function (Blueprint $table) {
            $table->id('id_aviso');
            $table->foreignId('id_prenda')->constrained('prenda', 'id_prenda');
            $table->timestamp('fecha_solicitud')->useCurrent();
            $table->boolean('aprobado')->nullable();
            $table->foreignId('id_usuario_aprobo')->nullable()->constrained('usuario', 'id_usuario');
            $table->timestamp('fecha_aprobacion')->nullable();
        });

        Schema::create('solicitud_aprobacion', function (Blueprint $table) {
            $table->id('id_solicitud');
            $table->enum('tipo', ['PRESTAMO_RIESGO', 'VENTA_PRENDA', 'AVISO_REMATE']);
            $table->unsignedBigInteger('referencia_id');
            $table->foreignId('id_usuario_solicito')->constrained('usuario', 'id_usuario');
            $table->enum('estado', ['PENDIENTE', 'APROBADO', 'RECHAZADO'])->default('PENDIENTE');
            $table->string('motivo', 255)->nullable();
            $table->timestamp('fecha_solicitud')->useCurrent();
            $table->foreignId('id_usuario_resolvio')->nullable()->constrained('usuario', 'id_usuario');
            $table->timestamp('fecha_resolucion')->nullable();
            $table->index('estado');
        });

        Schema::create('plantilla_mensaje', function (Blueprint $table) {
            $table->id('id_plantilla');
            $table->string('tipo_aviso', 30);
            $table->text('contenido');
            $table->boolean('activo')->default(true);
        });

        Schema::create('notificacion', function (Blueprint $table) {
            $table->id('id_notificacion');
            $table->foreignId('id_cliente')->constrained('cliente', 'id_cliente');
            $table->foreignId('id_prestamo')->nullable()->constrained('prestamo', 'id_prestamo');
            $table->foreignId('id_plantilla')->nullable()->constrained('plantilla_mensaje', 'id_plantilla');
            $table->string('tipo', 30);
            $table->enum('canal', ['WHATSAPP', 'SMS']);
            $table->string('estado_envio', 10);
            $table->timestamp('fecha_hora')->useCurrent();
            $table->index('id_cliente');
        });

        Schema::create('cierre_caja', function (Blueprint $table) {
            $table->id('id_cierre');
            $table->date('fecha')->unique();
            $table->decimal('efectivo_esperado', 12, 2);
            $table->decimal('efectivo_fisico', 12, 2)->nullable();
            $table->decimal('diferencia', 12, 2)->nullable();
            $table->string('observacion', 255)->nullable();
            $table->boolean('confirmado')->default(false);
            $table->foreignId('id_usuario')->constrained('usuario', 'id_usuario');
        });

        Schema::create('auditoria', function (Blueprint $table) {
            $table->id('id_auditoria');
            $table->foreignId('id_usuario')->constrained('usuario', 'id_usuario');
            $table->string('entidad', 40);
            $table->unsignedBigInteger('entidad_id');
            $table->enum('accion', ['CREAR', 'MODIFICAR', 'ELIMINAR']);
            $table->json('valor_anterior')->nullable();
            $table->json('valor_nuevo')->nullable();
            $table->timestamp('fecha_hora')->useCurrent();
            $table->index(['entidad', 'entidad_id']);
            $table->index('id_usuario');
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auditoria');
        Schema::dropIfExists('cierre_caja');
        Schema::dropIfExists('notificacion');
        Schema::dropIfExists('plantilla_mensaje');
        Schema::dropIfExists('solicitud_aprobacion');
        Schema::dropIfExists('aviso_remate');
        Schema::dropIfExists('remate');
        Schema::dropIfExists('recibo');
        Schema::dropIfExists('contrato');
        Schema::dropIfExists('pago');
        Schema::dropIfExists('historial_estado_prenda');
        Schema::dropIfExists('foto_prenda');
        Schema::dropIfExists('prenda');
        Schema::dropIfExists('prestamo');
        Schema::dropIfExists('cotizacion_oro');
        Schema::dropIfExists('parametro');
        Schema::dropIfExists('cliente');
        Schema::dropIfExists('usuario');
    }
};
