<?php

/**
 * Autoloader mínimo (sin Composer) para los namespaces Antes\ y Despues\.
 */

spl_autoload_register(function (string $class): void {
    $prefixes = [
        'Antes\\' => __DIR__ . '/../src/antes/',
        'Despues\\' => __DIR__ . '/../src/despues/',
    ];

    foreach ($prefixes as $prefix => $dir) {
        if (str_starts_with($class, $prefix)) {
            $relative = substr($class, strlen($prefix));
            $file = $dir . str_replace('\\', '/', $relative) . '.php';
            if (file_exists($file)) {
                require $file;

                return;
            }
        }
    }
});

/** Micro-runner de aserciones (evita depender de PHPUnit/Composer). */
final class Aserciones
{
    public static int $total = 0;
    public static int $fallidas = 0;

    public static function reiniciar(): void
    {
        self::$total = 0;
        self::$fallidas = 0;
    }

    public static function verdadero(bool $condicion, string $mensaje): void
    {
        self::$total++;
        echo ($condicion ? '  [PASS] ' : '  [FAIL] ') . $mensaje . "\n";
        if (!$condicion) {
            self::$fallidas++;
        }
    }

    public static function noLanza(callable $fn, string $mensaje): void
    {
        self::$total++;
        try {
            $fn();
            echo "  [PASS] {$mensaje}\n";
        } catch (\Throwable $e) {
            self::$fallidas++;
            echo "  [FAIL] {$mensaje} -> lanzó " . get_class($e) . ': ' . $e->getMessage() . "\n";
        }
    }
}

function nuevaConexionSqlite(): PDO
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
