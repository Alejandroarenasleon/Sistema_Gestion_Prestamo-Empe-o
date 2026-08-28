-- ============================================================
-- Trueque Cash — Modelo físico MySQL 8+
-- Sistema de Gestión para Casa de Préstamo y Empeño
-- Exportable desde Laravel: php artisan migrate + db:seed
-- ============================================================

CREATE DATABASE IF NOT EXISTS truequecash CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE truequecash;

CREATE TABLE usuario (
  id_usuario BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre_completo VARCHAR(120) NOT NULL,
  login VARCHAR(40) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  rol ENUM('ADMIN','OPERADOR') NOT NULL,
  activo TINYINT(1) NOT NULL DEFAULT 1,
  fecha_creacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE cliente (
  id_cliente BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  ci VARCHAR(20) NOT NULL UNIQUE,
  nombre_completo VARCHAR(150) NOT NULL,
  direccion VARCHAR(200) NULL,
  celular VARCHAR(20) NOT NULL,
  foto_ci_anverso VARCHAR(255) NOT NULL,
  foto_ci_reverso VARCHAR(255) NOT NULL,
  referencia_contacto VARCHAR(150) NULL,
  comprobante_domicilio VARCHAR(255) NULL,
  alerta_riesgo TINYINT(1) NOT NULL DEFAULT 0,
  motivo_alerta VARCHAR(255) NULL,
  fecha_registro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  activo TINYINT(1) NOT NULL DEFAULT 1,
  INDEX idx_cliente_ci (ci),
  INDEX idx_cliente_celular (celular)
) ENGINE=InnoDB;

CREATE TABLE parametro (
  id_parametro BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  clave VARCHAR(60) NOT NULL UNIQUE,
  valor VARCHAR(255) NOT NULL,
  descripcion VARCHAR(255) NULL,
  id_usuario_modifico BIGINT UNSIGNED NULL,
  fecha_modificacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_usuario_modifico) REFERENCES usuario(id_usuario)
) ENGINE=InnoDB;

CREATE TABLE cotizacion_oro (
  id_cotizacion BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  quilate VARCHAR(10) NOT NULL,
  precio_gramo DECIMAL(10,2) NOT NULL,
  fecha DATE NOT NULL,
  id_usuario BIGINT UNSIGNED NULL,
  FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
) ENGINE=InnoDB;

CREATE TABLE prestamo (
  id_prestamo BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_cliente BIGINT UNSIGNED NOT NULL,
  id_usuario_registro BIGINT UNSIGNED NOT NULL,
  monto_capital DECIMAL(12,2) NOT NULL,
  tasa_interes_mensual DECIMAL(5,2) NOT NULL,
  fecha_emision DATE NOT NULL,
  fecha_vencimiento DATE NOT NULL,
  estado ENUM('VIGENTE','MORA','RENOVADO','CANCELADO') NOT NULL DEFAULT 'VIGENTE',
  requiere_aprobacion TINYINT(1) NOT NULL DEFAULT 0,
  activo TINYINT(1) NOT NULL DEFAULT 1,
  FOREIGN KEY (id_cliente) REFERENCES cliente(id_cliente),
  FOREIGN KEY (id_usuario_registro) REFERENCES usuario(id_usuario),
  INDEX idx_prestamo_cliente (id_cliente),
  INDEX idx_prestamo_estado (estado),
  INDEX idx_prestamo_vencim (fecha_vencimiento)
) ENGINE=InnoDB;

CREATE TABLE prenda (
  id_prenda BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_prestamo BIGINT UNSIGNED NOT NULL,
  categoria VARCHAR(40) NOT NULL,
  descripcion VARCHAR(255) NOT NULL,
  marca VARCHAR(60) NULL,
  modelo VARCHAR(60) NULL,
  material VARCHAR(60) NULL,
  peso_gramos DECIMAL(8,2) NULL,
  numero_serie_imei VARCHAR(60) NULL,
  estado_fisico_obs TEXT NULL,
  avaluo DECIMAL(12,2) NOT NULL,
  estado ENUM('RECIBIDA','VIGENTE','EN_MORA','EN_GRACIA','DISPONIBLE_REMATE','VENDIDA','RENOVADA','DEVUELTA') NOT NULL DEFAULT 'RECIBIDA',
  activo TINYINT(1) NOT NULL DEFAULT 1,
  FOREIGN KEY (id_prestamo) REFERENCES prestamo(id_prestamo),
  INDEX idx_prenda_prestamo (id_prestamo),
  INDEX idx_prenda_estado (estado)
) ENGINE=InnoDB;

CREATE TABLE foto_prenda (
  id_foto BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_prenda BIGINT UNSIGNED NOT NULL,
  url VARCHAR(255) NOT NULL,
  fecha_hora TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_prenda) REFERENCES prenda(id_prenda)
) ENGINE=InnoDB;

CREATE TABLE historial_estado_prenda (
  id_historial BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_prenda BIGINT UNSIGNED NOT NULL,
  estado_anterior VARCHAR(25) NULL,
  estado_nuevo VARCHAR(25) NOT NULL,
  evento VARCHAR(120) NOT NULL,
  id_usuario BIGINT UNSIGNED NULL,
  fecha TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_prenda) REFERENCES prenda(id_prenda),
  FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
  INDEX idx_hist_prenda (id_prenda)
) ENGINE=InnoDB;

