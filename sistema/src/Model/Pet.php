<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\ConexaoBanco;
use PDO;

final class Pet {
    private static function pdo(): PDO {
        $cfg = dirname(__DIR__, 2) . '/config/database.ini';
        return ConexaoBanco::obterConexao($cfg);
    }

    public static function listarTodos(): array {
        $sql = "SELECT p.id, p.nome, p.especie, p.raca, p.data_nascimento, t.nome AS tutor_nome, t.id AS tutor_id
                FROM pets p
                JOIN tutores t ON t.id = p.tutor_id
                ORDER BY p.nome ASC";
        return self::pdo()->query($sql)->fetchAll();
    }

    public static function buscarPorId(int $id): ?array {
        $sql = "SELECT id, tutor_id, nome, especie, raca, data_nascimento FROM pets WHERE id = :id LIMIT 1";
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute([':id' => $id]);
        $pet = $stmt->fetch();
        return $pet ?: null;
    }

    public static function salvar(
        int $tutorId,
        string $nome,
        string $especie,
        string $raca,
        string $dataNascimento
    ): bool {
        $sql = "INSERT INTO pets (tutor_id, nome, especie, raca, data_nascimento)
                SELECT id, :nome, :especie, :raca, :data_nascimento
                FROM tutores
                WHERE id = :tutor_id";
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute([
            ':tutor_id' => $tutorId,
            ':nome' => trim($nome),
            ':especie' => trim($especie),
            ':raca' => trim($raca),
            ':data_nascimento' => $dataNascimento
        ]);
        return $stmt->rowCount() === 1;
    }

    public static function totalPets(): int {
        $sql = 'SELECT COUNT(*) FROM pets';
        return (int)self::pdo()->query($sql)->fetchColumn();
    }
}