---
title: "US-22 - Gestionar usuarios y roles"
labels: ["Usuarios/Auditoría", "Must", "Sprint 1"]
story_points: 3
sprint: "Sprint 1"
epic: "Usuarios/Auditoría"
priority_moscow: "Must"
---

# US-22 — Gestionar usuarios y roles

**Épica:** Usuarios/Auditoría · **Prioridad (MoSCoW):** Must · **Valor de negocio:** Alto · **Puntos de historia:** 3 · **Sprint:** Sprint 1

**Como** Administrador,
**quiero** gestionar cuentas de usuario con rol de Administrador u Operador,
**para** controlar quién puede acceder al sistema y qué acciones puede ejecutar.

## Criterios de aceptación

- Dado un usuario nuevo, cuando el Administrador lo crea, entonces se le asigna un rol (ADMIN u OPERADOR) y una contraseña almacenada con hash seguro (bcrypt/argon2).
- Dado un usuario con rol Operador, cuando intenta eliminar registros, modificar tasas de interés o marcar una prenda como vendida, entonces el sistema bloquea la acción en el backend y muestra "requiere aprobación del administrador".
- Dado cualquier usuario autenticado, cuando accede a una pantalla, entonces las acciones disponibles corresponden exactamente a su rol, validado en servidor (no solo en la interfaz).

**Requisitos relacionados:** RF-35, RF-36, RNF-01 · CU-01

## Definición de Hecho (Done)

- [ ] Código implementado en `main`
- [ ] Pruebas (unitarias / end-to-end) pasando
- [ ] Desplegado en ambiente de pruebas
- [ ] Validado en Sprint Review
