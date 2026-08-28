---
title: "US-06 - Actualizar automáticamente el estado de la prenda"
labels: ["Prendas", "Must", "Sprint 2"]
story_points: 5
sprint: "Sprint 2"
epic: "Prendas"
priority_moscow: "Must"
---

# US-06 — Actualizar automáticamente el estado de la prenda

**Épica:** Prendas · **Prioridad (MoSCoW):** Must · **Valor de negocio:** Alto · **Puntos de historia:** 5 · **Sprint:** Sprint 2

**Como** sistema (proceso transversal),
**quiero** gestionar y registrar automáticamente el ciclo de vida de cada prenda,
**para** que todos los módulos (préstamos, cobros, remates) reflejen siempre el estado real de la garantía.

## Criterios de aceptación

- Dado un evento válido (préstamo registrado, pago, renovación, vencimiento, venta, devolución), cuando ocurre, entonces el estado de la prenda transiciona únicamente entre: Recibida, Vigente, En mora/Vencida, En gracia, Disponible para remate, Vendida, Renovada, Devuelta.
- Dado un cambio de estado, cuando se ejecuta, entonces queda registrado en el historial con fecha, usuario responsable, estado anterior y estado nuevo.
- Dado el historial de una prenda, cuando se consulta la línea de tiempo, entonces no permite edición retroactiva de ningún registro.
- Dado el vencimiento del préstamo más el periodo de gracia configurable, cuando se cumple el plazo sin pago, entonces el estado cambia automáticamente a "Disponible para remate" con su fecha visible.

**Requisitos relacionados:** RF-11, RF-12, RF-26 · Transversal (CU-05, CU-06, CU-08, CU-09, CU-12)

## Definición de Hecho (Done)

- [ ] Código implementado en `main`
- [ ] Pruebas (unitarias / end-to-end) pasando
- [ ] Desplegado en ambiente de pruebas
- [ ] Validado en Sprint Review
