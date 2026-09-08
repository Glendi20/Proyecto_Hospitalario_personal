<?php

namespace Antes;

/**
 * Subclase "RepositorioPacienteExtranjero" (diseño ANTES).
 *
 * VIOLACIÓN LSP #1 — Fortalecimiento de precondición:
 *   registrar() exige un campo adicional obligatorio ('pasaporte') que la
 *   clase base jamás exigió. Un cliente que sustituye RepositorioPaciente
 *   por esta subclase, y le pasa el mismo conjunto mínimo de datos que
 *   siempre funcionó con el padre, recibe una excepción inesperada.
 *
 * VIOLACIÓN LSP #2 — Debilitamiento/ruptura de la postcondición:
 *   actualizar() dejó de comportarse como "siempre retorna bool". En su
 *   lugar imprime el resultado por pantalla (efecto colateral no
 *   contemplado por el contrato) e intenta retornar null desde un método
 *   tipado como ": bool". En PHP con tipado esto produce un \TypeError en
 *   tiempo de ejecución: el código cliente que hace
 *   `if ($repositorio->actualizar($id, $datos)) { ... }` se rompe.
 */
class RepositorioPacienteExtranjero extends RepositorioPaciente
{
    public function registrar(array $datos): int
    {
        if (empty($datos['pasaporte'])) {
            // El padre NUNCA exige este campo: esta excepción es una
            // precondición más fuerte que la del tipo base -> viola LSP.
            throw new \InvalidArgumentException(
                'El pasaporte es obligatorio para pacientes extranjeros.'
            );
        }

        return parent::registrar($datos);
    }

    public function actualizar(int $id, array $datos): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE pacientes SET telefono = :telefono, direccion = :direccion WHERE id = :id'
        );
        $ok = $stmt->execute([
            ':telefono' => $datos['telefono'] ?? null,
            ':direccion' => $datos['direccion'] ?? null,
            ':id' => $id,
        ]);

        // Efecto colateral no contemplado por el contrato base:
        echo $ok ? "Actualizado OK\n" : "Fallo al actualizar\n";

        // @phpstan-ignore-next-line  -- intencional: rompe el contrato ": bool"
        return null;
    }
}
