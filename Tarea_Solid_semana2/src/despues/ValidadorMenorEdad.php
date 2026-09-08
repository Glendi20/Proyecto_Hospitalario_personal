<?php

namespace Despues;

/**
 * Validador para el paciente "menor de edad": exige los campos mínimos más
 * el nombre del tutor responsable. Igual que ValidadorExtranjero, nunca
 * lanza excepción por la regla de negocio incumplida: retorna
 * ResultadoValidacion::invalido(), cumpliendo el mismo contrato que
 * cualquier otro ValidadorPacienteInterface.
 */
class ValidadorMenorEdad implements ValidadorPacienteInterface
{
    public function validar(array $datos): ResultadoValidacion
    {
        $errores = [];

        foreach (['documento', 'nombres', 'apellidos', 'fecha_nacimiento', 'nombre_tutor'] as $campo) {
            if (empty($datos[$campo])) {
                $errores[$campo] = "El campo '{$campo}' es obligatorio para paciente menor de edad.";
            }
        }

        return $errores ? ResultadoValidacion::invalido($errores) : ResultadoValidacion::valido();
    }
}
