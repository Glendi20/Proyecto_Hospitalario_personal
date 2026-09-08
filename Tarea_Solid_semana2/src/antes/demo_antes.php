<?php

/**
 * demo_antes.php
 *
 * Cliente polimórfico que representa el flujo "registro, búsqueda y
 * actualización segura de un paciente". Este cliente NO conoce la variante
 * concreta del repositorio: programa contra el tipo base RepositorioPaciente
 * y confía en el contrato documentado en esa clase.
 *
 * Al recibir subclases (RepositorioPacienteExtranjero, RepositorioPacienteMenorEdad)
 * en sustitución del tipo base, el cliente se rompe. Esa ruptura es la
 * evidencia empírica de la violación del Principio de Sustitución de Liskov.
 *
 * Datos: exclusivamente ficticios, sin información clínica identificable.
 */

require __DIR__ . '/RepositorioPaciente.php';
require __DIR__ . '/RepositorioPacienteExtranjero.php';
require __DIR__ . '/RepositorioPacienteMenorEdad.php';

use Antes\RepositorioPaciente;
use Antes\RepositorioPacienteExtranjero;
use Antes\RepositorioPacienteMenorEdad;

function crearPdoDemo(): PDO
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
 * Datos MÍNIMOS válidos según el contrato del tipo base RepositorioPaciente.
 * El cliente usa exactamente el mismo lote para cualquier repositorio que
 * reciba, porque ese es el punto de programar contra un tipo base: no debería
 * necesitar saber nada especial de la subclase concreta.
 */
$datosPacienteFicticio = [
    'documento' => 'GT-0001',
    'nombres' => 'Paciente',
    'apellidos' => 'DePrueba Uno',
    'fecha_nacimiento' => '1999-05-14',
    'telefono' => '5555-0001',
    'direccion' => 'Zona ficticia 1, Ciudad Demo',
];

/** @var array<string, RepositorioPaciente> $repositorios */
$repositorios = [];

$pdoBase = crearPdoDemo();
$repositorios['RepositorioPaciente (tipo base)'] = new RepositorioPaciente($pdoBase);

$pdoExtranjero = crearPdoDemo();
$repositorios['RepositorioPacienteExtranjero (subclase)'] = new RepositorioPacienteExtranjero($pdoExtranjero);

$pdoMenor = crearPdoDemo();
$repositorios['RepositorioPacienteMenorEdad (subclase)'] = new RepositorioPacienteMenorEdad($pdoMenor);

echo "=== DEMO ANTES: flujo registro/búsqueda/actualización sobre RepositorioPaciente ===\n";
echo "Mismo dato de entrada, mismo código cliente, distintas subclases sustituidas.\n\n";

foreach ($repositorios as $etiqueta => $repositorio) {
    echo "--- Repositorio bajo prueba: {$etiqueta} ---\n";

    // 1) REGISTRO
    $id = 0;
    try {
        $id = $repositorio->registrar($datosPacienteFicticio);
        echo "[OK]    registrar() -> id={$id}\n";
    } catch (\Throwable $e) {
        echo '[ROTO]  registrar() lanzó ' . get_class($e) . ': ' . $e->getMessage() . "\n";
        echo "        -> El cliente esperaba el mismo comportamiento del tipo base.\n";
        echo "        -> Se continúa la demo con id=0 para exhibir también las fallas siguientes.\n";
    }

    // 2) BÚSQUEDA de un documento que NO existe (caso normal: se espera null)
    try {
        $resultado = $repositorio->buscarPorDocumento('GT-NO-EXISTE');
        echo '[OK]    buscarPorDocumento("no existe") -> '
            . ($resultado === null ? 'null (correcto)' : 'valor inesperado') . "\n";
    } catch (\Throwable $e) {
        echo '[ROTO]  buscarPorDocumento() lanzó ' . get_class($e) . ': ' . $e->getMessage() . "\n";
        echo "        -> El tipo base nunca lanza excepción por 'no encontrado'.\n";
    }

    // 3) ACTUALIZACIÓN segura
    try {
        $ok = $repositorio->actualizar($id, ['telefono' => '5555-9999', 'direccion' => 'Nueva dirección ficticia']);
        $tipo = gettype($ok);
        echo "[OK]    actualizar() -> ({$tipo}) " . var_export($ok, true) . "\n";
    } catch (\Throwable $e) {
        echo '[ROTO]  actualizar() lanzó ' . get_class($e) . ': ' . $e->getMessage() . "\n";
        echo "        -> El tipo base garantiza que actualizar() siempre retorna bool.\n";
    }

    echo "\n";
}

echo "=== CONCLUSIÓN DE LA DEMO ===\n";
echo "El repositorio base funciona en los tres pasos. Las subclases sustituidas\n";
echo "rompen el flujo (excepción inesperada en registrar/buscarPorDocumento, o\n";
echo "TypeError en actualizar). Esto es una violación empírica del Principio de\n";
echo "Sustitución de Liskov: sustituir el tipo base por la subclase SÍ altera la\n";
echo "corrección del programa cliente.\n";
