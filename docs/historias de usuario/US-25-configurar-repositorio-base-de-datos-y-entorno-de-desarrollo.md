---
title: "US-25 - Configurar repositorio, base de datos y entorno de desarrollo"
labels: ["Infraestructura", "Must", "Sprint 0"]
story_points: 3
sprint: "Sprint 0"
epic: "Infraestructura"
priority_moscow: "Must"
---

# US-25 — Configurar repositorio, base de datos y entorno de desarrollo

**Épica:** Infraestructura · **Prioridad (MoSCoW):** Must · **Valor de negocio:** Alto · **Puntos de historia:** 3 · **Sprint:** Sprint 0

**Como** miembro del equipo de desarrollo,
**quiero** contar con el repositorio, la base de datos y el entorno de desarrollo configurados,
**para** poder empezar a codificar sin fricciones técnicas.

## Criterios de aceptación

- Dado que el sprint inicia, cuando se clona el repositorio, entonces existe una estructura de carpetas definida (backend / frontend / db).
- Dado el motor PostgreSQL instalado, cuando se ejecuta el script DDL, entonces se crean los tipos ENUM y las 18 tablas del modelo físico sin errores.
- Dado el script DDL aplicado, cuando se listan los índices, entonces existen los índices de apoyo sobre cliente, préstamo, prenda y auditoría.
- Dado el entorno configurado, cuando se inicia el backend, entonces la conexión a la base de datos se establece correctamente mediante variables de entorno.
- Dado el entorno de prueba, cuando se cargan los datos semilla, entonces existen un usuario administrador, un usuario operador y un cliente demo.

**Requisitos relacionados:** Infraestructura del proyecto (Sprint Backlog 8.3)

## Definición de Hecho (Done)

- [ ] Código implementado en `main`
- [ ] Pruebas (unitarias / end-to-end) pasando
- [ ] Desplegado en ambiente de pruebas
- [ ] Validado en Sprint Review
