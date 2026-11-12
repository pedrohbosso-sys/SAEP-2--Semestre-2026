<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Controller\Controller;
use App\Models\Agendamento;
use App\Models\Tutor;
use App\Models\Pet;
use DateTimeImmutable;

final class AgendamentoController extends Controller {
    public function listar(): void {
        $agendamentos = Agendamento::listarOrdenadoPorData();
        $this->renderizar('agendamentos/listar', [
            'titulo'       => 'Agendamentos da Clínica',
            'agendamentos' => $agendamentos,
            'csrfToken'    => $this->obterCsrfToken()
        ]);
    }

    public function formulario(): void {
        $tutores = Tutor::listar();
        $pets = Pet::listarTodos();
        $this->renderizar('agendamentos/criar', [
            'titulo'    => 'Novo Agendamento',
            'tutores'   => $tutores,
            'pets'      => $pets,
            'csrfToken' => $this->obterCsrfToken()
        ]);
    }

    public function salvar(): void {
        if (!$this->validarCsrfToken($_POST['csrf_token'] ?? null)) {
            $this->rejeitarCsrf();
            return;
        }
        $tutorId = (int)($_POST['tutor_id'] ?? 0);
        $petId = (int)($_POST['pet_id'] ?? 0);
        $data = trim($_POST['data_agendamento'] ?? '');
        $hora = trim($_POST['hora_agendamento'] ?? '');
        $servico = trim($_POST['servico'] ?? '');
        $usuarioId = (int)($_SESSION['usuario_id'] ?? 0);

        $dadosValidos = $tutorId > 0
            && $petId > 0
            && $usuarioId > 0
            && $this->dataValida($data)
            && $this->horaValida($hora)
            && $servico !== '';

        if ($dadosValidos && Agendamento::salvar($tutorId, $petId, $usuarioId, $data, $hora, $servico)) {
            $this->redirecionar('/agendamentos');
        }
        $this->renderizar('agendamentos/criar', [
            'titulo'    => 'Novo Agendamento',
            'tutores'   => Tutor::listar(),
            'pets'      => Pet::listarTodos(),
            'erro'      => 'Confira os dados e verifique se o pet pertence ao tutor selecionado.',
            'csrfToken' => $this->obterCsrfToken()
        ]);
    }

    public function formularioEditarData(): void {
        $id = (int)($_GET['id'] ?? 0);
        $agendamento = Agendamento::buscarPorId($id);
        if (!$agendamento) {
            $this->redirecionar('/agendamentos');
        }
        $this->renderizar('agendamentos/editar-data', [
            'titulo'      => 'Atualizar Data do Agendamento #' . $id,
            'agendamento' => $agendamento,
            'csrfToken'   => $this->obterCsrfToken()
        ]);
    }

    public function atualizarData(): void {
        if (!$this->validarCsrfToken($_POST['csrf_token'] ?? null)) {
            $this->rejeitarCsrf();
            return;
        }
        $id = (int)($_POST['id'] ?? 0);
        $novaData = trim($_POST['data_agendamento'] ?? '');
        $novaHora = trim($_POST['hora_agendamento'] ?? '');
        $servico = trim($_POST['servico'] ?? '');
        $agendamento = Agendamento::buscarPorId($id);

        if ($id <= 0 || !$agendamento) {
            $this->redirecionar('/agendamentos');
        }

        if ($this->dataValida($novaData) && $this->horaValida($novaHora)
            && $servico !== '' && strlen($servico) <= 128) {
            Agendamento::atualizar($id, $novaData, $novaHora, $servico);
            $this->redirecionar('/agendamentos');
        }

        $this->renderizar('agendamentos/editar-data', [
            'titulo'      => 'Atualizar Data do Agendamento #' . $id,
            'agendamento' => $agendamento,
            'erro'        => 'Informe uma data, um horário e um serviço válidos (até 128 caracteres).',
            'csrfToken'   => $this->obterCsrfToken()
        ]);
    }

    public function concluir(): void {
        if (!$this->validarCsrfToken($_POST['csrf_token'] ?? null)) {
            $this->rejeitarCsrf();
            return;
        }

        $id = (int)($_POST['id'] ?? 0);
        $agendamento = Agendamento::buscarPorId($id);
        if ($id > 0 && $agendamento && $agendamento['status'] === 'AGENDADO') {
            Agendamento::concluir($id);
        }
        $this->redirecionar('/agendamentos');
    }

    public function excluir(): void {
        if (!$this->validarCsrfToken($_POST['csrf_token'] ?? null)) {
            $this->rejeitarCsrf();
            return;
        }

        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0 && Agendamento::buscarPorId($id)) {
            Agendamento::excluir($id);
        }
        $this->redirecionar('/agendamentos');
    }

    private function dataValida(string $data): bool {
        $dataFormatada = DateTimeImmutable::createFromFormat('!Y-m-d', $data);
        return $dataFormatada !== false && $dataFormatada->format('Y-m-d') === $data;
    }

    private function horaValida(string $hora): bool {
        $horaFormatada = DateTimeImmutable::createFromFormat('!H:i', $hora);
        return $horaFormatada !== false && $horaFormatada->format('H:i') === $hora;
    }
}