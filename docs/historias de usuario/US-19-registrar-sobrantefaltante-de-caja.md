---
title: "US-19 - Registrar sobrante/faltante de caja"
labels: ["Caja/Reportes", "Must", "Sprint 4"]
story_points: 2
sprint: "Sprint 4"
epic: "Caja/Reportes"
priority_moscow: "Must"
---

# US-19 — Registrar sobrante/faltante de caja

**Épica:** Caja/Reportes · **Prioridad (MoSCoW):** Must · **Valor de negocio:** Medio · **Puntos de historia:** 2 · **Sprint:** Sprint 4

**Como** Administrador,
**quiero** registrar el motivo de una diferencia detectada entre el efectivo esperado y el físico,
**para** dejar constancia auditable de cualquier descuadre de caja.

## Criterios de aceptación

- Dado que existe una diferencia en el arqueo, cuando el sistema la muestra, entonces el Administrador puede registrar el motivo u observación.
- Dado el sobrante/faltante registrado, cuando se guarda, entonces queda visible en el histórico de cierres de caja y en auditoría con usuario y hora.

**Requisitos relacionados:** RF-30 · CU-13, CU-18

## Definición de Hecho (Done)

- [ ] Código implementado en `main`
- [ ] Pruebas (unitarias / end-to-end) pasando
- [ ] Desplegado en ambiente de pruebas
- [ ] Validado en Sprint Review
