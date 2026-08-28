---
title: "US-15 - Ver lista de prendas disponibles para remate"
labels: ["Remates", "Must", "Sprint 3"]
story_points: 3
sprint: "Sprint 3"
epic: "Remates"
priority_moscow: "Must"
---

# US-15 — Ver lista de prendas disponibles para remate

**Épica:** Remates · **Prioridad (MoSCoW):** Must · **Valor de negocio:** Medio · **Puntos de historia:** 3 · **Sprint:** Sprint 3

**Como** Administrador,
**quiero** ver la lista de prendas en estado "Disponible para remate" ordenadas por antigüedad y con el total adeudado,
**para** decidir con criterio el orden de venta y minimizar pérdidas.

## Criterios de aceptación

- Dado el listado de prendas disponibles para remate, cuando se consulta, entonces se ordena por antigüedad en gracia.
- Dado cada prenda listada, cuando se visualiza, entonces muestra el total adeudado y el mínimo aceptable de venta para no incurrir en pérdida.

**Requisitos relacionados:** RF-43 · CU-12

## Definición de Hecho (Done)

- [ ] Código implementado en `main`
- [ ] Pruebas (unitarias / end-to-end) pasando
- [ ] Desplegado en ambiente de pruebas
- [ ] Validado en Sprint Review
