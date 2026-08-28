---
title: "US-08 - Generar contrato de empeño en PDF/impreso"
labels: ["Préstamos", "Must", "Sprint 2"]
story_points: 3
sprint: "Sprint 2"
epic: "Préstamos"
priority_moscow: "Must"
---

# US-08 — Generar contrato de empeño en PDF/impreso

**Épica:** Préstamos · **Prioridad (MoSCoW):** Must · **Valor de negocio:** Alto · **Puntos de historia:** 3 · **Sprint:** Sprint 2

**Como** Operador/Cajero,
**quiero** generar e imprimir el contrato de empeño al formalizar un préstamo,
**para** entregar al cliente un respaldo legal con los datos, prendas, condiciones y política de gracia.

## Criterios de aceptación

- Dado un préstamo registrado, cuando se genera el contrato, entonces incluye datos del cliente, prenda(s) con foto, capital, interés, plazo y política de gracia.
- Dado el contrato generado, cuando se solicita imprimir, entonces puede emitirse en impresora térmica y/o como PDF.
- Dado que la impresora térmica falla, cuando ocurre el error, entonces el sistema lo muestra y ofrece reimprimir o emitir solo el PDF.

**Requisitos relacionados:** RF-18 · CU-07 (incluido por CU-06)

## Definición de Hecho (Done)

- [ ] Código implementado en `main`
- [ ] Pruebas (unitarias / end-to-end) pasando
- [ ] Desplegado en ambiente de pruebas
- [ ] Validado en Sprint Review
