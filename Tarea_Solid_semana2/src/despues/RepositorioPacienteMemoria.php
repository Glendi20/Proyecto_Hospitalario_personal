<?php

namespace Despues;

/**
 * Implementación concreta en memoria (usada en pruebas y demos rápidas, sin
 * requerir base de datos). Cumple exactamente el mismo contrato que
 * RepositorioPacientePDO: es sustituible por ella y viceversa, en cualquier
 * punto del sistema, sin que el código cliente note la diferencia.
 */
class RepositorioPacienteMemoria implements RepositorioPacienteInterface
{
    /** @var array<int, array<string, mixed>> */
    private array $registros = [];

    private int $siguienteId = 1;

    public function registrar(array $datos): int
    {
        $id = $this->siguienteId++;

        $this->registros[$id] = [
            'id' => $id,
            'documento' => $datos['documento'],
            'nombres' => $datos['nombres'],
            'apellidos' => $datos['apellidos'],
            'fecha_nacimiento' => $datos['fecha_nacimiento'],
            'telefono' => $datos['telefono'] ?? null,
            'direccion' => $datos['direccion'] ?? null,
            'fecha_registro' => date('Y-m-d H:i:s'),
        ];

        return $id;
    }

    public function buscarPorDocumento(string $documento): ?array
    {
        foreach ($this->registros as $registro) {
            if ($registro['documento'] === $documento) {
                return $registro;
            }
        }

        return null;
    }

    public function actualizar(int $id, array $datos): bool
    {
        if (!isset($this->registros[$id])) {
            return false;
        }

        $this->registros[$id]['telefono'] = $datos['telefono'] ?? $this->registros[$id]['telefono'];
        $this->registros[$id]['direccion'] = $datos['direccion'] ?? $this->registros[$id]['direccion'];

        return true;
    }
}
