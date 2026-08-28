---
title: "US-14 - Aprobar aviso de remate antes del envío"
labels: ["Notificaciones", "Must", "Sprint 3"]
story_points: 3
sprint: "Sprint 3"
epic: "Notificaciones"
priority_moscow: "Must"
---

# US-14 — Aprobar aviso de remate antes del envío

**Épica:** Notificaciones · **Prioridad (MoSCoW):** Must · **Valor de negocio:** Medio · **Puntos de historia:** 3 · **Sprint:** Sprint 3

**Como** Administrador,
**quiero** aprobar manualmente cada aviso de remate antes de que se envíe al cliente,
**para** evitar notificaciones incorrectas o prematuras que afecten la relación con el cliente.

## Criterios de aceptación

- Dado que una prenda está próxima a cumplir el periodo de gracia o ya venció, cuando el sistema genera el aviso, entonces este aparece pendiente en la bandeja del Administrador y no se envía automáticamente.
- Dado que el Administrador revisa el aviso, cuando lo aprueba, entonces se pone en cola de envío del módulo de notificaciones.
- Dado que el Administrador rechaza o posterga el aviso, cuando registra la decisión, entonces el aviso queda pendiente y no se envía.

**Requisitos relacionados:** RF-24 · CU-15

## Definición de Hecho (Done)

- [ ] Código implementado en `main`
- [ ] Pruebas (unitarias / end-to-end) pasando
- [ ] Desplegado en ambiente de pruebas
- [ ] Validado en Sprint Review
