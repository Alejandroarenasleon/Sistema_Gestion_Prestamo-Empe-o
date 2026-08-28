---
title: "US-04 - Registrar prenda con foto y avalúo"
labels: ["Prendas", "Must", "Sprint 1"]
story_points: 5
sprint: "Sprint 1"
epic: "Prendas"
priority_moscow: "Must"
---

# US-04 — Registrar prenda con foto y avalúo

**Épica:** Prendas · **Prioridad (MoSCoW):** Must · **Valor de negocio:** Alto · **Puntos de historia:** 5 · **Sprint:** Sprint 1

**Como** Operador/Cajero,
**quiero** registrar una prenda con su categoría, descripción, estado físico, fotografía y avalúo,
**para** dejar respaldo documental completo de la garantía y calcular el monto máximo a prestar.

## Criterios de aceptación

- Dado el registro de una prenda, cuando el operador completa categoría, descripción, marca, modelo y material, entonces el sistema exige además peso (si es joya) o N° de serie/IMEI (si es electrónico).
- Dado que no hay fotografía disponible, cuando se intenta guardar la prenda, entonces el sistema exige como mínimo observaciones de texto del estado físico.
- Dado que se captura una fotografía, cuando se guarda, entonces queda almacenada con fecha y hora, visible en la ficha y en el respaldo documental.
- Dado el avalúo y la categoría de la prenda, cuando el operador confirma los datos, entonces el sistema calcula el monto máximo a prestar aplicando el porcentaje configurado por categoría.
- Dado que el operador intenta prestar por encima del máximo calculado, cuando confirma el monto, entonces el sistema alerta y exige autorización del Administrador.
- Dado el registro exitoso, cuando finaliza el flujo, entonces la prenda queda en estado "Recibida", lista para asociarse a un préstamo.

**Requisitos relacionados:** RF-06, RF-07, RF-08, RF-10 · CU-05

## Definición de Hecho (Done)

- [ ] Código implementado en `main`
- [ ] Pruebas (unitarias / end-to-end) pasando
- [ ] Desplegado en ambiente de pruebas
- [ ] Validado en Sprint Review
