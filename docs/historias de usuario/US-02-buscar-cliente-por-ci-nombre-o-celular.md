---
title: "US-02 - Buscar cliente por CI, nombre o celular"
labels: ["Clientes", "Must", "Sprint 1"]
story_points: 2
sprint: "Sprint 1"
epic: "Clientes"
priority_moscow: "Must"
---

# US-02 — Buscar cliente por CI, nombre o celular

**Épica:** Clientes · **Prioridad (MoSCoW):** Must · **Valor de negocio:** Medio · **Puntos de historia:** 2 · **Sprint:** Sprint 1

**Como** Operador/Cajero o Administrador,
**quiero** buscar clientes por CI, nombre o celular con resultados instantáneos,
**para** atender al cliente en mostrador sin demoras.

## Criterios de aceptación

- Dado que escribo parte del CI, nombre o celular, cuando tecleo en el buscador, entonces el sistema muestra coincidencias parciales en menos de 1 segundo (red local).
- Dado un cliente en alerta roja, cuando aparece en los resultados de búsqueda, entonces se resalta visualmente el motivo de la alerta.

**Requisitos relacionados:** RF-38 · CU-02, CU-03

## Definición de Hecho (Done)

- [ ] Código implementado en `main`
- [ ] Pruebas (unitarias / end-to-end) pasando
- [ ] Desplegado en ambiente de pruebas
- [ ] Validado en Sprint Review
