<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\ConexaoBanco;
use PDO;

final class Agendamento {
    private static function pdo(): PDO {
        $cfg = dirname(__DIR__, 2) . '/config/database.ini';
        return ConexaoBanco::obterConexao($cfg);
    }

    public static function listarOrdenadoPorData(): array {
        $sql = "SELECT a.id, a.data_agendamento, a.hora_agendamento, a.servico, a.status, a.observacao,
                       t.id AS tutor_id, t.nome AS tutor_nome, t.telefone AS tutor_telefone, t.email AS tutor_email,
                       p.id AS pet_id, p.nome AS pet_nome, p.especie AS pet_especie, p.raca AS pet_raca,
                       p.data_nascimento AS pet_data_nascimento,
                       u.nome AS responsavel_nome
                FROM agendamentos a
                JOIN tutores t ON t.id = a.tutor_id
                JOIN pets p ON p.id = a.pet_id
                JOIN usuarios u ON u.id = a.usuario_id
                ORDER BY a.data_agendamento ASC, a.hora_agendamento ASC";
        return self::pdo()->query($sql)->fetchAll();
    }

    public static function buscarPorId(int $id): ?array {
        $sql = "SELECT a.id, a.data_agendamento, a.hora_agendamento, a.servico, a.status,
                       t.nome AS tutor_nome, p.nome AS pet_nome
                FROM agendamentos a
                JOIN tutores t ON t.id = a.tutor_id
                JOIN pets p ON p.id = a.pet_id
                WHERE a.id = :id LIMIT 1";
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute([':id' => $id]);
        $agendamento = $stmt->fetch();
        return $agendamento ?: null;
    }

    public static function atualizar(int $id, string $data, string $hora, string $servico): void {
        $sql = "UPDATE agendamentos
                SET data_agendamento = :data, hora_agendamento = :hora, servico = :servico
                WHERE id = :id";
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute([
            ':id' => $id,
            ':data' => $data,
            ':hora' => $hora,
            ':servico' => trim($servico)
        ]);
    }

    public static function concluir(int $id): void {
        $sql = "UPDATE agendamentos SET status = 'CONCLUIDO' WHERE id = :id AND status = 'AGENDADO'";
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute([':id' => $id]);
    }

    public static function excluir(int $id): void {
        $stmt = self::pdo()->prepare('DELETE FROM agendamentos WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }

    public static function salvar(
        int $tutorId,
        int $petId,
        int $usuarioId,
        string $data,
        string $hora,
        string $servico
    ): bool {
        $sql = "INSERT INTO agendamentos (tutor_id, pet_id, usuario_id, data_agendamento, hora_agendamento, servico)
                SELECT p.tutor_id, p.id, :usuario_id, :data, :hora, :servico
                FROM pets p
                WHERE p.id = :pet_id AND p.tutor_id = :tutor_id";
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute([
            ':tutor_id' => $tutorId,
            ':pet_id' => $petId,
            ':usuario_id' => $usuarioId,
            ':data' => $data,
            ':hora' => $hora,
            ':servico' => trim($servico)
        ]);
        return $stmt->rowCount() === 1;
    }

    public static function atualizarData(int $id, string $novaData, ?string $novaHora = null): bool {
        if ($novaHora !== null && trim($novaHora) !== '') {
            $sql = "UPDATE agendamentos SET data_agendamento = :dt, hora_agendamento = :hr WHERE id = :id";
            $parametros = [':dt' => $novaData, ':hr' => $novaHora, ':id' => $id];
        } else {
            $sql = "UPDATE agendamentos SET data_agendamento = :dt WHERE id = :id";
            $parametros = [':dt' => $novaData, ':id' => $id];
        }

        $stmt = self::pdo()->prepare($sql);
        $stmt->execute($parametros);
        return $stmt->rowCount() === 1;
    }
}