---
title: "US-13 - Enviar recordatorios automáticos (simulado en V1)"
labels: ["Notificaciones", "Should", "Sprint 3"]
story_points: 3
sprint: "Sprint 3"
epic: "Notificaciones"
priority_moscow: "Should"
---

# US-13 — Enviar recordatorios automáticos (simulado en V1)

**Épica:** Notificaciones · **Prioridad (MoSCoW):** Should · **Valor de negocio:** Medio · **Puntos de historia:** 3 · **Sprint:** Sprint 3

**Como** cliente (beneficiario indirecto),
**quiero** recibir recordatorios de vencimiento y mora sin que el operador deba generarlos manualmente,
**para** conocer a tiempo el estado de mi préstamo y evitar la pérdida de mi prenda.

## Criterios de aceptación

- Dado un proceso programado diario, cuando se ejecuta, entonces genera automáticamente la cola de avisos regulares sin intervención del operador.
- Dado que en V1 no hay integración real con WhatsApp/SMS, cuando se "envía" un recordatorio, entonces el sistema simula el envío y lo registra (destinatario, canal, fecha, resultado), dejando documentada la interfaz del gateway real para V2.
- Dado el historial de notificaciones, cuando se consulta por cliente o préstamo, entonces se listan todos los envíos con su resultado.

**Requisitos relacionados:** RF-21, RF-23, RF-25 · Proceso programado (transversal)

## Definición de Hecho (Done)

- [ ] Código implementado en `main`
- [ ] Pruebas (unitarias / end-to-end) pasando
- [ ] Desplegado en ambiente de pruebas
- [ ] Validado en Sprint Review
