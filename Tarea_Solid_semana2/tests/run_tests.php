<?php

/**
 * run_tests.php
 *
 * Ejecuta la MISMA batería de contrato (contratoRepositorio / contratoValidador)
 * contra:
 *  1) Las clases del diseño ANTES (se espera que FALLE al menos una aserción
 *     por cada subclase: esa falla ES la evidencia de la violación LSP).
 *  2) Las clases del diseño DESPUÉS (se espera que TODAS las aserciones pasen:
 *     esa es la evidencia de que el rediseño cumple LSP).
 *
 * Uso:  php tests/run_tests.php
 */

require __DIR__ . '/bootstrap.php';
require __DIR__ . '/ContratoRepositorioTest.php';
require __DIR__ . '/ContratoValidadorTest.php';

use Antes\RepositorioPaciente;
use Antes\RepositorioPacienteExtranjero;
use Antes\RepositorioPacienteMenorEdad;
use Despues\RepositorioPacientePDO;
use Despues\RepositorioPacienteMemoria;
use Despues\ValidadorEstandar;
use Despues\ValidadorExtranjero;
use Despues\ValidadorMenorEdad;

echo str_repeat('=', 78) . "\n";
echo "SECCIÓN 1 / 2 — DISEÑO ANTES (se espera que ALGUNA aserción falle:\n";
echo "                 esa falla es la evidencia de la violación LSP, CA-05)\n";
echo str_repeat('=', 78) . "\n\n";

Aserciones::reiniciar();

contratoRepositorio(new RepositorioPaciente(nuevaConexionSqlite()), 'Antes\\RepositorioPaciente (tipo base)');
contratoRepositorio(new RepositorioPacienteExtranjero(nuevaConexionSqlite()), 'Antes\\RepositorioPacienteExtranjero (subclase)');
contratoRepositorio(new RepositorioPacienteMenorEdad(nuevaConexionSqlite()), 'Antes\\RepositorioPacienteMenorEdad (subclase)');

$totalAntes = Aserciones::$total;
$fallidasAntes = Aserciones::$fallidas;

echo "Resultado ANTES: {$fallidasAntes} de {$totalAntes} aserciones fallaron.\n";
echo ($fallidasAntes > 0)
    ? "-> CONFIRMADO: el diseño ANTES viola LSP (se registran fallas al sustituir subclases).\n\n"
    : "-> INESPERADO: no se registraron fallas; revisar el escenario de violación.\n\n";

echo str_repeat('=', 78) . "\n";
echo "SECCIÓN 2 / 2 — DISEÑO DESPUÉS (se espera que TODAS las aserciones pasen:\n";
echo "                 CA-01, CA-02, CA-03, CA-04)\n";
echo str_repeat('=', 78) . "\n\n";

Aserciones::reiniciar();

contratoRepositorio(new RepositorioPacientePDO(nuevaConexionSqlite()), 'Despues\\RepositorioPacientePDO');
contratoRepositorio(new RepositorioPacienteMemoria(), 'Despues\\RepositorioPacienteMemoria');

contratoValidador(new ValidadorEstandar(), [], 'Despues\\ValidadorEstandar');
contratoValidador(new ValidadorExtranjero(), ['documento' => 'X'], 'Despues\\ValidadorExtranjero');
contratoValidador(new ValidadorMenorEdad(), ['documento' => 'X'], 'Despues\\ValidadorMenorEdad');

$totalDespues = Aserciones::$total;
$fallidasDespues = Aserciones::$fallidas;

echo "Resultado DESPUÉS: {$fallidasDespues} de {$totalDespues} aserciones fallaron.\n";
echo ($fallidasDespues === 0)
    ? "-> CONFIRMADO: el diseño DESPUÉS cumple LSP (todas las implementaciones son sustituibles).\n\n"
    : "-> ATENCIÓN: hay fallas en el diseño DESPUÉS; revisar antes de considerarlo válido.\n\n";

echo str_repeat('=', 78) . "\n";
echo "RESUMEN FINAL\n";
echo str_repeat('=', 78) . "\n";
echo "ANTES   : {$fallidasAntes}/{$totalAntes} fallidas (se ESPERA > 0 -> violación evidenciada)\n";
echo "DESPUÉS : {$fallidasDespues}/{$totalDespues} fallidas (se REQUIERE 0 -> LSP cumplido)\n";

$evidenciaValida = ($fallidasAntes > 0) && ($fallidasDespues === 0);
echo "\nVEREDICTO: " . ($evidenciaValida
    ? "OK - La evidencia respalda la aplicación correcta de LSP en el rediseño.\n"
    : "REVISAR - La evidencia NO respalda completamente el rediseño.\n");

exit($evidenciaValida ? 0 : 1);
