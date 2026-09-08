-- schema.sql
-- Esquema de referencia para la tabla "pacientes" usada en la demo del flujo
-- "registro, búsqueda y actualización segura de un paciente".
--
-- Los scripts PHP de demo (src/antes/demo_antes.php, src/despues/demo_despues.php,
-- tests/bootstrap.php) crean este mismo esquema de forma programática sobre
-- SQLite en memoria (PDO::sqlite::memory:), para que la demo sea autocontenida
-- y no dependa de un motor de base de datos externo. Este archivo documenta
-- el esquema equivalente para un motor persistente (MySQL/PostgreSQL/SQLite en
-- archivo) y sirve como fuente editable de referencia.
--
-- Todos los datos de ejemplo son EXCLUSIVAMENTE FICTICIOS.

CREATE TABLE IF NOT EXISTS pacientes (
    id                INTEGER PRIMARY KEY AUTOINCREMENT,
    documento         VARCHAR(30)  NOT NULL UNIQUE,   -- documento de identidad o pasaporte (ficticio)
    nombres           VARCHAR(100) NOT NULL,
    apellidos         VARCHAR(100) NOT NULL,
    fecha_nacimiento  DATE         NOT NULL,
    telefono          VARCHAR(30),
    direccion         VARCHAR(200),
    fecha_registro    DATETIME     NOT NULL
);

-- Datos ficticios de ejemplo (NO corresponden a personas reales, NO contienen
-- información clínica identificable).
INSERT INTO pacientes (documento, nombres, apellidos, fecha_nacimiento, telefono, direccion, fecha_registro)
VALUES
    ('GT-1001', 'Paciente', 'DePrueba Estandar', '1990-01-01', '5555-1001', 'Zona ficticia 1, Ciudad Demo', '2026-08-01 08:00:00'),
    ('PAS-2002', 'Paciente', 'DePrueba Extranjero', '1985-03-20', '5555-2002', 'Zona ficticia 2, Ciudad Demo', '2026-08-02 09:15:00'),
    ('GT-3003', 'Paciente', 'DePrueba Menor', '2015-07-10', '5555-3003', 'Zona ficticia 3, Ciudad Demo', '2026-08-03 10:30:00');