CREATE TABLE pago (
  id_pago BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_prestamo BIGINT UNSIGNED NOT NULL,
  id_usuario BIGINT UNSIGNED NOT NULL,
  tipo ENUM('INTERES','ABONO','CANCELACION','RENOVACION') NOT NULL,
  monto DECIMAL(12,2) NOT NULL,
  interes_periodo_calculado DECIMAL(12,2) NULL,
  saldo_capital_resultante DECIMAL(12,2) NOT NULL,
  nueva_fecha_vencimiento DATE NULL,
  fecha TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_prestamo) REFERENCES prestamo(id_prestamo),
  FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
  INDEX idx_pago_prestamo (id_prestamo),
  INDEX idx_pago_fecha (fecha)
) ENGINE=InnoDB;

CREATE TABLE contrato (
  id_contrato BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_prestamo BIGINT UNSIGNED NOT NULL UNIQUE,
  pdf_url VARCHAR(255) NOT NULL,
  fecha_generacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_prestamo) REFERENCES prestamo(id_prestamo)
) ENGINE=InnoDB;

CREATE TABLE recibo (
  id_recibo BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_pago BIGINT UNSIGNED NOT NULL UNIQUE,
  canal ENUM('TERMICA','PDF','WHATSAPP','SMS') NOT NULL,
  pdf_url VARCHAR(255) NULL,
  fecha_generacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_pago) REFERENCES pago(id_pago)
) ENGINE=InnoDB;

CREATE TABLE remate (
  id_remate BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_prenda BIGINT UNSIGNED NOT NULL UNIQUE,
  precio_venta DECIMAL(12,2) NOT NULL,
  comprador VARCHAR(150) NULL,
  resultado DECIMAL(12,2) NOT NULL,
  fecha_venta DATE NOT NULL,
  id_usuario_aprobo BIGINT UNSIGNED NOT NULL,
  FOREIGN KEY (id_prenda) REFERENCES prenda(id_prenda),
  FOREIGN KEY (id_usuario_aprobo) REFERENCES usuario(id_usuario)
) ENGINE=InnoDB;

CREATE TABLE aviso_remate (
  id_aviso BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_prenda BIGINT UNSIGNED NOT NULL,
  fecha_solicitud TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  aprobado TINYINT(1) NULL,
  id_usuario_aprobo BIGINT UNSIGNED NULL,
  fecha_aprobacion TIMESTAMP NULL,
  FOREIGN KEY (id_prenda) REFERENCES prenda(id_prenda),
  FOREIGN KEY (id_usuario_aprobo) REFERENCES usuario(id_usuario)
) ENGINE=InnoDB;

CREATE TABLE solicitud_aprobacion (
  id_solicitud BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  tipo ENUM('PRESTAMO_RIESGO','VENTA_PRENDA','AVISO_REMATE') NOT NULL,
  referencia_id BIGINT UNSIGNED NOT NULL,
  id_usuario_solicito BIGINT UNSIGNED NOT NULL,
  estado ENUM('PENDIENTE','APROBADO','RECHAZADO') NOT NULL DEFAULT 'PENDIENTE',
  motivo VARCHAR(255) NULL,
  fecha_solicitud TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  id_usuario_resolvio BIGINT UNSIGNED NULL,
  fecha_resolucion TIMESTAMP NULL,
  FOREIGN KEY (id_usuario_solicito) REFERENCES usuario(id_usuario),
  FOREIGN KEY (id_usuario_resolvio) REFERENCES usuario(id_usuario),
  INDEX idx_solicitud_estado (estado)
) ENGINE=InnoDB;

CREATE TABLE plantilla_mensaje (
  id_plantilla BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  tipo_aviso VARCHAR(30) NOT NULL,
  contenido TEXT NOT NULL,
  activo TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB;

CREATE TABLE notificacion (
  id_notificacion BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_cliente BIGINT UNSIGNED NOT NULL,
  id_prestamo BIGINT UNSIGNED NULL,
  id_plantilla BIGINT UNSIGNED NULL,
  tipo VARCHAR(30) NOT NULL,
  canal ENUM('WHATSAPP','SMS') NOT NULL,
  estado_envio VARCHAR(10) NOT NULL,
  fecha_hora TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_cliente) REFERENCES cliente(id_cliente),
  FOREIGN KEY (id_prestamo) REFERENCES prestamo(id_prestamo),
  FOREIGN KEY (id_plantilla) REFERENCES plantilla_mensaje(id_plantilla),
  INDEX idx_notif_cliente (id_cliente)
) ENGINE=InnoDB;

CREATE TABLE cierre_caja (
  id_cierre BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  fecha DATE NOT NULL UNIQUE,
  efectivo_esperado DECIMAL(12,2) NOT NULL,
  efectivo_fisico DECIMAL(12,2) NULL,
  diferencia DECIMAL(12,2) NULL,
  observacion VARCHAR(255) NULL,
  confirmado TINYINT(1) NOT NULL DEFAULT 0,
  id_usuario BIGINT UNSIGNED NOT NULL,
  FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
) ENGINE=InnoDB;

CREATE TABLE auditoria (
  id_auditoria BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_usuario BIGINT UNSIGNED NOT NULL,
  entidad VARCHAR(40) NOT NULL,
  entidad_id BIGINT UNSIGNED NOT NULL,
  accion ENUM('CREAR','MODIFICAR','ELIMINAR') NOT NULL,
  valor_anterior JSON NULL,
  valor_nuevo JSON NULL,
  fecha_hora TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
  INDEX idx_auditoria_entidad (entidad, entidad_id),
  INDEX idx_auditoria_usuario (id_usuario)
) ENGINE=InnoDB;
