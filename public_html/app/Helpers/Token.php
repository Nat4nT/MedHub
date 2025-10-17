<?php

namespace App\Helpers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use DateTimeImmutable;

class Token
{
    private static string $secret = 'minha-chave-secreta'; // coloque no .env depois

    public static function gerarToken(array $payload): string
    {
        $agora = new DateTimeImmutable();
        $expira = $agora->modify('+1 hour')->getTimestamp();

        $dados = array_merge($payload, [
            'iat' => $agora->getTimestamp(),
            'exp' => $expira,
        ]);

        return JWT::encode($dados, self::$secret, 'HS256');
    }

    public static function validarToken(string $token): object
    {
        return JWT::decode($token, new Key(self::$secret, 'HS256'));
    }
}
