# Planificación de Sprints — Trueque Cash

**Proyecto:** Sistema de Gestión de Préstamos y Empeño  
**Stack:** Laravel 12 · PHP 8.2+ · MySQL · Bootstrap 5  
**Fecha de inicio:** 26/08/2026  
**Fecha límite:** 31/08/2026  
**Total historias:** 26 US · 86 puntos · 31 commits

---

## Tabla de Sprints

| Fase | Commits | Descripción | Estado |
|---|---|---|---|
| Fases I-III | 1 | SRS, RF/RNF, casos de uso, diagramas, modelo de BD | ✅ Cumplido |
| Sprint 0 | 1 | Repositorio, base de datos y entorno de desarrollo | ✅ Cumplido |
| Sprint 1 | 3 | Login, gestión de clientes y prendas operativos | ✅ Completo |
| Sprint 2 | 5 | Ciclo completo de préstamo: registro, contrato, cobro, renovación, recibo | ✅ Completo (en esta carpeta) |
| Sprint 3 | 7 | Notificaciones (simuladas) y remates operativos con aprobación | ❌ Pendiente |
| Sprint 4 | 9 | Cierre de caja, dashboard, reportes y auditoría operativos | ❌ Pendiente |
| Sprint 5 | 10 | Fases IV-VI documentadas, pruebas end-to-end y despliegue | ❌ Pendiente |
| Entrega final | 11 | Demo del sistema completo ante el docente y el dueño del negocio | ❌ Pendiente |

---

## Alcance de esta carpeta (hasta Sprint 2)

Esta carpeta contiene el proyecto **funcional hasta Sprint 2**, incluyendo:

### Sprint 1 — Login, clientes y prendas

| US | Título | Puntos | Estado |
|---|---|---|---|
| US-01 | Registrar cliente con CI y fotos | 3 | ✅ |
| US-02 | Buscar cliente por CI, nombre o celular | 2 | ✅ |
| US-03 | Ver alerta de riesgo antes de aprobar préstamo | 3 | ✅ |
| US-04 | Registrar prenda con foto y avalúo | 5 | ✅ |
| US-05 | Configurar cotización del oro | 2 | ✅ |
| US-22 | Gestionar usuarios y roles | 3 | ✅ |

### Sprint 2 — Ciclo completo de préstamo

| US | Título | Puntos | Estado |
|---|---|---|---|
| US-06 | Actualizar automáticamente el estado de la prenda | 5 | ✅ (comando + servicio) |
| US-07 | Registrar préstamo con una o más prendas | 5 | ✅ |
| US-08 | Generar contrato de empeño en PDF/impreso | 3 | ✅ |
| US-09 | Cobrar interés, abono o cancelación total | 5 | ✅ |
| US-10 | Renovar préstamo | 3 | ✅ |
| US-11 | Emitir recibo por cada cobro | 3 | ✅ |
| US-23 | Ver bandeja de aprobaciones pendientes | 3 | ✅ |

---

## Estructura del Proyecto

```
proyecto-sprint2/
├── app/
│   ├── Console/Commands/      → ActualizarEstadosMora.php (US-06)
│   ├── Http/Controllers/     → Todos los controladores
│   ├── Http/Middleware/      → EnsureAdmin.php
│   ├── Models/               → 18 modelos (usuario, cliente, prestamo, etc.)
│   ├── Providers/
│   └── Services/             → Cliente, Prestamo, Prenda, Pdf, Auditoria, Caja
├── bootstrap/
├── config/                   → database.php, filesystems.php
├── database/
│   ├── migrations/           → 2026_08_24_...trueque_cash_schema.php (18 tablas)
│   └── seeders/              → DatabaseSeeder.php (admin, operador, parámetros, etc.)
├── docs/                     → 26 historias de usuario
├── public/
├── resources/
│   └── views/                → layouts, clientes, prestamos, pagos, etc.
├── routes/                   → web.php
├── storage/                  → (estructura Laravel)
├── tests/
├── SCRUM/                    → Documentación SCRUM
├── .env.example
├── .gitignore
├── artisan
├── composer.json
├── package.json
├── phpunit.xml
├── README.md
└── vite.config.js
```

---

## Comandos para ejecutar

```bash
# 1. Instalar dependencias
composer install
npm install

# 2. Configurar entorno
copy .env.example .env
php artisan key:generate

# 3. Configurar base de datos (MySQL)
# Editar .env: DB_CONNECTION=mysql, DB_DATABASE=trueque_cash, etc.
# Crear base de datos en MySQL

# 4. Migrar y sembrar
php artisan migrate --seed

# 5. Enlazar almacenamiento de fotos
php artisan storage:link

# 6. Compilar assets (opcional)
npm run build

# 7. Ejecutar servidor
php artisan serve
```

**Credenciales de prueba (seeder):**
- Admin: `admin` / `admin123`
- Operador: `operador` / `operador123`

---

## Cronograma de Commits (26/08 — 31/08)

| Día | Sprint | Commits | Puntos |
|---|---|---|---|
| 26/08 | Sprint 1 | 3 | 10 |
| 27/08 | Sprint 2 (ini) | 4 | 16 |
| 28/08 | Sprint 2 (fin) | 3 | 11 |
| 29/08 | Sprint 3 | 7 | 22 |
| 30/08 | Sprint 4 | 4 | 13 |
| 31/08 | Sprint 5 + Final | 10 | — |
| **Total** | | **31** | **86+** |

---

## Dependencias entre Sprints

```
Sprint 0 (Repo + BD)
    └──→ Sprint 1 (Clientes + Prendas)
              └──→ Sprint 2 (Préstamos + Contrato + Cobros)
                        └──→ Sprint 3 (Notificaciones + Remates)
                                  └──→ Sprint 4 (Caja + Dashboard + Reportes)
                                            └──→ Sprint 5 (Docs + Pruebas + Despliegue)
                                                      └──→ Entrega Final (Demo)
```
