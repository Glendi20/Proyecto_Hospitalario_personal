<?php

/**
 * demo_despues.php
 *
 * El mismo cliente (bucle genérico) del flujo "registro, búsqueda y
 * actualización segura de un paciente", ahora ejercitado con TRES
 * combinaciones distintas de repositorio+validador (estándar sobre PDO,
 * extranjero sobre PDO, menor de edad sobre memoria). Ninguna combinación
 * requiere código especial en el cliente: todas cumplen el mismo contrato.
 *
 * Datos: exclusivamente ficticios, sin información clínica identificable.
 */

require __DIR__ . '/RepositorioPacienteInterface.php';
require __DIR__ . '/RepositorioPacientePDO.php';
require __DIR__ . '/RepositorioPacienteMemoria.php';
require __DIR__ . '/ResultadoValidacion.php';
require __DIR__ . '/ValidadorPacienteInterface.php';
require __DIR__ . '/ValidadorEstandar.php';
require __DIR__ . '/ValidadorExtranjero.php';
require __DIR__ . '/ValidadorMenorEdad.php';
require __DIR__ . '/ResultadoOperacion.php';
require __DIR__ . '/ServicioPaciente.php';

use Despues\RepositorioPacientePDO;
use Despues\RepositorioPacienteMemoria;
use Despues\ValidadorEstandar;
use Despues\ValidadorExtranjero;
use Despues\ValidadorMenorEdad;
use Despues\ServicioPaciente;

function crearPdoDemoDespues(): PDO
{
    $pdo = new PDO('sqlite::memory:');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec(
        'CREATE TABLE pacientes (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            documento TEXT NOT NULL,
            nombres TEXT NOT NULL,
            apellidos TEXT NOT NULL,
            fecha_nacimiento TEXT NOT NULL,
            telefono TEXT,
            direccion TEXT,
            fecha_registro TEXT NOT NULL
        )'
    );

    return $pdo;
}

/**
 * Cliente genérico: recibe un ServicioPaciente ya compuesto (repositorio +
 * validador inyectados) y un lote de datos. No sabe ni le importa qué
 * variante concreta está detrás de la interfaz.
 */
function ejecutarFlujoPaciente(ServicioPaciente $servicio, array $datos, string $documentoBusqueda): void
{
    $resultado = $servicio->registrar($datos);

    if (!$resultado->esExitoso()) {
        echo '[VALIDACIÓN] registrar() rechazado (comportamiento esperado, no una excepción): '
            . implode('; ', $resultado->errores()) . "\n";

        return;
    }

    $id = $resultado->idPaciente();
    echo "[OK] registrar() -> id={$id}\n";

    $noEncontrado = $servicio->buscarPorDocumento('GT-NO-EXISTE');
    echo '[OK] buscarPorDocumento("no existe") -> '
        . ($noEncontrado === null ? 'null (correcto)' : 'valor inesperado') . "\n";

    $encontrado = $servicio->buscarPorDocumento($documentoBusqueda);
    echo '[OK] buscarPorDocumento("' . $documentoBusqueda . '") -> '
        . ($encontrado !== null ? 'registro encontrado' : 'NO encontrado (inesperado)') . "\n";

    $actualizado = $servicio->actualizar($id, ['telefono' => '5555-8888', 'direccion' => 'Dirección ficticia actualizada']);
    echo '[OK] actualizar() -> (' . gettype($actualizado) . ') ' . var_export($actualizado, true) . "\n";
}

echo "=== DEMO DESPUÉS: mismo cliente, tres combinaciones repositorio+validador ===\n\n";

echo "--- Combinación 1: paciente ESTÁNDAR + RepositorioPacientePDO ---\n";
$servicio1 = new ServicioPaciente(new RepositorioPacientePDO(crearPdoDemoDespues()), new ValidadorEstandar());
$datosEstandar = [
    'documento' => 'GT-1001',
    'nombres' => 'Paciente',
    'apellidos' => 'DePrueba Estandar',
    'fecha_nacimiento' => '1990-01-01',
    'telefono' => '5555-1001',
    'direccion' => 'Zona ficticia 1, Ciudad Demo',
];
ejecutarFlujoPaciente($servicio1, $datosEstandar, 'GT-1001');

echo "\n--- Combinación 2: paciente EXTRANJERO + RepositorioPacientePDO ---\n";
$servicio2 = new ServicioPaciente(new RepositorioPacientePDO(crearPdoDemoDespues()), new ValidadorExtranjero());
$datosExtranjero = [
    'documento' => 'PAS-2002',
    'nombres' => 'Paciente',
    'apellidos' => 'DePrueba Extranjero',
    'fecha_nacimiento' => '1985-03-20',
    'pasaporte' => 'X1234567',
    'telefono' => '5555-2002',
    'direccion' => 'Zona ficticia 2, Ciudad Demo',
];
ejecutarFlujoPaciente($servicio2, $datosExtranjero, 'PAS-2002');

echo "\n--- Combinación 2b: paciente EXTRANJERO sin pasaporte (validación, NO excepción) ---\n";
$servicio2b = new ServicioPaciente(new RepositorioPacientePDO(crearPdoDemoDespues()), new ValidadorExtranjero());
$datosExtranjeroIncompleto = $datosExtranjero;
unset($datosExtranjeroIncompleto['pasaporte']);
ejecutarFlujoPaciente($servicio2b, $datosExtranjeroIncompleto, 'PAS-2002');

echo "\n--- Combinación 3: paciente MENOR DE EDAD + RepositorioPacienteMemoria ---\n";
$servicio3 = new ServicioPaciente(new RepositorioPacienteMemoria(), new ValidadorMenorEdad());
$datosMenor = [
    'documento' => 'GT-3003',
    'nombres' => 'Paciente',
    'apellidos' => 'DePrueba Menor',
    'fecha_nacimiento' => '2015-07-10',
    'nombre_tutor' => 'Tutor Ficticio Tres',
    'telefono' => '5555-3003',
    'direccion' => 'Zona ficticia 3, Ciudad Demo',
];
ejecutarFlujoPaciente($servicio3, $datosMenor, 'GT-3003');

echo "\n=== CONCLUSIÓN DE LA DEMO ===\n";
echo "El mismo cliente (ejecutarFlujoPaciente) funcionó sin cambios ni casos\n";
echo "especiales para las tres combinaciones de repositorio+validador, incluida\n";
echo "la ruta de rechazo por validación (que retorna un resultado, no lanza\n";
echo "excepción). Esto evidencia que todas las implementaciones concretas son\n";
echo "sustituibles entre sí: se cumple el Principio de Sustitución de Liskov.\n";
