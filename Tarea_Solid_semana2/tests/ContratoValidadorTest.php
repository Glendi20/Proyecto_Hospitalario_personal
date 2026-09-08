<?php

/**
 * Batería única de aserciones de contrato para cualquier
 * Despues\ValidadorPacienteInterface: nunca lanza excepción por una regla de
 * negocio incumplida y siempre retorna Despues\ResultadoValidacion.
 */
function contratoValidador(\Despues\ValidadorPacienteInterface $validador, array $datosIncompletos, string $etiqueta): void
{
    echo "> Contrato de validador sobre: {$etiqueta}\n";

    Aserciones::noLanza(function () use ($validador, $datosIncompletos) {
        $resultado = $validador->validar($datosIncompletos);
        if (!$resultado instanceof \Despues\ResultadoValidacion) {
            throw new \RuntimeException('validar() no retornó ResultadoValidacion');
        }
        if ($resultado->esValido()) {
            throw new \RuntimeException('se esperaba un resultado inválido para datos incompletos');
        }
    }, 'validar() con datos incompletos retorna ResultadoValidacion inválido, sin lanzar excepción');

    echo "\n";
}
