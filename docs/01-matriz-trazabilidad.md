# Matriz de trazabilidad — Requisito → Diagrama → Elemento

**Módulo:** Pacientes: registro, edición, búsqueda y detalle
**Proceso:** Registro, búsqueda y actualización segura de un paciente

La matriz demuestra que cada requisito funcional (RF) identificado para el proceso
está representado de forma consistente en los tres diagramas UML entregados.

| ID | Requisito funcional | Caso de uso (elemento) | Actividad (elemento) | Secuencia (elemento) |
|----|----------------------|-------------------------|------------------------|------------------------|
| RF-01 | El sistema debe permitir registrar un paciente con datos obligatorios completos y validados. | UC1 Registrar paciente | Nodo de decisión "¿Campos obligatorios completos y con formato válido?" (rama Registrar) | Sección `== Registrar paciente ==`, mensaje `validarCamposObligatorios(datosPaciente)` y `alt datos inválidos / datos válidos` |
| RF-02 | El sistema debe impedir el registro de pacientes duplicados (mismo número de identidad). | UC5 Validar duplicidad de paciente (`<<include>>` de UC1) | Nodo de decisión "¿Ya existe un paciente con ese número de identidad?" | Mensaje `existeDuplicado(numeroIdentidad)` y `alt paciente duplicado / sin duplicado` |
| RF-03 | El sistema debe permitir buscar pacientes por nombre, número de identidad o número de expediente. | UC2 Buscar paciente | Actividad "Ingresar criterio de búsqueda" y "Ejecutar consulta en base de datos" (rama Buscar) | Sección `== Buscar paciente ==`, mensaje `buscarPaciente(criterio)` → `buscar(criterio)` |
| RF-04 | El sistema debe informar cuando una búsqueda no arroja resultados. | UC2 Buscar paciente / UC4 Ver detalle de paciente (`<<extend>>`) | Nodo de decisión "¿Se encontraron resultados?" | `alt resultados encontrados / sin resultados`, mensaje "No se encontraron pacientes" |
| RF-05 | El sistema debe permitir editar/actualizar los datos de un paciente existente. | UC3 Actualizar paciente | Rama "Actualizar": "Cargar datos actuales", "Modificar campos requeridos", "Guardar cambios" | Sección `== Actualizar paciente ==`, mensaje `actualizarPaciente(idPaciente, cambios, version)` |
| RF-06 | El sistema debe verificar que el usuario tenga el rol/permiso autorizado antes de registrar o editar un paciente. | UC6 Verificar permisos de usuario (`<<include>>` de UC1 y UC3), actor Sistema RBAC | Nodo de decisión "¿Usuario autorizado para registrar/actualizar?" en ambas ramas | Mensaje `verificarPermiso(usuario, ...)` a `AuthService (RBAC)`, `alt sin permiso / con permiso` |
| RF-07 | El sistema debe detectar y resolver conflictos de concurrencia al editar (control de versión / optimistic locking). | UC3 Actualizar paciente | Nodo de decisión "¿Otro usuario modificó el registro antes?" (rama Actualizar) | Mensaje `UPDATE ... WHERE id=idPaciente AND version=version`, `alt conflicto de concurrencia` |
| RF-08 | El sistema debe registrar en bitácora de auditoría cada registro, edición o intento denegado sobre un paciente. | UC7 Registrar evento de auditoría (`<<include>>` de UC1/UC3, `<<extend>>` de UC6), actor Sistema de Auditoría | Actividades "Registrar intento denegado en bitácora de auditoría" y "Registrar evento de auditoría" en las 3 ramas | Mensajes `registrarEvento(...)` hacia `AuditoriaService` en las tres secciones |
| RF-09 | El sistema debe mostrar una respuesta clara al actor al finalizar cada operación (éxito o error específico). | UC1, UC2, UC3, UC4 (resultado del caso de uso) | Nodos finales de color (verde = éxito, rojo = error de validación/permiso, ámbar = alerta/conflicto) en las 3 ramas | Mensajes de retorno finales `UI --> R` en cada sección (`201 Created`, `403 Forbidden`, `409 Conflict`, `200 OK`, etc.) |

## Notas de trazabilidad

- Los tres diagramas comparten los mismos actores: **Recepcionista**, **Administrador** (generalización de Recepcionista),
  **Sistema RBAC** y **Sistema de Auditoría**.
- Las tres sub-operaciones del proceso (registrar, buscar, actualizar) aparecen como casos de uso independientes (UC1–UC3),
  como ramas del `switch` en el diagrama de actividad, y como secciones (`==`) del diagrama de secuencia.
- Toda excepción modelada en la actividad (dato inválido, duplicado, sin permiso, sin resultados, conflicto de concurrencia)
  tiene su contraparte exacta como bloque `alt` en la secuencia y como flujo de excepción del caso de uso correspondiente.
