---
title: "US-16 - Aprobar venta de una prenda"
labels: ["Remates", "Must", "Sprint 3"]
story_points: 3
sprint: "Sprint 3"
epic: "Remates"
priority_moscow: "Must"
---

# US-16 — Aprobar venta de una prenda

**Épica:** Remates · **Prioridad (MoSCoW):** Must · **Valor de negocio:** Alto · **Puntos de historia:** 3 · **Sprint:** Sprint 3

**Como** Administrador,
**quiero** aprobar o rechazar la propuesta de venta de una prenda disponible para remate,
**para** asegurar que ninguna venta se concrete sin control del negocio.

## Criterios de aceptación

- Dado que un operador propone la venta de una prenda con un precio ofertado, cuando el Administrador revisa el total adeudado y el precio propuesto, entonces puede aprobar o rechazar la venta.
- Dado un rechazo, cuando se registra, entonces la prenda permanece "Disponible para remate" a la espera de otra oferta.
- Dado que no existe aprobación del Administrador, cuando el operador intenta guardar la venta, entonces el sistema no lo permite.

**Requisitos relacionados:** RF-27, RF-37 · CU-11

## Definición de Hecho (Done)

- [ ] Código implementado en `main`
- [ ] Pruebas (unitarias / end-to-end) pasando
- [ ] Desplegado en ambiente de pruebas
- [ ] Validado en Sprint Review
