<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Controller\Controller;
use App\Models\Tutor;

/**
 * Controller para gerenciamento, cadastro, busca e edicao de tutores.
 */
final class TutorController extends Controller {
    public function listar(): void {
        $termo = trim((string)($_GET['q'] ?? ''));
        $tutores = Tutor::listar($termo !== '' ? $termo : null);
        $this->renderizar('tutores/listar', [
            'titulo'  => 'Gerenciamento de Tutores',
            'tutores' => $tutores,
            'busca'   => $termo
        ]);
    }

    public function formulario(): void {
        $this->renderizar('tutores/criar', [
            'titulo'    => 'Novo Tutor',
            'csrfToken' => $this->obterCsrfToken()
        ]);
    }

    public function salvar(): void {
        if (!$this->validarCsrfToken($_POST['csrf_token'] ?? null)) {
            $this->rejeitarCsrf();
            return;
        }
        $nome = trim($_POST['nome'] ?? '');
        $cpf = trim($_POST['cpf'] ?? '');
        $telefone = trim($_POST['telefone'] ?? '');
        $email = trim($_POST['email'] ?? '');

        $dadosValidos = $nome !== ''
            && $cpf !== ''
            && $telefone !== ''
            && filter_var($email, FILTER_VALIDATE_EMAIL);

        if ($dadosValidos) {
            Tutor::salvar($nome, $cpf, $telefone, $email);
            $this->redirecionar('/tutores');
        }
        $this->renderizar('tutores/criar', [
            'titulo'    => 'Novo Tutor',
            'erro'      => 'Preencha os campos obrigatórios com dados válidos.',
            'csrfToken' => $this->obterCsrfToken()
        ]);
    }

    public function formularioEdicao(): void {
        $id = (int)($_GET['id'] ?? 0);
        $tutor = Tutor::buscarPorId($id);
        if (!$tutor) {
            $this->redirecionar('/tutores');
        }
        $this->renderizar('tutores/editar', [
            'titulo'    => 'Editar Dados do Tutor',
            'tutor'     => $tutor,
            'csrfToken' => $this->obterCsrfToken()
        ]);
    }

    public function atualizar(): void {
        if (!$this->validarCsrfToken($_POST['csrf_token'] ?? null)) {
            $this->rejeitarCsrf();
            return;
        }
        $id = (int)($_POST['id'] ?? 0);
        $nome = trim($_POST['nome'] ?? '');
        $cpf = trim($_POST['cpf'] ?? '');
        $telefone = trim($_POST['telefone'] ?? '');
        $email = trim($_POST['email'] ?? '');

        $dadosValidos = $id > 0
            && $nome !== ''
            && $cpf !== ''
            && $telefone !== ''
            && filter_var($email, FILTER_VALIDATE_EMAIL);

        if ($dadosValidos) {
            Tutor::atualizar($id, $nome, $cpf, $telefone, $email);
            $this->redirecionar('/tutores');
        }
        $this->redirecionar('/tutores/editar?id=' . $id);
    }
}