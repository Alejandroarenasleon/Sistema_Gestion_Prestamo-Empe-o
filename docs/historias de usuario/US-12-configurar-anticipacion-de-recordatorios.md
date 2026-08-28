---
title: "US-12 - Configurar anticipación de recordatorios"
labels: ["Notificaciones", "Should", "Sprint 3"]
story_points: 2
sprint: "Sprint 3"
epic: "Notificaciones"
priority_moscow: "Should"
---

# US-12 — Configurar anticipación de recordatorios

**Épica:** Notificaciones · **Prioridad (MoSCoW):** Should · **Valor de negocio:** Bajo · **Puntos de historia:** 2 · **Sprint:** Sprint 3

**Como** Administrador,
**quiero** configurar la anticipación y frecuencia de los recordatorios de vencimiento y mora,
**para** adaptar la comunicación con los clientes a la política del negocio.

## Criterios de aceptación

- Dado el panel de configuración, cuando el Administrador define días de anticipación (3 días antes, el mismo día, a los 3 días de mora, aviso firme a los 15 días), entonces los valores se aplican a todos los préstamos activos.
- Dado un cambio en la configuración de recordatorios, cuando se guarda, entonces queda auditado con el valor anterior y el nuevo.

**Requisitos relacionados:** RF-22 · CU-16

## Definición de Hecho (Done)

- [ ] Código implementado en `main`
- [ ] Pruebas (unitarias / end-to-end) pasando
- [ ] Desplegado en ambiente de pruebas
- [ ] Validado en Sprint Review
