---
title: "US-20 - Ver dashboard con indicadores clave"
labels: ["Caja/Reportes", "Must", "Sprint 4"]
story_points: 5
sprint: "Sprint 4"
epic: "Caja/Reportes"
priority_moscow: "Must"
---

# US-20 — Ver dashboard con indicadores clave

**Épica:** Caja/Reportes · **Prioridad (MoSCoW):** Must · **Valor de negocio:** Alto · **Puntos de historia:** 5 · **Sprint:** Sprint 4

**Como** Administrador u Operador,
**quiero** ver al iniciar sesión un dashboard con los indicadores clave del negocio,
**para** tener visibilidad inmediata de la situación diaria sin navegar por múltiples pantallas.

## Criterios de aceptación

- Dado el inicio de sesión, cuando se carga el dashboard, entonces muestra sin necesidad de navegar: movimiento de caja del día, prendas por vencer en la semana, prendas en mora y prendas disponibles para remate.
- Dado cada indicador visible, cuando se hace clic sobre él, entonces enlaza a su detalle correspondiente.
- Dado el dashboard, cuando se mide su tiempo de carga, entonces es menor a 2 segundos.
- Dado el acceso desde el celular del Administrador, cuando se abre el dashboard, entonces el diseño es responsive.

**Requisitos relacionados:** RF-31, RNF-06, RNF-08, RNF-09 · CU-14

## Definición de Hecho (Done)

- [ ] Código implementado en `main`
- [ ] Pruebas (unitarias / end-to-end) pasando
- [ ] Desplegado en ambiente de pruebas
- [ ] Validado en Sprint Review
