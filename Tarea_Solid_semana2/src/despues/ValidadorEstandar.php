<?php

namespace Despues;

/**
 * Validador para el paciente "estándar": únicamente exige los cuatro campos
 * mínimos del contrato de registro.
 */
class ValidadorEstandar implements ValidadorPacienteInterface
{
    public function validar(array $datos): ResultadoValidacion
    {
        $errores = [];

        foreach (['documento', 'nombres', 'apellidos', 'fecha_nacimiento'] as $campo) {
            if (empty($datos[$campo])) {
                $errores[$campo] = "El campo '{$campo}' es obligatorio.";
            }
        }

        return $errores ? ResultadoValidacion::invalido($errores) : ResultadoValidacion::valido();
    }
}
