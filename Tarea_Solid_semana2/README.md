# Tarea SOLID — Análisis y Diseño de Software II

**Estudiante:** Glendi Patricia Campos Orellana
**GitHub:** [Glendi20](https://github.com/Glendi20)
**Módulo oficial:** Pacientes: registro, edición, búsqueda y detalle
**Principio SOLID aplicado:** LSP (Liskov Substitution Principle)
**Consigna individual:** Aplicar LSP al diseño del flujo «registro, búsqueda y actualización segura de un paciente»; definir RF/RNF y criterios de aceptación; mostrar diseño antes/después; justificar responsabilidades y dependencias; aportar evidencia verificable.

**Repositorio:** https://github.com/Glendi20/Proyecto_Hospitalario_personal (esta carpeta: `Tarea_Solid_semana2/`)
**Rama evaluada:** `main`
**Commit / etiqueta evaluada:** `d1d3c28`

> Los datos usados en todo el proyecto son **exclusivamente ficticios**, sin información clínica identificable.

## Documento principal

- 📄 Informe completo: [`docs/Informe_LSP_Pacientes.md`](docs/Informe_LSP_Pacientes.md)
- 🤖 Declaración de uso de IA: [`DECLARACION_IA.md`](DECLARACION_IA.md)

## Estructura de esta carpeta

```text
Tarea_Solid_semana2/
├── README.md                      <- este archivo
├── DECLARACION_IA.md
├── database/
│   └── schema.sql                 <- esquema de referencia (documentación)
├── docs/
│   ├── Informe_LSP_Pacientes.md   <- documento fuente (editable)
│   ├── Informe_LSP_Pacientes.docx <- documento final
│   ├── diagrams/                  <- diagramas Mermaid (.mmd, editables) + .png renderizados
│   └── evidencia/                 <- salidas de ejecución capturadas (evidencia)
├── src/
│   ├── antes/                     <- diseño que VIOLA LSP (jerarquía por herencia)
│   └── despues/                   <- diseño que APLICA LSP (interfaces + composición)
└── tests/                         <- batería de pruebas de contrato (verifica LSP)
```

## Cómo ejecutar la evidencia localmente

Requisitos: PHP 8.2+ con extensión `pdo_sqlite` (incluida por defecto en la mayoría de instalaciones).

```bash
# 1) Evidencia empírica de la violación LSP (diseño ANTES)
php src/antes/demo_antes.php

# 2) Evidencia empírica de la sustituibilidad correcta (diseño DESPUÉS)
php src/despues/demo_despues.php

# 3) Batería de pruebas de contrato (ANTES debe fallar, DESPUÉS debe pasar)
php tests/run_tests.php
```

Las salidas ya quedaron capturadas como evidencia en [`docs/evidencia/`](docs/evidencia/).

## Evidencia Git

Esta carpeta se agregó al repositorio en un único commit:

```
d1d3c28 subiendo archivos
```

Enlace directo: https://github.com/Glendi20/Proyecto_Hospitalario_personal/commit/d1d3c28

Ver también el historial completo del repositorio (semana 1 + semana 2) en
[`../docs/02-evidencia-git.md`](../docs/02-evidencia-git.md).

## Declaración de IA

Se utilizó Claude Code (Anthropic) como asistente de generación de código y documentación. El detalle completo (herramienta, propósito, prompts relevantes, partes aceptadas/modificadas y validación humana) está en [`DECLARACION_IA.md`](DECLARACION_IA.md). Ningún commit fue generado por la IA.
