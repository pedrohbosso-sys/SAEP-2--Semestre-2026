<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Controller\Controller;
use App\Models\Usuario;

final class AuthController extends Controller {
    public function formulario(): void {
        $mensagemExpiracao = isset($_GET['expirada'])
            ? 'Sua sessão expirou por inatividade. Faça login novamente.'
            : null;

        $this->renderizar('auth/login', [
            'titulo'    => 'Login - Clínica Veterinária VetCare',
            'erro'      => $mensagemExpiracao,
            'csrfToken' => $this->obterCsrfToken()
        ]);
    }

    public function login(): void {
        if (!$this->validarCsrfToken($_POST['csrf_token'] ?? null)) {
            $this->rejeitarCsrf();
            return;
        }
        $email = trim($_POST['email'] ?? '');
        $senha = (string)($_POST['senha'] ?? '');
        $usuario = Usuario::buscarPorEmail($email);

        $senhaCorreta = $usuario && password_verify($senha, $usuario['senha_hash']);
        if (!$senhaCorreta) {
            $this->renderizar('auth/login', [
                'titulo'    => 'Login',
                'erro'      => 'Falha na autenticação: E-mail ou senha incorretos.',
                'csrfToken' => $this->obterCsrfToken()
            ]);
            return;
        }

        session_regenerate_id(true);
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $_SESSION['usuario_id'] = (int)$usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_perfil'] = $usuario['perfil'];
        $_SESSION['ultimo_acesso'] = time();
        $this->redirecionar('/');
    }

    public function logout(): void {
        if (!$this->validarCsrfToken($_POST['csrf_token'] ?? null)) {
            $this->rejeitarCsrf();
            return;
        }
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
        $this->redirecionar('/login');
    }
}