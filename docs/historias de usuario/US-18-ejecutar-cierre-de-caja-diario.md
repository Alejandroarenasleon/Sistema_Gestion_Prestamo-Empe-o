---
title: "US-18 - Ejecutar cierre de caja diario"
labels: ["Caja/Reportes", "Must", "Sprint 4"]
story_points: 5
sprint: "Sprint 4"
epic: "Caja/Reportes"
priority_moscow: "Must"
---

# US-18 — Ejecutar cierre de caja diario

**Épica:** Caja/Reportes · **Prioridad (MoSCoW):** Must · **Valor de negocio:** Alto · **Puntos de historia:** 5 · **Sprint:** Sprint 4

**Como** Administrador,
**quiero** que el sistema calcule automáticamente el efectivo esperado del día y me permita confirmarlo contra el conteo físico,
**para** reducir el tiempo del arqueo de minutos manuales a un clic.

## Criterios de aceptación

- Dado el fin de la jornada, cuando se solicita el cierre, entonces el sistema calcula el efectivo esperado a partir de préstamos entregados, pagos recibidos y ventas de remate del día.
- Dado el conteo físico ingresado por el Administrador, cuando se contrasta contra el efectivo esperado, entonces el sistema muestra si ambos valores coinciden.
- Dado que los valores coinciden, cuando el Administrador confirma, entonces el cierre queda guardado y disponible en reportes.
- Dado que los valores no coinciden, cuando se detecta la diferencia, entonces se activa el registro de sobrante/faltante (US-19).

**Requisitos relacionados:** RF-29, RF-30 · CU-13

## Definición de Hecho (Done)

- [ ] Código implementado en `main`
- [ ] Pruebas (unitarias / end-to-end) pasando
- [ ] Desplegado en ambiente de pruebas
- [ ] Validado en Sprint Review
