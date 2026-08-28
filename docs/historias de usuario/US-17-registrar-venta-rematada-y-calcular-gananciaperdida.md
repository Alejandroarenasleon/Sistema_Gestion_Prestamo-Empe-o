---
title: "US-17 - Registrar venta rematada y calcular ganancia/pérdida"
labels: ["Remates", "Must", "Sprint 3"]
story_points: 3
sprint: "Sprint 3"
epic: "Remates"
priority_moscow: "Must"
---

# US-17 — Registrar venta rematada y calcular ganancia/pérdida

**Épica:** Remates · **Prioridad (MoSCoW):** Must · **Valor de negocio:** Alto · **Puntos de historia:** 3 · **Sprint:** Sprint 3

**Como** Administrador,
**quiero** registrar el precio final de venta de una prenda rematada y ver automáticamente la ganancia o pérdida generada,
**para** conocer el resultado real de cada remate y no solo el ingreso bruto.

## Criterios de aceptación

- Dado una venta aprobada (US-16), cuando el Administrador registra precio final, fecha y comprador, entonces el sistema calcula el resultado = precio de venta − (capital + interés no pagado).
- Dado el registro de la venta, cuando se confirma, entonces el estado de la prenda cambia a "Vendida" y el préstamo asociado se cierra.
- Dado que el precio de venta es menor al adeudado, cuando se calcula el resultado, entonces la pérdida se registra y se asocia a la categoría del artículo para el seguimiento de pérdidas recurrentes.
- Dado el resultado calculado, cuando se guarda, entonces es visible en la ficha del cliente, en los reportes y en el dashboard.

**Requisitos relacionados:** RF-27, RF-28, RF-43 · CU-12 (incluye CU-11)

## Definición de Hecho (Done)

- [ ] Código implementado en `main`
- [ ] Pruebas (unitarias / end-to-end) pasando
- [ ] Desplegado en ambiente de pruebas
- [ ] Validado en Sprint Review
