---
title: "US-01 - Registrar cliente con CI y fotos"
labels: ["Clientes", "Must", "Sprint 1"]
story_points: 3
sprint: "Sprint 1"
epic: "Clientes"
priority_moscow: "Must"
---

# US-01 — Registrar cliente con CI y fotos

**Épica:** Clientes · **Prioridad (MoSCoW):** Must · **Valor de negocio:** Alto · **Puntos de historia:** 3 · **Sprint:** Sprint 1

**Como** Operador/Cajero,
**quiero** registrar un cliente nuevo con su número de CI, fotos del carnet y datos de contacto,
**para** dejar constancia formal de su identidad antes de otorgarle un préstamo.

## Criterios de aceptación

- Dado un cliente nuevo, cuando el operador intenta guardar el registro sin el N° de CI, las dos fotos del CI o el celular, entonces el sistema bloquea el guardado y señala los campos faltantes.
- Dado un CI ya existente en el sistema, cuando el operador intenta registrarlo nuevamente, entonces el sistema rechaza el registro con un mensaje claro y muestra el cliente existente.
- Dado que el monto estimado del préstamo supera el umbral configurado, cuando el operador guarda el cliente, entonces el sistema exige registrar una referencia de contacto o un comprobante de domicilio antes de continuar.
- Dado un cliente guardado correctamente, cuando se abre su ficha, entonces la alerta de riesgo aparece en falso por defecto.

**Requisitos relacionados:** RF-01, RF-02 · CU-02

## Definición de Hecho (Done)

- [ ] Código implementado en `main`
- [ ] Pruebas (unitarias / end-to-end) pasando
- [ ] Desplegado en ambiente de pruebas
- [ ] Validado en Sprint Review
