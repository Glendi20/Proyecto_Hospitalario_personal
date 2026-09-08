<?php

/**
 * Batería única de aserciones de contrato para cualquier repositorio de
 * pacientes (técnica de "contract testing" / prueba de sustitución LSP):
 * la MISMA función se ejecuta contra cada implementación concreta. Si
 * alguna implementación necesita una versión especial de esta función para
 * pasar, esa implementación NO es sustituible y viola LSP.
 */
function contratoRepositorio(object $repositorio, string $etiqueta): void
{
    echo "> Contrato de repositorio sobre: {$etiqueta}\n";

    $datosMinimos = [
        'documento' => 'GT-CONTRATO-' . substr(md5($etiqueta), 0, 6),
        'nombres' => 'Paciente',
        'apellidos' => 'Contrato',
        'fecha_nacimiento' => '2000-01-01',
    ];

    $id = null;

    Aserciones::noLanza(function () use ($repositorio, $datosMinimos, &$id) {
        $id = $repositorio->registrar($datosMinimos);
        if (!is_int($id) || $id <= 0) {
            throw new \RuntimeException('registrar() no retornó un int > 0');
        }
    }, 'registrar() con datos mínimos no lanza excepción y retorna int > 0');

    Aserciones::noLanza(function () use ($repositorio) {
        $resultado = $repositorio->buscarPorDocumento('GT-DOCUMENTO-INEXISTENTE');
        if ($resultado !== null) {
            throw new \RuntimeException('se esperaba null para un documento inexistente');
        }
    }, 'buscarPorDocumento() de un documento inexistente retorna null sin lanzar excepción');

    if ($id !== null) {
        Aserciones::noLanza(function () use ($repositorio, $id) {
            $ok = $repositorio->actualizar($id, ['telefono' => '5555-0000', 'direccion' => 'Dirección de contrato']);
            if (!is_bool($ok)) {
                throw new \RuntimeException('actualizar() no retornó bool (retornó ' . gettype($ok) . ')');
            }
        }, 'actualizar() retorna siempre bool, sin lanzar excepción por reglas de negocio');
    } else {
        Aserciones::verdadero(false, 'actualizar() no pudo verificarse: registrar() no produjo id');
    }

    echo "\n";
}
