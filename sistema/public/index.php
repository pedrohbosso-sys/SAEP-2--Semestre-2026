<?php
declare(strict_types=1);

if (PHP_SAPI === 'cli-server') {
    $caminhoRequisitado = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    $arquivoSolicitado = realpath(__DIR__ . DIRECTORY_SEPARATOR . ltrim((string)$caminhoRequisitado, '/\\'));
    $diretorioPublico = realpath(__DIR__);

    if (
        $arquivoSolicitado !== false
        && $diretorioPublico !== false
        && str_starts_with($arquivoSolicitado, $diretorioPublico . DIRECTORY_SEPARATOR)
        && is_file($arquivoSolicitado)
        && strtolower(pathinfo($arquivoSolicitado, PATHINFO_EXTENSION)) !== 'php'
    ) {
        return false;
    }
}

// Inicia a sessao com cookies protegidos.
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}
require_once dirname(__DIR__) . '/vendor/autoload.php';

use App\Core\Roteador;
use App\Core\AuthGuard;
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\TutorController;
use App\Controllers\PetController;
use App\Controllers\AgendamentoController;

$roteador = new Roteador();
$guardAutenticado = [[AuthGuard::class, 'proteger']];

// Rotas de acesso
$roteador->registrar('GET', '/login', [AuthController::class, 'formulario']);
$roteador->registrar('POST', '/login', [AuthController::class, 'login']);
$roteador->registrar('POST', '/logout', [AuthController::class, 'logout'], $guardAutenticado);

// Painel principal
$roteador->registrar('GET', '/', [HomeController::class, 'index'], $guardAutenticado);

// Tutores
$roteador->registrar('GET', '/tutores', [TutorController::class, 'listar'], $guardAutenticado);
$roteador->registrar('GET', '/tutores/novo', [TutorController::class, 'formulario'], $guardAutenticado);
$roteador->registrar('POST', '/tutores/salvar', [TutorController::class, 'salvar'], $guardAutenticado);
$roteador->registrar('GET', '/tutores/editar', [TutorController::class, 'formularioEdicao'], $guardAutenticado);
$roteador->registrar('POST', '/tutores/atualizar', [TutorController::class, 'atualizar'], $guardAutenticado);

// Pets
$roteador->registrar('GET', '/pets', [PetController::class, 'listar'], $guardAutenticado);
$roteador->registrar('GET', '/pets/novo', [PetController::class, 'formulario'], $guardAutenticado);
$roteador->registrar('POST', '/pets/salvar', [PetController::class, 'salvar'], $guardAutenticado);

// Agendamentos
$roteador->registrar('GET', '/agendamentos', [AgendamentoController::class, 'listar'], $guardAutenticado);
$roteador->registrar('GET', '/agendamentos/novo', [AgendamentoController::class, 'formulario'], $guardAutenticado);
$roteador->registrar('POST', '/agendamentos/salvar', [AgendamentoController::class, 'salvar'], $guardAutenticado);
$roteador->registrar('GET', '/agendamentos/editar-data', [AgendamentoController::class, 'formularioEditarData'], $guardAutenticado);
$roteador->registrar('POST', '/agendamentos/atualizar-data', [AgendamentoController::class, 'atualizarData'], $guardAutenticado);
$roteador->registrar('POST', '/agendamentos/concluir', [AgendamentoController::class, 'concluir'], $guardAutenticado);
$roteador->registrar('POST', '/agendamentos/excluir', [AgendamentoController::class, 'excluir'], $guardAutenticado);

// Envia a requisicao para a rota correspondente.
$roteador->despachar($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);