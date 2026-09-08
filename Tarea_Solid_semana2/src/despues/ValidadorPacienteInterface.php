<?php

namespace Despues;

/**
 * Contrato de validación: SIEMPRE retorna un ResultadoValidacion. Ninguna
 * implementación puede lanzar excepción por una regla de negocio incumplida
 * (documento faltante, pasaporte faltante, tutor faltante, etc.); las
 * excepciones quedan reservadas para errores de programación (p. ej. tipos
 * incorrectos), nunca para casos de negocio esperables.
 *
 * Este es el mecanismo que reemplaza, por composición, lo que en el diseño
 * ANTES se intentaba resolver con herencia y terminaba violando LSP: cada
 * variante de paciente (estándar, extranjero, menor de edad) tiene su propio
 * validador, pero todos son 100% sustituibles entre sí desde la perspectiva
 * de quien los consume (ServicioPaciente).
 */
interface ValidadorPacienteInterface
{
    /** @param array<string, mixed> $datos */
    public function validar(array $datos): ResultadoValidacion;
}
