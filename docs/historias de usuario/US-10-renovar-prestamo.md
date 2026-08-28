---
title: "US-10 - Renovar préstamo"
labels: ["Préstamos", "Must", "Sprint 2"]
story_points: 3
sprint: "Sprint 2"
epic: "Préstamos"
priority_moscow: "Must"
---

# US-10 — Renovar préstamo

**Épica:** Préstamos · **Prioridad (MoSCoW):** Must · **Valor de negocio:** Medio · **Puntos de historia:** 3 · **Sprint:** Sprint 2

**Como** Operador/Cajero,
**quiero** renovar o prorrogar un préstamo vigente o en mora mediante el pago del interés vencido,
**para** extender el vencimiento un mes sin alterar el capital del cliente.

## Criterios de aceptación

- Dado un préstamo vigente o en mora, cuando el operador selecciona "Renovar", entonces el sistema calcula el interés vencido a pagar.
- Dado que el cliente no cubre el interés completo, cuando intenta renovar, entonces el sistema no habilita la renovación hasta completar el monto.
- Dado el pago del interés vencido confirmado, cuando se procesa la renovación, entonces la fecha de vencimiento se extiende un mes sin modificar el capital.
- Dado el resultado de la renovación, cuando se completa, entonces el estado de la prenda pasa a "Renovada" y luego a "Vigente", y se genera el recibo correspondiente.

**Requisitos relacionados:** RF-17 · CU-09 (incluye CU-10)

## Definición de Hecho (Done)

- [ ] Código implementado en `main`
- [ ] Pruebas (unitarias / end-to-end) pasando
- [ ] Desplegado en ambiente de pruebas
- [ ] Validado en Sprint Review
