<?php

namespace Despues;

/**
 * Contrato único para cualquier repositorio de pacientes.
 *
 * Precondiciones y postcondiciones válidas para TODAS las implementaciones
 * concretas, sin excepción (esto es lo que garantiza el cumplimiento de LSP):
 *
 *  - registrar(array $datos): int
 *      Precondición: exige únicamente documento, nombres, apellidos,
 *      fecha_nacimiento. NINGUNA implementación puede exigir campos
 *      adicionales aquí (los campos adicionales de variantes como
 *      "extranjero" o "menor de edad" se validan por composición, ver
 *      ValidadorPacienteInterface, no fortaleciendo este método).
 *      Postcondición: retorna siempre un int > 0. Nunca imprime.
 *
 *  - buscarPorDocumento(string $documento): ?array
 *      Postcondición: retorna el registro o null si no existe. Ninguna
 *      implementación puede lanzar excepción por "no encontrado".
 *
 *  - actualizar(int $id, array $datos): bool
 *      Postcondición: retorna siempre bool. Ninguna implementación puede
 *      lanzar excepción por reglas de negocio ni cambiar el tipo de retorno.
 */
interface RepositorioPacienteInterface
{
    public function registrar(array $datos): int;

    public function buscarPorDocumento(string $documento): ?array;

    public function actualizar(int $id, array $datos): bool;
}
