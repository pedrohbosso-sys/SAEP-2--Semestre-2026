<?php
declare(strict_types=1);

namespace App\Core;

use PDO;
use Exception;
use RuntimeException;

final class ConexaoBanco {
    private static ?PDO $instancia = null;

    private function __construct() {}
    private function __clone(): void {}

    public function __wakeup(): void {
        throw new Exception("Desserializacao nao permitida.");
    }

    public static function obterConexao(string $caminhoConfig): PDO {
        if (self::$instancia === null) {
            $dados = parse_ini_file($caminhoConfig, true);
            if ($dados === false || !isset($dados['database'])) {
                throw new RuntimeException('A configuracao do banco de dados nao pode ser lida.');
            }
            $cfg = $dados['database'];
            $host = getenv('SISVET_DB_HOST') ?: $cfg['db_host'];
            $port = getenv('SISVET_DB_PORT') ?: $cfg['db_port'];
            $name = getenv('SISVET_DB_NAME') ?: $cfg['db_name'];
            $user = getenv('SISVET_DB_USER') ?: $cfg['db_user'];
            $password = getenv('SISVET_DB_PASSWORD');
            if ($password === false) {
                $password = $cfg['db_pass'];
            }
            $dsn = sprintf(
                '%s:host=%s;port=%s;dbname=%s',
                $cfg['db_driver'],
                $host,
                $port,
                $name
            );
            self::$instancia = new PDO($dsn, $user, $password, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false
            ]);
        }
        return self::$instancia;
    }
}