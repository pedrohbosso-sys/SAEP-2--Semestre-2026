<?php
declare(strict_types=1);

namespace App\Core;

use RuntimeException;

final class Criptografia {
    private static function obterChave(): string {
        $chave = getenv('SISVET_APP_KEY');
        if ($chave === false) {
            $cfg = parse_ini_file(dirname(__DIR__, 2) . '/config/database.ini', true);
            $chave = $cfg['security']['app_key'] ?? '';
        }

        if (!is_string($chave) || strlen($chave) < 32) {
            throw new RuntimeException('Configure SISVET_APP_KEY com pelo menos 32 caracteres.');
        }

        return hash('sha256', $chave, true);
    }

    public static function cifrar(string $dado): string {
        $iv = random_bytes(12);
        $tag = '';
        $cifrado = openssl_encrypt($dado, 'aes-256-gcm', self::obterChave(), OPENSSL_RAW_DATA, $iv, $tag);

        if ($cifrado === false) {
            throw new RuntimeException('Nao foi possivel criptografar o CPF.');
        }

        return base64_encode($iv . $tag . $cifrado);
    }

    public static function decifrar(string $dadoCifrado): string {
        if ($dadoCifrado === '') {
            return '';
        }

        $dadosBrutos = base64_decode($dadoCifrado, true);
        if ($dadosBrutos === false || strlen($dadosBrutos) < 29) {
            throw new RuntimeException('O CPF armazenado possui formato criptografico invalido.');
        }

        $iv = substr($dadosBrutos, 0, 12);
        $tag = substr($dadosBrutos, 12, 16);
        $cifrado = substr($dadosBrutos, 28);
        $resultado = openssl_decrypt($cifrado, 'aes-256-gcm', self::obterChave(), OPENSSL_RAW_DATA, $iv, $tag);

        if ($resultado === false) {
            throw new RuntimeException('Nao foi possivel validar ou descriptografar o CPF armazenado.');
        }

        return $resultado;
    }
}
