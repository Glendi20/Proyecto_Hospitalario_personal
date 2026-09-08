<?php

namespace Despues;

/**
 * Objeto de valor inmutable que representa el resultado de una validación.
 * Su existencia es lo que permite que ningún ValidadorPacienteInterface
 * necesite lanzar una excepción para señalar una regla de negocio
 * incumplida: en vez de eso, retorna este objeto (contrato uniforme).
 */
final class ResultadoValidacion
{
    /** @param array<string, string> $errores campo => mensaje */
    private function __construct(private bool $valido, private array $errores)
    {
    }

    public static function valido(): self
    {
        return new self(true, []);
    }

    /** @param array<string, string> $errores */
    public static function invalido(array $errores): self
    {
        return new self(false, $errores);
    }

    public function esValido(): bool
    {
        return $this->valido;
    }

    /** @return array<string, string> */
    public function errores(): array
    {
        return $this->errores;
    }
}
