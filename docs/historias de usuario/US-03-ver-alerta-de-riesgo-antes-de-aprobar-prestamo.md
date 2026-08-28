---
title: "US-03 - Ver alerta de riesgo antes de aprobar préstamo"
labels: ["Clientes", "Must", "Sprint 1"]
story_points: 3
sprint: "Sprint 1"
epic: "Clientes"
priority_moscow: "Must"
---

# US-03 — Ver alerta de riesgo antes de aprobar préstamo

**Épica:** Clientes · **Prioridad (MoSCoW):** Must · **Valor de negocio:** Alto · **Puntos de historia:** 3 · **Sprint:** Sprint 1

**Como** Operador/Cajero,
**quiero** ver la alerta de riesgo de un cliente antes de registrar un préstamo,
**para** no aprobar operaciones a clientes riesgosos sin autorización del administrador.

**También aplica a Administrador:** quiere revisar el historial del cliente en alerta y aprobar o rechazar la solicitud, para mantener el control final sobre las decisiones financieras de riesgo.

## Criterios de aceptación

- Dado un cliente con prenda vencida sin pagar, prenda rematada en su historial o moras recurrentes, cuando se consulta su ficha, entonces el sistema muestra una alerta roja persistente.
- Dado un cliente en alerta, cuando un operador intenta registrar un préstamo, entonces el sistema bloquea el guardado y genera una solicitud en la bandeja del Administrador.
- Dado que el Administrador revisa la solicitud, cuando aprueba o rechaza, entonces la decisión queda registrada en auditoría con usuario, fecha y motivo.
- Dado un rechazo del Administrador, cuando se notifica al operador, entonces el registro del préstamo se cancela.

**Requisitos relacionados:** RF-04, RF-05, RF-37 · CU-03, CU-04

## Definición de Hecho (Done)

- [ ] Código implementado en `main`
- [ ] Pruebas (unitarias / end-to-end) pasando
- [ ] Desplegado en ambiente de pruebas
- [ ] Validado en Sprint Review
