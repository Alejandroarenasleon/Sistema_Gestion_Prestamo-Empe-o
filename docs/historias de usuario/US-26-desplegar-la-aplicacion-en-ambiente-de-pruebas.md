---
title: "US-26 - Desplegar la aplicación en ambiente de pruebas"
labels: ["Infraestructura", "Must", "Sprint 5"]
story_points: 3
sprint: "Sprint 5"
epic: "Infraestructura"
priority_moscow: "Must"
---

# US-26 — Desplegar la aplicación en ambiente de pruebas

**Épica:** Infraestructura · **Prioridad (MoSCoW):** Must · **Valor de negocio:** Alto · **Puntos de historia:** 3 · **Sprint:** Sprint 5

**Como** Product Owner,
**quiero** que la aplicación esté desplegada en un ambiente de pruebas,
**para** poder demostrarla ante el docente y el dueño del negocio.

## Criterios de aceptación

- Dado el sistema completo, cuando se accede a la URL del ambiente de pruebas, entonces todos los módulos (clientes, prendas, préstamos, remates, caja, reportes) responden correctamente.
- Dado el flujo completo de negocio, cuando se ejecuta una prueba end-to-end (registro → cobro → remate → caja), entonces el resultado es consistente en cada paso.
- Dado el despliegue, cuando ocurre un corte de conexión, entonces el sistema no pierde ni duplica transacciones (RNF-05).

## Definición de Hecho (Done)

- [ ] Código implementado en `main`
- [ ] Pruebas (unitarias / end-to-end) pasando
- [ ] Desplegado en ambiente de pruebas
- [ ] Validado en Sprint Review
