<?php

namespace Antes;

/**
 * Subclase "RepositorioPacienteMenorEdad" (diseño ANTES).
 *
 * VIOLACIÓN LSP #3 — Excepción no contemplada por el contrato base:
 *   buscarPorDocumento() del padre retorna null cuando no encuentra el
 *   registro (comportamiento normal, no es un error). Esta subclase, en
 *   cambio, lanza \RuntimeException en ese mismo caso. Cualquier cliente
 *   que confía en el contrato del padre (comprobar `=== null`) y recibe
 *   esta subclase por sustitución, termina con una excepción no capturada.
 */
class RepositorioPacienteMenorEdad extends RepositorioPaciente
{
    public function buscarPorDocumento(string $documento): ?array
    {
        $resultado = parent::buscarPorDocumento($documento);

        if ($resultado === null) {
            throw new \RuntimeException(
                "No se encontró paciente menor de edad con documento {$documento} (falta tutor)."
            );
        }

        return $resultado;
    }
}
