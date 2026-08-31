---
title: "US-11 - Emitir recibo por cada cobro"
labels: ["Préstamos", "Must", "Sprint 2"]
story_points: 3
sprint: "Sprint 2"
epic: "Préstamos"
priority_moscow: "Must"
---

# US-11 — Emitir recibo por cada cobro

**Épica:** Préstamos · **Prioridad (MoSCoW):** Must · **Valor de negocio:** Medio · **Puntos de historia:** 3 · **Sprint:** Sprint 2

**Como** Operador/Cajero,
**quiero** generar un recibo (térmico y/o PDF) por cada pago de interés, abono o cancelación,
**para** entregar comprobante inmediato al cliente y respaldar el historial del préstamo.

## Criterios de aceptación

- Dado un pago registrado, cuando se genera el recibo, entonces incluye fecha, monto, concepto y saldo pendiente, y se produce en menos de 1 segundo.
- Dado el recibo generado, cuando se necesita reimprimir, entonces el sistema lo permite sin alterar el original.
- Dado que se opta por enviar el recibo por WhatsApp, cuando se ejecuta el envío (simulado en V1), entonces el intento queda registrado con destinatario, canal, fecha y resultado.

**Requisitos relacionados:** RF-19, RF-20 · CU-10

## Definición de Hecho (Done)

- [ ] Código implementado en `main`
- [ ] Pruebas (unitarias / end-to-end) pasando
- [ ] Desplegado en ambiente de pruebas
- [ ] Validado en Sprint Review
