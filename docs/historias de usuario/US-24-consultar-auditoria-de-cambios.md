---
title: "US-24 - Consultar auditoría de cambios"
labels: ["Usuarios/Auditoría", "Must", "Sprint 4"]
story_points: 3
sprint: "Sprint 4"
epic: "Usuarios/Auditoría"
priority_moscow: "Must"
---

# US-24 — Consultar auditoría de cambios

**Épica:** Usuarios/Auditoría · **Prioridad (MoSCoW):** Must · **Valor de negocio:** Medio · **Puntos de historia:** 3 · **Sprint:** Sprint 4

**Como** Administrador,
**quiero** consultar el registro de auditoría filtrando por usuario, fecha o entidad,
**para** verificar quién hizo qué cambio y detectar posibles fraudes o errores internos.

## Criterios de aceptación

- Dado el módulo de auditoría, cuando se filtra por usuario, fecha o entidad, entonces el sistema muestra los registros que cumplen el filtro con el valor "antes → después" de cada cambio.
- Dado cualquier rol, incluido el Administrador, cuando intenta editar o eliminar un registro de auditoría, entonces el sistema lo impide (append-only).
- Dado toda creación, modificación o eliminación de préstamos, cobros, abonos, cambios de estado o parámetros, cuando ocurre, entonces genera automáticamente un registro de auditoría.

**Requisitos relacionados:** RF-46, RNF-02, RNF-03 · CU-16, CU-17

## Definición de Hecho (Done)

- [ ] Código implementado en `main`
- [ ] Pruebas (unitarias / end-to-end) pasando
- [ ] Desplegado en ambiente de pruebas
- [ ] Validado en Sprint Review
