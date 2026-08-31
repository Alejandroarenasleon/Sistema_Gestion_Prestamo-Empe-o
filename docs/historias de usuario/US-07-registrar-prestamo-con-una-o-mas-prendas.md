---
title: "US-07 - Registrar préstamo con una o más prendas"
labels: ["Préstamos", "Must", "Sprint 2"]
story_points: 5
sprint: "Sprint 2"
epic: "Préstamos"
priority_moscow: "Must"
---

# US-07 — Registrar préstamo con una o más prendas

**Épica:** Préstamos · **Prioridad (MoSCoW):** Must · **Valor de negocio:** Alto · **Puntos de historia:** 5 · **Sprint:** Sprint 2

**Como** Operador/Cajero,
**quiero** registrar un préstamo asociando un cliente con una o más prendas ya avaluadas,
**para** formalizar la operación de empeño con su monto, tasa e interés y fecha de vencimiento.

## Criterios de aceptación

- Dado un cliente y una o más prendas avaluadas, cuando el operador registra el préstamo, entonces puede asociar dos o más garantías a un mismo préstamo.
- Dado el monto prestado y la tasa de interés mensual ingresados, cuando se confirma el préstamo, entonces la fecha de vencimiento por defecto es la fecha de emisión más un mes.
- Dado que el cliente está en alerta de riesgo, cuando el operador intenta guardar el préstamo, entonces el flujo se extiende al proceso de aprobación del Administrador (US-03) antes de continuar.
- Dado un préstamo guardado, cuando se confirma, entonces el estado de todas las prendas asociadas cambia a "Vigente".

**Requisitos relacionados:** RF-13, RF-14, RF-39 · CU-06

## Definición de Hecho (Done)

- [ ] Código implementado en `main`
- [ ] Pruebas (unitarias / end-to-end) pasando
- [ ] Desplegado en ambiente de pruebas
- [ ] Validado en Sprint Review
