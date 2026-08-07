# Proyecto Hospitalario — Modelado UML del módulo Pacientes

**Estudiante:** Glendi Patricia Campos Orellana
**GitHub:** [`Glendi20`](https://github.com/Glendi20)
**Módulo asignado:** Pacientes: registro, edición, búsqueda y detalle
**Proceso modelado:** Registro, búsqueda y actualización segura de un paciente

**Repositorio:** `https://github.com/Glendi20/Proyecto_Hospitalario_personal`
**Rama evaluada:** `main`
**Commit / etiqueta evaluada:** _<completar con el hash corto del último commit, ver sección
"Evidencia Git" abajo>_

## Descripción

Este repositorio contiene el análisis y modelado UML (casos de uso, actividad y secuencia) del
proceso «registro, búsqueda y actualización segura de un paciente», entregado como actividad
individual del módulo de Pacientes. Todos los datos son ficticios; no contiene información clínica
real ni datos personales identificables.

## Estructura del repositorio

```
.
├── README.md                          Este archivo (portada técnica del repo)
├── DECLARACION_IA.md                  Declaración transparente de uso de IA
├── diagramas/
│   ├── fuente/                        Fuentes UML editables (PlantUML, texto plano)
│   │   ├── 01-casos-de-uso-pacientes.puml
│   │   ├── 02-actividad-pacientes.puml
│   │   └── 03-secuencia-pacientes.puml
│   └── render/                        Diagramas exportados a PNG (para el documento)
│       ├── 01-casos-de-uso-pacientes.png
│       ├── 02-actividad-pacientes.png
│       └── 03-secuencia-pacientes.png
└── docs/
    ├── 01-matriz-trazabilidad.md      Matriz requisito → diagrama → elemento
    └── 02-evidencia-git.md            Historial de commits y enlace al commit evaluado
```

## Cómo re-renderizar los diagramas

Los diagramas fuente están en formato [PlantUML](https://plantuml.com/) (texto plano, editable con
cualquier editor). Para regenerar las imágenes PNG tras editar un `.puml`:

1. **Opción rápida (VS Code):** instalar la extensión "PlantUML" y usar *Alt+D* para previsualizar, o
   clic derecho → "Export Current Diagram".
2. **Opción en línea:** pegar el contenido del `.puml` en https://www.plantuml.com/plantuml.
3. **Opción por línea de comandos** (requiere Java):
   ```
   java -jar plantuml.jar -tpng -o ../../diagramas/render diagramas/fuente/<archivo>.puml
   ```
   (`plantuml.jar` se descarga desde https://plantuml.com/download)

## Evidencia Git

Ver [`docs/02-evidencia-git.md`](docs/02-evidencia-git.md) para el `git log --oneline` y el enlace al
commit evaluado.

## Declaración de IA

Ver [`DECLARACION_IA.md`](DECLARACION_IA.md).
