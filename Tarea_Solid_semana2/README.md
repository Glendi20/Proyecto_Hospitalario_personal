# Tarea SOLID — Análisis y Diseño de Software II

**Estudiante:** Glendi Patricia Campos Orellana
**GitHub:** [Glendi20](https://github.com/Glendi20)
**Módulo oficial:** Pacientes: registro, edición, búsqueda y detalle
**Principio SOLID aplicado:** LSP (Liskov Substitution Principle)
**Consigna individual:** Aplicar LSP al diseño del flujo «registro, búsqueda y actualización segura de un paciente»; definir RF/RNF y criterios de aceptación; mostrar diseño antes/después; justificar responsabilidades y dependencias; aportar evidencia verificable.

**Repositorio:** https://github.com/Glendi20/Tarea_Solid_AnalisisII
**Rama evaluada:** `main`
**Commit / etiqueta evaluada:** `[COMPLETAR: hash o tag tras hacer los commits]`

> Los datos usados en todo el proyecto son **exclusivamente ficticios**, sin información clínica identificable.

## Documento principal

- 📄 Informe completo (fuente editable): [`docs/Informe_LSP_Pacientes.md`](docs/Informe_LSP_Pacientes.md)
- 📄 Informe completo (DOCX): [`docs/Informe_LSP_Pacientes.docx`](docs/Informe_LSP_Pacientes.docx)
- 🤖 Declaración de uso de IA: [`DECLARACION_IA.md`](DECLARACION_IA.md)

## Estructura del repositorio

```text
Tarea_Solid_AnalisisII/
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

`[COMPLETAR tras hacer los commits — la IA no generó commits, por instrucción explícita de la estudiante]`

Pasos sugeridos (a ejecutar manualmente):

```bash
git add .
git commit -m "feat(antes): agregar jerarquia de repositorios que viola LSP"
# ... commits adicionales atómicos, ver docs/Informe_LSP_Pacientes.md sección 2.10
git log --oneline
git push origin main
```

Tras el push, completar en este README y en la portada del informe:
- El hash del commit evaluado.
- La salida real de `git log --oneline`.
- El enlace directo al commit en GitHub.

## Declaración de IA

Se utilizó Claude Code (Anthropic) como asistente de generación de código y documentación. El detalle completo (herramienta, propósito, prompts relevantes, partes aceptadas/modificadas y validación humana) está en [`DECLARACION_IA.md`](DECLARACION_IA.md). Ningún commit fue generado por la IA.
