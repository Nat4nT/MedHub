<?php

namespace App\Controllers;

use App\Helpers\JsonResponse;
use App\Models\UsuarioModel;
use App\Services\AutenticacaoService;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class LoginController
{
    public function realizarLogin(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $email = $data['email'] ?? '';
        $password = $data['senha'] ?? '';

        $token = (new AutenticacaoService())->realizarLogin($email, $password);
        $json = new JsonResponse();

        if (!$token) {
            return $json->emitirResposta($response, ['error' => 'Login inválido'], 401);
        }

        

        return $json->emitirResposta($response, ['token' => $token]);
    }

}
