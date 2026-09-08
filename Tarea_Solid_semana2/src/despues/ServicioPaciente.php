<?php

namespace Despues;

/**
 * Orquestador del flujo "registro, búsqueda y actualización segura de un
 * paciente". Depende únicamente de las interfaces (RepositorioPacienteInterface
 * y ValidadorPacienteInterface), nunca de una implementación concreta.
 *
 * Esto es lo que hace posible que el mismo código, sin ifs por tipo ni
 * try/catch especiales por variante, funcione idénticamente sin importar
 * qué implementación concreta se le inyecte: es la prueba de que las
 * implementaciones son sustituibles (LSP) entre sí.
 */
class ServicioPaciente
{
    public function __construct(
        private RepositorioPacienteInterface $repositorio,
        private ValidadorPacienteInterface $validador
    ) {
    }

    /** @param array<string, mixed> $datos */
    public function registrar(array $datos): ResultadoOperacion
    {
        $resultadoValidacion = $this->validador->validar($datos);

        if (!$resultadoValidacion->esValido()) {
            return ResultadoOperacion::fallo($resultadoValidacion->errores());
        }

        $id = $this->repositorio->registrar($datos);

        return ResultadoOperacion::exito($id);
    }

    public function buscarPorDocumento(string $documento): ?array
    {
        return $this->repositorio->buscarPorDocumento($documento);
    }

    /** @param array<string, mixed> $datos */
    public function actualizar(int $id, array $datos): bool
    {
        return $this->repositorio->actualizar($id, $datos);
    }
}
