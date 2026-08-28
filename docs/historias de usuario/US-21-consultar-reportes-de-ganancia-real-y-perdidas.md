---
title: "US-21 - Consultar reportes de ganancia real y pérdidas"
labels: ["Caja/Reportes", "Should", "Sprint 4"]
story_points: 3
sprint: "Sprint 4"
epic: "Caja/Reportes"
priority_moscow: "Should"
---

# US-21 — Consultar reportes de ganancia real y pérdidas

**Épica:** Caja/Reportes · **Prioridad (MoSCoW):** Should · **Valor de negocio:** Medio · **Puntos de historia:** 3 · **Sprint:** Sprint 4

**Como** Administrador,
**quiero** consultar reportes de ganancia neta mensual, ganancia real y pérdidas por categoría,
**para** conocer la rentabilidad real del negocio y no solo los intereses cobrados.

## Criterios de aceptación

- Dado el resumen mensual, cuando se genera, entonces coincide con la suma de movimientos del periodo (intereses cobrados − pérdidas por remates del mes).
- Dado el cálculo de "ganancia real", cuando se genera el reporte, entonces descuenta las pérdidas por prendas rematadas por debajo de lo adeudado.
- Dado que una categoría de artículo acumula un número determinado de remates a pérdida, cuando se cierra el mes, entonces el sistema genera una alerta en el dashboard.
- Dado cualquier reporte (caja por día, intereses por mes, prendas por estado, remates), cuando se filtra por rango de fechas, entonces puede exportarse a PDF y es consultable desde el celular.

**Requisitos relacionados:** RF-32, RF-33, RF-34, RF-44 · CU-14

## Definición de Hecho (Done)

- [ ] Código implementado en `main`
- [ ] Pruebas (unitarias / end-to-end) pasando
- [ ] Desplegado en ambiente de pruebas
- [ ] Validado en Sprint Review
