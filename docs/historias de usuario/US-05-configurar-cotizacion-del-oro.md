---
title: "US-05 - Configurar cotización del oro"
labels: ["Prendas", "Should", "Sprint 1"]
story_points: 2
sprint: "Sprint 1"
epic: "Prendas"
priority_moscow: "Should"
---

# US-05 — Configurar cotización del oro

**Épica:** Prendas · **Prioridad (MoSCoW):** Should · **Valor de negocio:** Medio · **Puntos de historia:** 2 · **Sprint:** Sprint 1

**Como** Administrador,
**quiero** mantener actualizada la tabla de cotización del oro por quilate,
**para** que el sistema calcule avalúos sugeridos consistentes con el precio de mercado.

## Criterios de aceptación

- Dado que el Administrador actualiza el precio por gramo de un quilate, cuando guarda el cambio, entonces el sistema registra fecha, usuario y el valor anterior en el historial.
- Dado un objeto de oro en registro de avalúo, cuando se calcula el avalúo sugerido, entonces el resultado es igual al peso multiplicado por la cotización vigente.

**Requisitos relacionados:** RF-09 · CU-05, CU-16

## Definición de Hecho (Done)

- [ ] Código implementado en `main`
- [ ] Pruebas (unitarias / end-to-end) pasando
- [ ] Desplegado en ambiente de pruebas
- [ ] Validado en Sprint Review
