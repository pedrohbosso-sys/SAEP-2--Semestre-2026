<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Controller\Controller;
use App\Models\Pet;
use App\Models\Tutor;
use DateTimeImmutable;

final class PetController extends Controller {
    public function listar(): void {
        $pets = Pet::listarTodos();
        $this->renderizar('pets/listar', [
            'titulo' => 'Gerenciamento de Pets',
            'pets'   => $pets
        ]);
    }

    public function formulario(): void {
        $tutores = Tutor::listar();
        $this->renderizar('pets/criar', [
            'titulo'    => 'Cadastrar Novo Pet',
            'tutores'   => $tutores,
            'csrfToken' => $this->obterCsrfToken()
        ]);
    }

    public function salvar(): void {
        if (!$this->validarCsrfToken($_POST['csrf_token'] ?? null)) {
            $this->rejeitarCsrf();
            return;
        }
        $tutorId = (int)($_POST['tutor_id'] ?? 0);
        $nome = trim($_POST['nome'] ?? '');
        $especie = trim($_POST['especie'] ?? '');
        $raca = trim($_POST['raca'] ?? '');
        $dataNascimento = trim($_POST['data_nascimento'] ?? '');

        $data = DateTimeImmutable::createFromFormat('!Y-m-d', $dataNascimento);
        $dataValida = $data !== false && $data->format('Y-m-d') === $dataNascimento;
        $dadosValidos = $tutorId > 0 && $nome !== '' && $especie !== '' && $raca !== '' && $dataValida;

        if ($dadosValidos && Pet::salvar($tutorId, $nome, $especie, $raca, $dataNascimento)) {
            $this->redirecionar('/pets');
        }
        $this->renderizar('pets/criar', [
            'titulo'    => 'Cadastrar Novo Pet',
            'erro'      => 'Selecione um tutor existente e preencha todos os dados do animal corretamente.',
            'tutores'   => Tutor::listar(),
            'csrfToken' => $this->obterCsrfToken()
        ]);
    }
}