<?php

namespace Despues;

/**
 * Objeto de valor que representa el resultado de una operación de negocio
 * (registrar). Permite a ServicioPaciente comunicar éxito/fallo sin recurrir
 * a excepciones para casos de negocio esperables.
 */
final class ResultadoOperacion
{
    /** @param array<string, string> $errores */
    private function __construct(
        private bool $exitoso,
        private ?int $idPaciente,
        private array $errores
    ) {
    }

    public static function exito(int $idPaciente): self
    {
        return new self(true, $idPaciente, []);
    }

    /** @param array<string, string> $errores */
    public static function fallo(array $errores): self
    {
        return new self(false, null, $errores);
    }

    public function esExitoso(): bool
    {
        return $this->exitoso;
    }

    public function idPaciente(): ?int
    {
        return $this->idPaciente;
    }

    /** @return array<string, string> */
    public function errores(): array
    {
        return $this->errores;
    }
}
