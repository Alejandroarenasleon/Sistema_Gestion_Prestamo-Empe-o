# Trueque Cash — Sistema de Gestión (Laravel)

Sistema web de gestión integral para casa de préstamo y empeño, implementado en **Laravel 12** según el documento *TruequeCash_Fases_I-VI* (18 tablas, roles ADMIN/OPERADOR, auditoría, caja, remates, PDF).

## Requisitos

- PHP 8.2+
- Composer
- Extensiones PHP: mbstring, openssl, pdo, tokenizer, xml, ctype, json, fileinfo
- MySQL 8+ **o** SQLite (desarrollo)
- XAMPP / Laragon / servidor web con Apache o `php artisan serve`

## Instalación rápida

```bash
cd trueque-cash
composer install
copy .env.example .env
php artisan key:generate
```

### Opción A — SQLite (desarrollo / demo)

El proyecto ya viene configurado con SQLite. Ejecute:

```bash
php artisan migrate:fresh --seed
php artisan storage:link
php artisan serve
```

Abra: http://127.0.0.1:8000

### Opción B — MySQL (producción / exportación)

1. Cree la base de datos:

```sql
CREATE DATABASE truequecash CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

O importe el script completo:

```bash
mysql -u root -p < database/sql/trueque_cash_mysql.sql
```

2. Configure `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=truequecash
DB_USERNAME=root
DB_PASSWORD=
```

3. Ejecute migraciones y datos iniciales:

```bash
php artisan migrate:fresh --seed
php artisan storage:link
php artisan serve
```

## Usuarios de prueba

| Rol      | Usuario   | Contraseña   |
|----------|-----------|--------------|
| Admin    | `admin`   | `admin123`   |
| Operador | `operador`| `operador123`|

## Módulos implementados

- **Autenticación** con roles ADMIN / OPERADOR
- **Clientes** — registro con CI, fotos, búsqueda, alerta de riesgo
- **Préstamos y prendas** — múltiples garantías, contrato PDF
- **Cobros** — interés, abono, cancelación, renovación + recibo PDF
- **Remates** — listado, solicitud y registro con aprobación admin
- **Bandeja de aprobaciones** — préstamos en riesgo, ventas
- **Cierre de caja** — efectivo esperado vs. físico
- **Dashboard** — indicadores del día
- **Parámetros** — tasas, gracia, umbrales
- **Cotización de oro**
- **Notificaciones** — envío simulado (V1)
- **Auditoría** — registro append-only
- **Reportes** — exportación PDF (caja, intereses, remates)

## Exportar el proyecto

Para entregar o desplegar en otro equipo:

1. Copie la carpeta `trueque-cash` completa (sin `vendor` si prefiere reinstalar).
2. Incluya `database/sql/trueque_cash_mysql.sql` para recrear la BD en MySQL.
3. En destino: `composer install`, configurar `.env`, `migrate --seed`, `storage:link`.

### Exportar base de datos (MySQL)

```bash
mysqldump -u root -p truequecash > backup_truequecash.sql
```

### Empaquetar para ZIP

```powershell
Compress-Archive -Path "d:\prestamos\trueque-cash" -DestinationPath "d:\prestamos\TruequeCash-Laravel.zip"
```

## Estructura de base de datos

18 tablas según Fase III del documento:

`usuario`, `cliente`, `parametro`, `cotizacion_oro`, `prestamo`, `prenda`, `foto_prenda`, `historial_estado_prenda`, `pago`, `contrato`, `recibo`, `remate`, `aviso_remate`, `solicitud_aprobacion`, `plantilla_mensaje`, `notificacion`, `cierre_caja`, `auditoria`

## Comandos útiles

```bash
php artisan migrate:fresh --seed   # Reiniciar BD con datos demo
php artisan storage:link           # Enlaces para fotos y PDFs
php artisan schedule:work          # Recordatorios automáticos (V1 simulado)
```

## Equipo

Proyecto académico Trueque Cash — Tarija, Bolivia  
Jhoel · Adriana · Josue · Jair
