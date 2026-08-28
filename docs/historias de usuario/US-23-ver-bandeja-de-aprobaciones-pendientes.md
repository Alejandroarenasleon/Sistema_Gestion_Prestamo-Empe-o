---
title: "US-23 - Ver bandeja de aprobaciones pendientes"
labels: ["Usuarios/Auditoría", "Must", "Sprint 2"]
story_points: 3
sprint: "Sprint 2"
epic: "Usuarios/Auditoría"
priority_moscow: "Must"
---

# US-23 — Ver bandeja de aprobaciones pendientes

**Épica:** Usuarios/Auditoría · **Prioridad (MoSCoW):** Must · **Valor de negocio:** Alto · **Puntos de historia:** 3 · **Sprint:** Sprint 2

**Como** Administrador,
**quiero** ver en una bandeja centralizada todas las solicitudes de aprobación pendientes (préstamos en riesgo, ventas de remate, avisos de remate),
**para** decidir con contexto sin buscarlas manualmente en cada módulo.

## Criterios de aceptación

- Dado que un operador genera una solicitud restringida, cuando se guarda, entonces aparece en la bandeja del Administrador con el contexto necesario (tipo, referencia, motivo, usuario solicitante).
- Dado que el Administrador aprueba o rechaza una solicitud, cuando confirma la decisión, entonces esta queda registrada en auditoría con usuario, fecha y motivo.

**Requisitos relacionados:** RF-05, RF-27, RF-37 · CU-04, CU-11, CU-15

## Definición de Hecho (Done)

- [ ] Código implementado en `main`
- [ ] Pruebas (unitarias / end-to-end) pasando
- [ ] Desplegado en ambiente de pruebas
- [ ] Validado en Sprint Review
