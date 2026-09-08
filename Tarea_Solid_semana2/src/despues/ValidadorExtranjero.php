<?php

namespace Despues;

/**
 * Validador para el paciente "extranjero": exige los campos mínimos más
 * 'pasaporte'. A diferencia del diseño ANTES, esta regla adicional NO se
 * implementa fortaleciendo la precondición de un método heredado: vive en
 * un validador independiente que cumple el mismo contrato
 * (ValidadorPacienteInterface) que ValidadorEstandar. Es 100% sustituible.
 */
class ValidadorExtranjero implements ValidadorPacienteInterface
{
    public function validar(array $datos): ResultadoValidacion
    {
        $errores = [];

        foreach (['documento', 'nombres', 'apellidos', 'fecha_nacimiento', 'pasaporte'] as $campo) {
            if (empty($datos[$campo])) {
                $errores[$campo] = "El campo '{$campo}' es obligatorio para paciente extranjero.";
            }
        }

        return $errores ? ResultadoValidacion::invalido($errores) : ResultadoValidacion::valido();
    }
}
