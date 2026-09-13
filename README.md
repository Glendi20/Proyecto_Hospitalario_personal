# Módulo 03 — Paciente local, búsqueda y vínculo con MPI CENTRAL (proyecto ejecutable)

**Estudiante:** Glendi Patricia Campos Orellana
**GitHub:** [`Glendi20`](https://github.com/Glendi20)
**Módulo asignado:** Pacientes: registro, edición, búsqueda y detalle → evolucionado a arquitectura federada CENTRAL/HOSPITAL (semanas 3 y 4).

## Qué es esta rama

Es una copia **ejecutable y acotada a mi módulo** del proyecto final de equipo (Sistema Hospitalario
Integrado, Análisis de Sistemas II 2026). Contiene únicamente el scaffold base de Laravel
(autenticación, tenants, RBAC) más todo el trabajo de mi módulo — no incluye el código de los
demás compañeros del curso, que sí vive en el repositorio de equipo
(`compilations-teams/sistema-hospitalario-integrado-SistenasII-2026`) y en mi PR ahí.

## Contenido por semana

| Semana | Entrega | Ubicación |
|---|---|---|
| 3 | Vista arquitectónica del módulo (estilo del HIS, C4, capas planeadas) | [`docs/module-03/vista-arquitectonica.md`](docs/module-03/vista-arquitectonica.md) |
| 4 | Evolución a arquitectura federada CENTRAL/HOSPITAL: especificación ampliada, ADR, diagramas, código por capas, migraciones PostgreSQL, pruebas | [`docs/modulos/mod03/`](docs/modulos/mod03/) + código en `app/`, `database/`, `tests/` |

Semanas 1 y 2 (modelado UML y principio SOLID/LSP) están en la rama `main` de este mismo
repositorio, en la raíz y en [`Tarea_Solid_semana2/`](../../tree/main/Tarea_Solid_semana2)
respectivamente.

## Qué NO incluye esta rama (y por qué)

Para que el proyecto sea ejecutable con solo mi parte, se quitaron del scaffold original:

- El código de otros módulos ya integrados en `develop` del repo de equipo (p. ej. Módulo 20 —
  validación de resultados de laboratorio — y sus modelos, rutas, provider y pruebas).
- Modelos, migraciones, factories y seeders de módulos clínicos que no son míos (admisiones, EMR,
  laboratorio, medicamentos, camas/salas, auditoría). El modelo `Patient` conserva sus relaciones
  Eloquent hacia esas entidades (`allergies()`, `admissions()`, `medicalRecord()`, etc.) tal como
  están en el repo de equipo, mostrando el contrato completo — pero no se llaman en ningún flujo de
  esta rama, así que no requieren esas tablas para funcionar.
- Documentación y plantillas específicas de otros compañeros o del proceso de equipo (guía de
  worktrees, plan semanal de 18 semanas, plantillas de issue/PR).

Nada de esto afecta al módulo de Pacientes: su documentación completa está en
[`ADR-001-arquitectura.md`](docs/modulos/mod03/ADR-001-arquitectura.md).

## Instalación y ejecución

Requisitos: PHP 8.2+, Composer 2, y PostgreSQL (recomendado) o SQLite para pruebas rápidas.

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
```

### Opción rápida (SQLite)

```bash
touch database/database.sqlite
php artisan migrate:fresh --seed
php artisan serve
```

### Arquitectura federada real (PostgreSQL, HOSPITAL y CENTRAL como bases distintas)

Ver el bloque `CENTRAL_DB_*` en [`.env.example`](.env.example) y el detalle completo en
[`docs/modulos/mod03/ADR-001-arquitectura.md`](docs/modulos/mod03/ADR-001-arquitectura.md).

```bash
psql -U postgres -c "CREATE DATABASE shi_hospital_glendi;"
psql -U postgres -c "CREATE DATABASE shi_central_glendi;"
# Configurar .env: DB_CONNECTION=pgsql (+ credenciales) y CENTRAL_DB_CONNECTION=pgsql (+ credenciales)
php artisan migrate:fresh --seed
php artisan serve
```

### Pruebas

```bash
php artisan test
```

## Endpoints de este módulo

Todas las rutas están bajo `/api/v1`, requieren la cabecera `X-Tenant-ID` y, salvo login/registro,
un `Authorization: Bearer <token>` JWT.

| Método | Ruta | Rol | Descripción |
|---|---|---|---|
| POST | `/auth/register` | — | Alta de usuario (rol Recepcionista por defecto). |
| POST | `/auth/login` | — | Login, devuelve JWT. |
| POST | `/patients` | Recepcionista, Admin | Registrar paciente local + intento de vínculo MPI. |
| GET | `/patients` | Recepcionista, Admin, Médico, Enfermera | Buscar pacientes del hospital activo. |
| POST | `/patients/match-candidates/{id}/resolve` | Recepcionista, Admin | Confirmar/rechazar una coincidencia MPI ambigua. |

Comando de sincronización del outbox (continuidad ante falla de CENTRAL):

```bash
php artisan patients:sync-mpi
```

## Documentación completa

- [`docs/modulos/mod03/ESPECIFICACION.md`](docs/modulos/mod03/ESPECIFICACION.md) — problema, actores, RF/RNF, criterios de aceptación.
- [`docs/modulos/mod03/ADR-001-arquitectura.md`](docs/modulos/mod03/ADR-001-arquitectura.md) — decisiones de arquitectura y alternativas descartadas.
- [`docs/modulos/mod03/diagramas/`](docs/modulos/mod03/diagramas/) — casos de uso, clases, secuencia, componentes y modelo de datos.
- [`docs/modulos/mod03/INFORME.md`](docs/modulos/mod03/INFORME.md) — informe consolidado.
- [`EVIDENCIA.md`](EVIDENCIA.md) — comandos de validación y estado de ejecución.
- [`DECLARACION_IA.md`](DECLARACION_IA.md) — declaración de uso de IA.
