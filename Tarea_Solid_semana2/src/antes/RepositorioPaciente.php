<?php

namespace Antes;

/**
 * Clase base "RepositorioPaciente" (diseño ANTES de aplicar LSP).
 *
 * Contrato que el código cliente asume implícitamente al programar contra
 * esta clase (y que, por sustitución, debería cumplir cualquier subclase):
 *
 *  - registrar(array $datos): int
 *      Precondición: sólo exige documento, nombres, apellidos, fecha_nacimiento.
 *      Postcondición: retorna un id entero > 0. NUNCA lanza excepción si
 *      esos cuatro campos mínimos están presentes.
 *
 *  - buscarPorDocumento(string $documento): ?array
 *      Postcondición: retorna el registro como array o null si no existe.
 *      JAMÁS lanza excepción por "no encontrado": ausencia de resultado es
 *      un caso normal, no un error.
 *
 *  - actualizar(int $id, array $datos): bool
 *      Postcondición: SIEMPRE retorna bool. Nunca imprime, nunca lanza
 *      excepción por reglas de negocio.
 *
 * Datos: exclusivamente ficticios (sin información clínica identificable).
 */
class RepositorioPaciente
{
    protected \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function registrar(array $datos): int
    {
        $this->validarMinimos($datos);

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

    protected function validarMinimos(array $datos): void
    {
        foreach (['documento', 'nombres', 'apellidos', 'fecha_nacimiento'] as $campo) {
            if (empty($datos[$campo])) {
                throw new \InvalidArgumentException("Falta el campo obligatorio: {$campo}");
            }
        }
    }
}
