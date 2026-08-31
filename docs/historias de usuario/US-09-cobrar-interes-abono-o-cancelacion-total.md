---
title: "US-09 - Cobrar interés, abono o cancelación total"
labels: ["Préstamos", "Must", "Sprint 2"]
story_points: 5
sprint: "Sprint 2"
epic: "Préstamos"
priority_moscow: "Must"
---

# US-09 — Cobrar interés, abono o cancelación total

**Épica:** Préstamos · **Prioridad (MoSCoW):** Must · **Valor de negocio:** Alto · **Puntos de historia:** 5 · **Sprint:** Sprint 2

**Como** Operador/Cajero,
**quiero** registrar un cobro de interés, un abono a capital o una cancelación total sobre un préstamo vigente,
**para** actualizar el saldo del cliente de forma correcta y automática, sin cálculos manuales.

## Criterios de aceptación

- Dado un pago de tipo interés, cuando se registra, entonces el capital del préstamo no se modifica y el interés del periodo pagado queda saldado.
- Dado un abono a capital, cuando se registra, entonces el interés del siguiente periodo se recalcula automáticamente como (capital − suma de abonos) × tasa / 100.
- Dado un saldo pendiente, cuando se consulta, entonces siempre es igual a capital + intereses acumulados no pagados − abonos, sin ningún cálculo manual.
- Dado que el monto recibido es insuficiente para cubrir el interés del periodo, cuando se registra el pago, entonces el sistema advierte y documenta el saldo pendiente.
- Dado el pago de cancelación total (capital + intereses pendientes), cuando se confirma, entonces la prenda pasa a estado "Devuelta" y el préstamo se cierra sin más cobros.
- Dado cualquier tipo de pago registrado, cuando se completa, entonces se genera automáticamente el recibo correspondiente.

**Requisitos relacionados:** RF-15, RF-16, RF-40, RF-41 · CU-08 (incluye CU-10)

## Definición de Hecho (Done)

- [ ] Código implementado en `main`
- [ ] Pruebas (unitarias / end-to-end) pasando
- [ ] Desplegado en ambiente de pruebas
- [ ] Validado en Sprint Review
