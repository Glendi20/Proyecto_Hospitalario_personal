<?php

namespace Despues;

/**
 * Implementación concreta que persiste en una base de datos real (SQLite vía
 * PDO en esta demo; el mismo contrato aplicaría con MySQL/PostgreSQL).
 * Cumple exactamente el contrato de RepositorioPacienteInterface: no agrega
 * precondiciones, no cambia postcondiciones, no lanza excepciones fuera de
 * lo documentado en la interfaz.
 */
class RepositorioPacientePDO implements RepositorioPacienteInterface
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function registrar(array $datos): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO pacientes (documento, nombres, apellidos, fecha_nacimiento, telefono, direccion, fecha_registro)
             VALUES (:documento, :nombres, :apellidos, :fecha_nacimiento, :telefono, :direccion, :fecha_registro)'
        );
        $stmt->execute([
            ':documento' => $datos['documento'],
            ':nombres' => $datos['nombres'],
            ':apellidos' => $datos['apellidos'],
            ':fecha_nacimiento' => $datos['fecha_nacimiento'],
            ':telefono' => $datos['telefono'] ?? null,
            ':direccion' => $datos['direccion'] ?? null,
            ':fecha_registro' => date('Y-m-d H:i:s'),
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function buscarPorDocumento(string $documento): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM pacientes WHERE documento = :documento');
        $stmt->execute([':documento' => $documento]);
        $fila = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $fila ?: null;
    }

    public function actualizar(int $id, array $datos): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE pacientes SET telefono = :telefono, direccion = :direccion WHERE id = :id'
        );

        return $stmt->execute([
            ':telefono' => $datos['telefono'] ?? null,
            ':direccion' => $datos['direccion'] ?? null,
            ':id' => $id,
        ]);
    }
}
